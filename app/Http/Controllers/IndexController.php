<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Leads_Upload_Area;
// use App\Models\Upload_Offer;



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


// for stripe
use Stripe\Charge;
use Stripe\Stripe;

/** All Paypal Details class **/
// use Srmklive\PayPal\Services\ExpressCheckout;


// paypal subscription
use PayPal\Api\Agreement;
use PayPal\Api\Payer;
use PayPal\Api\Plan;

// Update Subscription Plan
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Common\PayPalModel;





/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class IndexController extends Controller
{
    // varibale to get paypal credentials
    public $apiContext;   

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // parent::__construct();


        $this->apiContext = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(
                    env('PAYPAL_CLIENT_ID'),// ClientID
                    env('PAYPAL_SECRET')// ClientSecret
                )
        );

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

            $offers = Leads_Upload_Area::with('memberships')->whereNull('deleted_at')->get();

            $files = json_decode($offers);            

            // $membership = DB::table('memberships')->whereNull('deleted_at')->get();

            // var_dump($membership);
            $fetch_membership_level = DB::table('memberships')->whereNull('deleted_at')->WHERE('id', Auth::user()->membership_id)->first();
            if($fetch_membership_level){
                $membership_level = $fetch_membership_level->membership_level;
            }else{
                $membership_level = '';
            }

            return view('frontend/profile', compact('title', 'files',  'membership_level'));
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
    public function user_subscription_page($id){

        $title = 'Subscription';
        
        $membership_id = $id;

        $fetch_membership_name = DB::table('memberships')->WHERE('id', $membership_id)->first();
        if($fetch_membership_name){

            $membership_name = $fetch_membership_name->membership_name;
            $membership_cost = $fetch_membership_name->cost;
            $subscription_period = $fetch_membership_name->subscription_period;
            $stripe_plan_id  = $fetch_membership_name->stripe_plan_id;
            $error = '';
            return view('frontend/register', compact('title', 'membership_name', 'membership_id', 'error', 'membership_cost', 'subscription_period', 'stripe_plan_id'));            
        }else{
            \Session::flash('message','No Data exist');
            $error = 'No data exist';
            return view('frontend/register', compact('title', 'error')); 
        }
        
    }


    public function user_registration_page(){

        $title = 'Registration';
        
        $memeberships = DB::table('memberships')->get();

        return view('frontend/register', compact('title', 'memeberships', 'membership_id'));
        
    }

    // Regiser User
    public function register_check(Request $request)
    {
        $data = $request;
        $name = $request->input('user_name');
        $email = $request->input('email');
        $membership_id = $request->input('membership_id');
        $page = $request->input('payment_page');
        
        


        $authenticateResult = $this->register_authenticate($email);
        if ($authenticateResult === true) { 
            $user = [
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($request->input('password')),
                'context_id' => 0,
                'type' => "user",
                'created_at' => Carbon::now(),
                'membership_id' => 0
            ];

            $insertedUsersid = DB::table('users')->insertGetId($user);

            $page = $page.":".$insertedUsersid;



            echo IndexController::payment_integration($data, $page);            
            

            // // Get email content from database
            // $get_email_content = DB::table('email_templates')->Where('options', 'Registeration')->whereNull('deleted_at')->first();
            // $content = $get_email_content->email_content;
            // $subject = $get_email_content->subject;

            // // Get membership name
            // $get_membership_name = DB::table('memberships')->Where('id', $membership_id)->whereNull('deleted_at')->first();
            // $membership_name = $get_membership_name->membership_name;
        


            // $name_updated = str_replace('[username]', $name, $content);
            // $registration_content = str_replace('[membership]', $membership_name, $name_updated);




            // $data = array( 'email' => $email, 'subject' => $subject, 'message' => $registration_content);

            // // Send email

            // Mail::send([], $data, function ($m) use($data) {                    
            //     $m->to($data['email'])->subject($data['subject'])->setBody($data['message'], 'text/html');
            // });

            // return redirect('/signin')->with('message', 'Registerd Successfully Kindly Login');
        }
        else {
            // echo "failed";
            // \Session::flash('message', $authenticateResult);
            // return redirect()->back()->with('error', 'error');
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


/*************************** Forgot Password *********************************/

    public function forgot_password(){
        $title = "Forgot Password";
        return view('frontend/forgot_password', compact('title'));
    }

/*************************** Update Password *********************************/

    public function update_password(Request $request){
        
        // User email
        $email = $request->email;

        // Fetch user name
        $fetch_name = DB::table('users')->where('email', $email)->first();
        if($fetch_name){

            $name = $fetch_name->name;

            // Generate Random String for Password

            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
               $charactersLength = strlen($characters);
               $randomString = '';
               for ($i = 0; $i < 10; $i++) {
                   $randomString .= $characters[rand(0, $charactersLength - 1)];
               }

            // Password
            $password = $randomString;
            $password_db = bcrypt($password);

            DB::table('users')->where('email', $email)->update(['password' => $password_db]);

            // Get forgot password email content

            $fetch_content = DB::table('email_templates')->where('options','Forgot Password')->first();

            $upgrade_content = $fetch_content->email_content;
            $upgrade_subject = $fetch_content->subject;


            $name_updated = str_replace('[username]', $name, $upgrade_content);
            $password_updated = str_replace('[password]', $password, $name_updated);
             
            $data = array( 'email' => [$email], 'subject' => $upgrade_subject, 'message' => $password_updated);         

            Mail::send([], $data, function ($m) use($data) {                    
                 $m->to($data['email'])->subject($data['subject'])->setBody($data['message'], 'text/html');
             });

            \Session::flash('message','Password Updated... <br> Check your email');
            
            return redirect("/signin");
        }else{

            \Session::flash('message','Email did not match...');
            
            return redirect("/forgot_password");

        }



    }




    /*********************** Payment Page ********************/

    public function payment_page(Request $request){

        $unsubscribe = $request->unsubscribe;

        if($unsubscribe == 'true'){
            $stripe = new \Stripe\Stripe();

            $stripe->setApiKey("sk_test_doaAddzso5GZH5xoQ4YwDbQO");

            $sub_id = Auth::user()->stripe_subscription_id;
            // check if subscription is stripe
            if(!empty($sub_id)){

                $sub = \Stripe\Subscription::retrieve($sub_id);
                
                $sub->cancel();

            }
            // if subscription is paypal
            else{

            }

            DB::table('users')->where('id', Auth::user()->id)->update(['membership_id' => 0, 'stripe_subscription_id' => '']);

            \Session::flash('message','Membership unsubscribed Successfully');            

            return redirect('/profile');


        }else{
            
            $name = $request->user_name;
            $membership_name = $request->membership_name;
            $membership_cost = $request->membership_cost;
            $membership_id = $request->membership_id;
            $membership_type = $request->membership_type;
            $subscription_period = $request->subscription_period;
            if ($membership_type != 'Free' && $subscription_period != 'Lifetime') {

                $planId = $request->planId;
                $stripeplanId = $request->stripeplanId;
                
                $form_data = [$name, $membership_name, $membership_cost,$membership_id, $membership_type, $subscription_period , $planId, $stripeplanId];            
            }else{

                $form_data = [$name, $membership_name, $membership_cost,$membership_id, $membership_type, $subscription_period ];            
            }

            $title = 'Proceed to checkout';
            
            return view('frontend/payment_page', compact('form_data', 'title'));
        }

    }
    
    /*********************** Payment Integration ********************/


    public function payment_integration(Request $request, $page = false){
       
        $option = $request->payment_method_;
        $membership_cost = $request->membership_cost;
        $membership_name = $request->membership_name;
        $membership_id = $request->membership_id;
        $user_name = $request->user_name;        
        $membership_type = $request->membership_type;        
        $subscription_period = $request->subscription_period;        
        $email = $request->email;

        Session::set('membershipID', $membership_id);
        Session::set('page', $page);
        Session::set('paymentMethod', $option);
        
        // if cost greater then 0 or not free
        if($membership_cost > 0 && $membership_type != 'Free' ) {

            Stripe::setApiKey('sk_test_doaAddzso5GZH5xoQ4YwDbQO');

            if ($subscription_period != 'Lifetime') {

                // for subscription plan
                if($option == 'stripe'){

                    // Create customer id using stripe token
                    $stripeCustomer = new \Stripe\Customer();

                    $createdCustomer = $stripeCustomer->create([
                      "description" => "Customer for ". $email,
                      "source" => $request->stripe_token, // obtained with Stripe.js
                      "email" => $email
                    ]);

                    // retrived from db
                    $stripeplanId = $request->stripeplanId;                                        
                    $stripe_subscription = new \Stripe\Subscription();

                    try{

                        $createdSubscription = $stripe_subscription->create([
                            "customer" => $createdCustomer->id,
                              "items" => [
                                [
                                  "plan" => $stripeplanId,
                                ],
                              ]
                        ]);                                               

                        Session::set('subscriptioID', $createdSubscription->id);

                        return redirect('/update_membership/true');

                    }catch (\Ecxeption $e){
                        
                        \Session::flash('message',$e->getMessage());
                        
                         return redirect('/profile');

                    }

                }                
                else{             

                    // setting the agreement start time after two minutes
                    $st = date("Y-m-d\TH:i:s\Z",strtotime('+2 minute'));

                    // plan id
                    $planId = $request->planId;
                    
                    // create agreement between marchant and buyer
                    $agreement = new Agreement();

                    $agreement->setName('Agreement For subscribing '.$membership_name)
                        ->setDescription('Agreement is placed at cost '.$membership_cost.'/- for every '.$subscription_period)                                      
                        ->setStartDate($st);

                    // getting plan for which agreement is created

                    $plan = new Plan();
                    
                    $plan->setId($planId);

                    $agreement->setPlan($plan);

                    // setting up payer

                    $payer = new Payer();
                    
                    $payer->setPaymentMethod('paypal');
                    
                    $agreement->setPayer($payer);                    
                    
                    try{


                        $agreement = $agreement->create($this->apiContext);
                        
                        $approvalUrl = $agreement->getApprovalLink();

                        return redirect($approvalUrl);

                    }catch( Exception $e){
                        echo $e->getData();
                    }
                }               
            }
            else{
                // for one time payment
                if($option == 'stripe'){
                    try{
                        Charge::create(array(
                            'amount' => $membership_cost*100,
                            'currency' => 'USD',
                            'source' => $request->stripe_token,
                            'description' => 'Membership upgraded to '.$membership_name
                        ));                         
                        
                        return redirect('/update_membership/true');
                        

                    } catch(\Exception $e){

                    \Session::flash('message',$e->getMessage());
                    echo $e->getMessage();
                     return redirect('/profile');
                    }
                    
                    // \Session::flash('message','Account Upgraded');
                    
                    // return redirect("/profile");
                }
                else{

                   

                }
                // else if($option == 'paypal'){
                //             $provider = new ExpressCheckout;                    

                //             $data = [];
                //             $data['items'] = [
                //                 [
                //                     'name' => $membership_name,
                //                     'price' => $membership_cost,
                //                     'qty' => 1
                //                 ]
                //             ];

                //             $data['invoice_id'] = uniqid();
                //             $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
                //             $data['return_url'] = url('/payment_succ/'.$membership_id.'/'.$page);
                //             $data['cancel_url'] = url('/payment_failed');
                //             $data['total'] = $membership_cost;

                //             $response = $provider->setExpressCheckout($data, true);
                //             // var_dump($response);
                //             return redirect($response['paypal_link']);
                // }
            }               
        }   
        else{
            return redirect('/update_membership/true');
            // old one time payment
            // return redirect('/update_membership/'.$membership_id.'/'.$page);
        }

    }

    public function successful_payment($id, Request $request, $page = false){       

       //  $membership_id = $id;


       //  $fetch_name = DB::table('memberships')->where('id', $membership_id)->first();

       //  $membership_name = $fetch_name->membership_name;
       //  $membership_cost = $fetch_name->cost;
        
       //  $provider = new ExpressCheckout; 

       //  $token = $request->token;       


       //  $response = $provider->getExpressCheckoutDetails($token);


       //  $payer_id = $response['PAYERID'];
       //  $invoiced_id = $response['INVNUM']??uniqid();
       // $data = [];
       // $data['items'] = [
       //     [
       //         'name' => $membership_name,
       //         'price' => $membership_cost,
       //         'qty' => 1
       //     ]
       // ];

       // $data['invoice_id'] = $invoiced_id;
       // $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
       // $data['return_url'] = url('/payment_succ/'.$membership_id.'/'.$page);
       // $data['cancel_url'] = url('/payment_failed');
       // $data['total'] = $membership_cost;

       //  $response = $provider->doExpressCheckoutPayment($data, $token, $payer_id);
       
        
       //  if ($response['PAYMENTINFO_0_ACK'] == 'Success') {
       //      return redirect('/update_membership/'.$membership_id.'/'.$page);
       //  }
    }
   
    public function unsuccessful_payment(Request $request){
        \Session::flash('message','Payment Cancelled');

        return redirect('/profile');
    }

// Normal Payment
    // public function update_membership($id, $page = false){
            
    //     $membership_id = $id;        
        

    //     $page_id = explode(":", $page);

    //     if ($page_id[0] == 'registration') {            

    //         // Update Membership
    //         $update_membership = ['membership_id' => $membership_id];
            
    //         DB::table('users')->where('id', $page_id[1])->update($update_membership);

    //         // Get user name

    //         $fetch_uname = DB::table('users')->where('id', $page_id[1])->first();
    //         $name = $fetch_uname->name;
    //         $email = $fetch_uname->email;

    //         // Get email content from database
    //         $get_email_content = DB::table('email_templates')->Where('options', 'Registeration')->whereNull('deleted_at')->first();
    //         $content = $get_email_content->email_content;
    //         $subject = $get_email_content->subject;

    //         // Get membership name
    //         $get_membership_name = DB::table('memberships')->Where('id', $membership_id)->whereNull('deleted_at')->first();
    //         $membership_name = $get_membership_name->membership_name;
            


    //         $name_updated = str_replace('[username]', $name, $content);
    //         $registration_content = str_replace('[membership]', $membership_name, $name_updated);




    //         $data = array( 'email' => $email, 'subject' => $subject, 'message' => $registration_content);

    //         // Send email

    //         Mail::send([], $data, function ($m) use($data) {                    
    //             $m->to($data['email'])->subject($data['subject'])->setBody($data['message'], 'text/html');
    //         });

    //         return redirect('/signin')->with('message', 'Registerd Successfully Kindly Login');
    //     }
    //     else{


    //         $update_membership = ['membership_id' => $membership_id];
            
    //         DB::table('users')->where('id', Auth::user()->id)->update($update_membership);

    //         $fetch_name = DB::table('memberships')->where('id', $membership_id)->first();

    //         $membership_name = $fetch_name->membership_name;

    //         // get upgrade membership email content

    //         $fetch_content = DB::table('email_templates')->where('options','Upgrade')->first();

    //         $upgrade_content = $fetch_content->email_content;
    //         $upgrade_subject = $fetch_content->subject;


    //         $name_updated = str_replace('[username]', Auth::user()->name, $upgrade_content);
    //         $membership_updated = str_replace('[membership]', $membership_name, $name_updated); 
            

    //         // Fetch admin email

    //         $fetch_admin_email = DB::table('users')->where('id',1)->first();

    //         $admin_email = $fetch_admin_email->email;

    //          $data = array( 'email' => [Auth::user()->email, $admin_email], 'subject' => $upgrade_subject, 'message' => $membership_updated);         

    //         Mail::send([], $data, function ($m) use($data) {                    
    //              $m->to($data['email'])->subject($data['subject'])->setBody($data['message'], 'text/html');
    //          });

    //         \Session::flash('message','Account Upgraded');
            
    //         return redirect("/profile");
    //     }

    // }


// Subscription
    public function update_membership($status){

        if ($status == 'true') {            

            $membership_id = Session::get('membershipID');
            $page = Session::get('page');          
            $payment_method = Session::get('paymentMethod');
            $subscriptionid = Session::get('subscriptioID');
            



            $subscriptionID = ['stripe_subscription_id' => $subscriptionid];

            $page_id = explode(":", $page);

            if ($payment_method == 'paypal') {
               
                $token = $_GET['token'];

                $agreement = new \PayPal\Api\Agreement();

                $agreement->execute($token, $this->apiContext);

                dump($agreement);
                
                exit();
            } 

            
            // subscribing from registration page
            if ($page_id[0] == 'registration') {            

                // Update Subscription id
                DB::table('users')->where('id', $page_id[1])->update($subscriptionID);

                // Update Membership
                $update_membership = ['membership_id' => $membership_id];
                
                DB::table('users')->where('id', $page_id[1])->update($update_membership);

                // Get user name

                $fetch_uname = DB::table('users')->where('id', $page_id[1])->first();
                $name = $fetch_uname->name;
                $email = $fetch_uname->email;

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
            // upgrading from profile page
            else{
                 // Update Subscription id
                DB::table('users')->where('id', Auth::user()->id)->update($subscriptionID);

                $update_membership = ['membership_id' => $membership_id];
                
                DB::table('users')->where('id', Auth::user()->id)->update($update_membership);

                $fetch_name = DB::table('memberships')->where('id', $membership_id)->first();

                $membership_name = $fetch_name->membership_name;

                // get upgrade membership email content

                $fetch_content = DB::table('email_templates')->where('options','Upgrade')->first();

                $upgrade_content = $fetch_content->email_content;
                $upgrade_subject = $fetch_content->subject;


                $name_updated = str_replace('[username]', Auth::user()->name, $upgrade_content);
                $membership_updated = str_replace('[membership]', $membership_name, $name_updated); 
                

                // Fetch admin email

                $fetch_admin_email = DB::table('users')->where('id',1)->first();

                $admin_email = $fetch_admin_email->email;

                 $data = array( 'email' => [Auth::user()->email, $admin_email], 'subject' => $upgrade_subject, 'message' => $membership_updated);         

                Mail::send([], $data, function ($m) use($data) {                    
                     $m->to($data['email'])->subject($data['subject'])->setBody($data['message'], 'text/html');
                 });

                \Session::flash('message','Account Upgraded');
                
                return redirect("/profile");
            }
            
            Session::forget('membershipID');
            Session::forget('page');

        }else{
            echo "false";
        }
    }


    /*********************** FAQ ********************/

    public function faq(){
        $fetch_faq = DB::table('faqs')->get();
        $title = "FAQ";
        return view('frontend/faq', compact('fetch_faq', 'title'));
    }




    /*********************** Subscription ********************/

    public function subscription(){
        if(!Auth::check()){
            $title = "Subscription";
            return view('frontend/subscription', compact('title'));
        }else{
            return redirect('/');
        }
    }




}


