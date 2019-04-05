<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Upload_Offer;



// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

// use Illuminate\Support\Facades\Mail;
// use App\Mail\SendMailable;
use Mail;

use Session;
use Carbon\Carbon;
use Auth;

use Stripe\Charge;
use Stripe\Stripe;

/** All Paypal Details class **/
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use URL;
use Redirect;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class IndexController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // parent::__construct();
                
        /** setup PayPal api context **/
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
  //   public function index()
  //   {
  //       $roleCount = \App\Role::count();
		// if($roleCount != 0) {
		// 	if($roleCount != 0) {
		// 		return view('home');
		// 	}
		// } else {
		// 	return view('errors.error', [
		// 		'title' => 'Migration not completed',
		// 		'message' => 'Please run command <code>php artisan db:seed</code> to generate required table data.',
		// 	]);
		// }
  //   }

/*********************** Profile ********************/

    public function profile(){

        if(Auth::user()){
            $title = 'Profile';

            $offers = Upload_Offer::with('memberships')->whereNull('deleted_at')->get();

            $files = json_decode($offers);

            $membership = DB::table('memberships')->whereNull('deleted_at')->get();
            $fetch_membership_level = DB::table('memberships')->whereNull('deleted_at')->WHERE('id', Auth::user()->membership_id)->first();
            $membership_level = $fetch_membership_level->membership_level;

            return view('frontend/profile', compact('title', 'files', 'membership', 'membership_level'));
        }else{
            return redirect('/signin');
        }
    }



/*********************** Mailing System ********************/

public function mail_system_page(){

    $membership = DB::table('memberships')->whereNull('deleted_at')->get();

    return view('frontend/email_system' , compact('membership'));
}

public function get_emails(Request $emails){

    $user_email = $emails->email;

    $get_email = DB::table('users')->WHERE('email', 'like', '%'.$user_email.'%')->whereNull('deleted_at')->get();

    $output = '';
    if($get_email){
        foreach ($get_email as $key => $user) {
            $output = $output.'<li class="email_list">'.$user->email.'</li><br>';
        }    
     echo $output;
    }else{
        echo "<li>No Email Found...</li>";
    }
}

public function send_mail(Request $form){
    $option = $form->option;
    $subject = $form->subject;
    $message = $form->question_ask;
    if($option == 'single_memeber'){
        $email = $form->specific_email;

        $data = array( 'email' => $email, 'subject' => $subject, 'message' => $message);

    }else if($option == 'membership_cat'){
        $memberhip_id = $form->membership_id;

        $get_users_email = DB::table('users')->WHERE('membership_id', $memberhip_id)->whereNull('deleted_at')->get();

        $emails = [];

        foreach ($get_users_email as $data) {
            array_push($emails, $data->email);
        }

        $data = array( 'email' => $emails, 'subject' => $subject, 'message' => $message);
     

    }else if($option == 'all_members'){

        $get_users_email = DB::table('users')->whereNull('deleted_at')->get();

        $emails = [];

        foreach ($get_users_email as $data) {
            array_push($emails, $data->email);
        }

        $data = array( 'email' => $emails, 'subject' => $subject, 'message' => $message);
    }


    Mail::send([], $data, function ($m) use($data) {                    
        $m->to($data['email'])->subject($data['subject'])->setBody($data['message'], 'text/html');
    });

    \Session::flash('message','Email sent');

    return redirect(config('laraadmin.adminRoute'). '/mailing_system');

}



/*********************** Email Template********************/

// public function mail_template_page(){

//     return view('frontend/mail_template_page');
// }




/*********************** Login ********************/
    public function user_login_page(){
        if(!Auth::user()){
            $title = 'Login';
            return view('frontend/login', compact('title'));            
        }else{            
            return redirect('/profile');
        }
    }

    // Check User Login
    public function login_check(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $userGet = DB::table('users')->Where([['email', $email], ['type', 'user']])->first();
        if ($userGet) {
            $passwordchecked = $userGet->password;
            if (Hash::check($password, $passwordchecked)) {
                Auth::attempt(['email' => $email, 'password' => $password]);

                return redirect('/profile');
            }
            else{
                return redirect()->back()->withInput()->with('message', 'User Password Does Not Match! if you dont have your accout  <a href="/signup">Register Here </a>');
            }
        }else {
            return redirect()->back()->withInput()->with('message', 'This Email address is not registered <a href="/signup">Register Here </a>');
            
        }
        
    }


    public function login_authenticate($userGet) {
        if ($userGet->hash_key != Null && $userGet->status == 'deactive') {
            return $messsage = 'Please check your Email and click the confirmation link before <a href="/signin">Login </a>';
        }
        if ($userGet->hash_key == Null && $userGet->status == 'deactive') {
            return $messsage = 'Your profile is in under review process';
        }
        else {
            return true;
        }
    }

/*********************** Resgistration ********************/
    public function user_registration_page(){
        $title = 'Registration';


        $memeberships = DB::table('memberships')->whereNull('deleted_at')->get();
            
        // Payment Done

        // try{
        //     if(env('MAIL_USERNAME') != null && env('MAIL_USERNAME') != "null" && env('MAIL_USERNAME') != "") {
        //         $emails_ = ['nehalayub233@gmail.com', 'marianoor363@gmail.com'];
        //         // Send mail to User his Password
        //         Mail::send('emails.message', ['user' => 'Yourname', 'password' => 'passwordyouenter'], function ($m) {
                    
        //             $m->to(['nehalayub233@gmail.com', 'aminshoukat4@gmail.com'])->subject('LaraAdmin - Your Login Credentials');
        //         });
        //     } else {
        //         Log::info("User created: username: ".$user->email." Password: ".$password);
        //     }
        //     echo "done";
        // }catch(Exception $e){
        //     var_dump($e);
        // }

            

        return view('frontend/register', compact('title', 'memeberships'));
    }

    // Regiser User
    public function register_check(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $membership_id = $request->input('membership_id');
        $authenticateResult = $this->register_authenticate($email);
        if ($authenticateResult === true) { 
            $user = [
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($request->input('password')),
                'context_id' => 0,
                'type' => "user",
                'created_at' => Carbon::now(),
                'membership_id' => $membership_id
            ];

            $insertedUsers = DB::table('users')->insert($user);

            // Get email content from database
            $get_email_content = DB::table('email_templates')->Where('options', 'Registeration')->whereNull('deleted_at')->first();
            $content = $get_email_content->email_content;
            $subject = $get_email_content->subject;

            // Get membership name
            $get_membership_name = DB::table('memberships')->Where('id', $membership_id)->whereNull('deleted_at')->first();
            $membership_name = $get_membership_name->membership_name;
        


            $name_updated = str_replace('[username]', $name, $content);
            $registration_content = str_replace('[membership]', $membership_name, $name_updated);




            $data = array( 'email' => $email, 'subject' => $subject, 'message' => $registration_content);

            // Send email

            Mail::send([], $data, function ($m) use($data) {                    
                $m->to($data['email'])->subject($data['subject'])->setBody($data['message'], 'text/html');
            });
            return redirect('/signin')->with('message', 'Registerd Successfully Kindly Login');
        }
        else {
            return redirect()->back()->withInput()->with('message', $authenticateResult);
        }

    }

    public function register_authenticate($email)
    {
        $haveUser = DB::table('users')->WHERE('email', $email)->first();
        if ($haveUser) {
            return $messsage = 'You have already submitted register form Please check your Email and click the confirmation link before <a href="/signin">Login </a>';           
        }
        else {
            return true;
        }
    
    }
    
    /*********************** Payment Integration ********************/

// public function send_mail_(Request $form){
    public function payment_integration(Request $request){

        $option = $request->payment_method_;
        $membership_cost = $request->membership_cost;
        $membership_name = $request->membership_name;
        $membership_id = $request->membership_id;
        $user_name = $request->user_name;
        if($membership_cost > 0){
            if($option == 'stripe'){
                Stripe::setApiKey('sk_test_doaAddzso5GZH5xoQ4YwDbQO');
                try{
                    Charge::create(array(
                        'amount' => $membership_cost,
                        'currency' => 'USD',
                        'source' => $request->stripe_token,
                        'description' => 'Membership upgraded to '.$membership_name
                    ));           
                    
                    $update_membership = ['membership_id' => $membership_id];
                    DB::table('users')->where('id', Auth::user()->id)->update($update_membership);

                } catch(\Exception $e){

                \Session::flash('message',$e->getMessage());
                 return redirect('/profile');
                }
                
                // \Session::flash('message','Account Upgraded');
                
                // return redirect("/profile");
            }

            else if($option == 'paypal'){
                $payer = new Payer();
                $payer->setPaymentMethod('paypal');
                $item_1 = new Item();
                $item_1->setName('Item 1') /** item name **/
                    ->setCurrency('USD')
                    ->setQuantity(1)
                    ->setPrice($membership_cost); /** unit price **/
                $item_list = new ItemList();
                $item_list->setItems(array($item_1));
                $amount = new Amount();
                $amount->setCurrency('USD')
                    ->setTotal($membership_cost);
                $transaction = new Transaction();
                $transaction->setAmount($amount)
                    ->setItemList($item_list)
                    ->setDescription('Your transaction description');
                $redirect_urls = new RedirectUrls();
                $redirect_urls->setReturnUrl(URL::route('payment.status')) /** Specify return URL **/
                    ->setCancelUrl(URL::route('payment.status'));
                $payment = new Payment();
                $payment->setIntent('Sale')
                    ->setPayer($payer)
                    ->setRedirectUrls($redirect_urls)
                    ->setTransactions(array($transaction));
                    /** dd($payment->create($this->_api_context));exit; **/
                try {
                    $payment->create($this->_api_context);
                } catch (\PayPal\Exception\PPConnectionException $ex) {
                    if (\Config::get('app.debug')) {
                        \Session::put('error','Connection timeout');
                        return Redirect::route('addmoney.paywithpaypal');
                        /** echo "Exception: " . $ex->getMessage() . PHP_EOL; **/
                        /** $err_data = json_decode($ex->getData(), true); **/
                        /** exit; **/
                    } else {
                        \Session::put('error','Some error occur, sorry for inconvenient');
                        return Redirect::route('addmoney.paywithpaypal');
                        /** die('Some error occur, sorry for inconvenient'); **/
                    }
                }
                foreach($payment->getLinks() as $link) {
                    if($link->getRel() == 'approval_url') {
                        $redirect_url = $link->getHref();
                        break;
                    }
                }
                /** add payment ID to session **/
                Session::put('paypal_payment_id', $payment->getId());
                if(isset($redirect_url)) {
                    /** redirect to paypal **/
                    return Redirect::away($redirect_url);
                }
                \Session::put('error','Unknown error occurred');
                return Redirect::route('addmoney.paywithpaypal');
            }
        }
        // else{
        // }
            $update_membership = ['membership_id' => $membership_id];
            DB::table('users')->where('id', Auth::user()->id)->update($update_membership);

            // get upgrade membership content           


            $fetch_content = DB::table('email_templates')->where('options','Upgrade')->first();

            $upgrade_content = $fetch_content->email_content;
            $upgrade_subject = $fetch_content->subject;


            $name_updated = str_replace('[username]', Auth::user()->name, $upgrade_content);
            $membership_updated = str_replace('[membership]', $membership_name, $name_updated); 
            

            // Fetch admin email

            $fetch_admin_email = DB::table('users')->where('id',1)->first();

            $admin_email = $fetch_admin_email->email;

             $data = array( 'email' => [Auth::user()->email, $admin_email], 'subject' => $upgrade_subject, 'message' => $membership_updated);
             var_dump($data);

            Mail::send([], $data, function ($m) use($data) {                    
                 $m->to($data['email'])->subject($data['subject'])->setBody($data['message'], 'text/html');
             });

            \Session::flash('message','Account Upgraded');
            
            return redirect("/profile");

        

    }

    public function getPaymentStatus()
       {
           /** Get the payment ID before session clear **/
           $payment_id = Session::get('paypal_payment_id');
           /** clear the session payment ID **/
           Session::forget('paypal_payment_id');
           if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
               \Session::put('error','Payment failed');
               return Redirect::route('addmoney.paywithpaypal');
           }
           $payment = Payment::get($payment_id, $this->_api_context);
           /** PaymentExecution object includes information necessary **/
           /** to execute a PayPal account payment. **/
           /** The payer_id is added to the request query parameters **/
           /** when the user is redirected from paypal back to your site **/
           $execution = new PaymentExecution();
           $execution->setPayerId(Input::get('PayerID'));
           /**Execute the payment **/
           $result = $payment->execute($execution, $this->_api_context);
           /** dd($result);exit; /** DEBUG RESULT, remove it later **/
           if ($result->getState() == 'approved') { 
               
               /** it's all right **/
               /** Here Write your database logic like that insert record or value in database if you want **/
               \Session::put('success','Payment success');
               return Redirect::route('addmoney.paywithpaypal');
           }
           \Session::put('error','Payment failed');
           return Redirect::route('addmoney.paywithpaypal');
       }
}