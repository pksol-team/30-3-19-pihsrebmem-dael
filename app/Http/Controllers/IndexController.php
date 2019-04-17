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



// paypal subscription
// use PayPal\Api\Agreement;
// use PayPal\Api\Plan;

// Update Subscription Plan
// use PayPal\Api\Patch;
// use PayPal\Api\PatchRequest;
// use PayPal\Common\PayPalModel;

// one time payment
use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\Transaction;
use PayPal\Api\Amount;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;
use PayPal\Api\Details;
use PayPal\Api\ItemList;
use PayPal\Api\PaymentExecution;

use DateTime;
use DateInterval;


/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class IndexController extends Controller
{
    // varibale to get paypal credentials
    public $apiContext;   
    public $stripeKey;
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

            
        $this->stripeKey = Stripe::setApiKey(env('secret_key'));

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



/*********************** Mailing System (Admin panel) ********************/

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
    public function login_check(Request $request){
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
    public function register_check(Request $request){
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



            return IndexController::payment_integration($data, $page);            
            
            }
        else {
            return redirect()->back()->withInput()->with('message', $authenticateResult);
        }

    }

    public function register_authenticate($email){
        $haveUser = DB::table('users')->WHERE('email', $email)->first();
        if ($haveUser) {
            return $messsage = 'You have already submitted register form Please check your Email and click the confirmation link before <a href="/signin">Login </a>';           
        }
        else {
            return true;
        }
    
    }


/*************************** Forgot/Update Password *********************************/

    public function forgot_password(){
        $title = "Forgot Password";
        return view('frontend/forgot_password', compact('title'));
    }

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

            DB::table('users')->where('id', Auth::user()->id)->update(['membership_id' => 0, 'subscription_enddate' => '']);

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

          // Setting up sessions

        Session::set('membershipID', $membership_id);
        Session::set('page', $page);
        Session::set('paymentMethod', $option);
        Session::set('cost', $membership_cost);
        Session::set('subscriptionPeriod', $subscription_period);

        // if cost greater then 0 or not free
        if($membership_cost > 0 && $membership_type != 'Free' ) {
            if($option == 'stripe'){
                
                try{
                    Charge::create(array(
                        'amount' => $membership_cost*100,
                        'currency' => 'USD',
                        'source' => $request->stripe_token,
                        'description' => 'Membership upgraded to '.$membership_name
                    ));   

                    $endDate = $this->setEndingDate($subscription_period);
                   
                    Session::set('endingDate', $endDate);                                                

                    return redirect('/update_membership/true');
                    

                } catch(\Exception $e){                    

                    \Session::flash('message',$e->getMessage());
                        
                     return redirect('/profile');
                }                   
               
            }
            else{               

                $payer = new Payer();
                $payer->setPaymentMethod("paypal");

                $item = new Item();
                $item->setName($membership_name)
                    ->setCurrency('USD')
                    ->setQuantity(1)
                    ->setSku($membership_id) // Similar to `item_number` in Classic API
                    ->setPrice($membership_cost); 

                $itemList = new ItemList();
                $itemList->setItems(array($item));

                $details = new Details();
                $details->setShipping(0)
                    ->setTax(0)
                    ->setSubtotal($membership_cost);

                $amount = new Amount();
                $amount->setCurrency("USD")
                    ->setTotal($membership_cost)
                    ->setDetails($details);                    

                $transaction = new Transaction();
                $transaction->setAmount($amount)
                    ->setItemList($itemList)
                    ->setDescription("Payment description")
                    ->setInvoiceNumber(uniqid());   

                $redirectUrls = new RedirectUrls();
                $redirectUrls->setReturnUrl(url("/update_membership/true"))
                    ->setCancelUrl(url("/update_membership/false"));  

                $payment = new Payment();
                $payment->setIntent("sale")
                    ->setPayer($payer)
                    ->setRedirectUrls($redirectUrls)
                    ->setTransactions(array($transaction));            

                $payment->create($this->apiContext);

                $approvalUrl = $payment->getApprovalLink();                   

                return redirect($approvalUrl);
            }
        }else{   
            Session::set('endingDate', '');      
            return redirect('update_membership/true');
        }
    }

    /*********** Update Membership After successful payment *********/

    public function update_membership($status){

        $membership_id = Session::get('membershipID');
        $page = Session::get('page');          
        $payment_method = Session::get('paymentMethod');                   
        $cost = Session::get('cost');                   
        $subscriptionPeriod = Session::get('subscriptionPeriod');                   
        if ($status == 'true') {   

            $page_id = explode(":", $page);

           if ($payment_method == 'paypal' && $cost > 0) {
                          
                $paymentId = $_GET['paymentId'];
                $payment = Payment::get($paymentId, $this->apiContext);

                $execution = new PaymentExecution();
                $execution->setPayerId($_GET['PayerID']);

                $result = $payment->execute($execution, $this->apiContext);


                $endDate = $this->setEndingDate($subscriptionPeriod);
                   
                Session::set('endingDate', $endDate);    
           }
                    
            
            $endingDate = Session::get('endingDate');    
            // subscribing from registration page
            if ($page_id[0] == 'registration') {                           

                // Update Membership, Subscription EndingDATE
                $update_data = ['membership_id' => $membership_id, 'subscription_enddate' => $endingDate];
                
                DB::table('users')->where('id', $page_id[1])->update($update_data);

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

                $this->unsetSession();

                return redirect('/signin')->with('message', 'Registerd Successfully Kindly Login');
            }
            
            else
                // upgrading from profile page
            {
                  // Update Membership, Subscription EndingDATE
                $update_data = ['membership_id' => $membership_id, 'subscription_enddate' => $endingDate];
                
                DB::table('users')->where('id', Auth::user()->id)->update($update_data);

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
           
                $this->unsetSession();

                return redirect("/profile");
            }
        }
        else{

            $page_id = explode(":", $page);

            if ($page_id[0] == 'registration') {
               
                \Session::flash('message','Registration Succesfull <br> Payment Cancelled. Login to complete payment');

                return redirect('/signin');

            }else{
                
                \Session::flash('message','Payment Cancelled Membership Cant be updatd');

                return redirect('/profile');
                
            }

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


    public function setEndingDate($subscription_period){
        if ($subscription_period == 'Lifetime') {
            $endDate = '';                        
        }else{

            $currentDate = date('Y-m-d');
            $date = new DateTime($currentDate);
            $date->add(new DateInterval('P10D'));
            $current = $date->format('Y-m-d');
            
            $endDate = date('Y-m-d', strtotime($current. ' + '.$subscription_period));

        }
        return $endDate;
    }

    public function unsetSession(){
        Session::forget('membershipID');
        Session::forget('page');
        Session::forget('paymentMethod');
        Session::forget('cost');
        Session::forget('subscriptionPeriod');
        Session::forget('end');
        Session::forget('endingDate');

    }

    public function cronJob(Request $request){
        if($request->method() == 'POST'){

            if($request->Auth == 'true'){
                
                $currentDate = date('Y-m-d'); 
                
                $fetch_date = DB::table('users')->whereNull('deleted_at')->get();
                
                foreach ($fetch_date as $data) {
                    if($data->subscription_enddate == $currentDate){
                        // update membership to 0(none)
                        DB::table('users')->where('id', $data->id)->update(['membership_id' => 0, 'subscription_enddate' => '']);

                        $fetch_content = DB::table('email_templates')->where('options','Membership Period Completed')->first();

                        $upgrade_content = $fetch_content->email_content;
                        $upgrade_subject = $fetch_content->subject;


                        $name_updated = str_replace('[username]', $data->name, $upgrade_content);
                        
                        $data = array( 'email' => $data->email, 'subject' => $upgrade_subject, 'message' => $name_updated);         

                        Mail::send([], $data, function ($m) use($data) {                    
                             $m->to($data['email'])->subject($data['subject'])->setBody($data['message'], 'text/html');
                         });
                    }
                }


            }else{
                echo "not auth";
            }
        }else{
            echo "not post";
        }
    }

        /************ Subscription work Start (Paypal/Stripe)*************/


    // Subscription Stripe/Payal
    // public function payment_page(Request $request){

    //     $unsubscribe = $request->unsubscribe;

    //     if($unsubscribe == 'true'){
    //         $stripe = new \Stripe\Stripe();

    //         $this->stripeKey;
    //         // $stripe->setApiKey("sk_test_doaAddzso5GZH5xoQ4YwDbQO");

    //         $sub_id = Auth::user()->stripe_subscription_id;
    //         // check if subscription is stripe
    //         if(!empty($sub_id)){

    //             $sub = \Stripe\Subscription::retrieve($sub_id);
                
    //             $sub->cancel();

    //         }
    //         // if subscription is paypal
    //         else{

    //         }

    //         DB::table('users')->where('id', Auth::user()->id)->update(['membership_id' => 0, 'stripe_subscription_id' => '']);

    //         \Session::flash('message','Membership unsubscribed Successfully');            

    //         return redirect('/profile');


    //     }else{
            
    //         $name = $request->user_name;
    //         $membership_name = $request->membership_name;
    //         $membership_cost = $request->membership_cost;
    //         $membership_id = $request->membership_id;
    //         $membership_type = $request->membership_type;
    //         $subscription_period = $request->subscription_period;
    //         if ($membership_type != 'Free' && $subscription_period != 'Lifetime') {

    //             $planId = $request->planId;
    //             $stripeplanId = $request->stripeplanId;
                
    //             $form_data = [$name, $membership_name, $membership_cost,$membership_id, $membership_type, $subscription_period , $planId, $stripeplanId];            
    //         }else{

    //             $form_data = [$name, $membership_name, $membership_cost,$membership_id, $membership_type, $subscription_period ];            
    //         }

    //         $title = 'Proceed to checkout';
            
    //         return view('frontend/payment_page', compact('form_data', 'title'));
    //     }
    // }

    // public function payment_integration(Request $request, $page = false){
       
    //     $option = $request->payment_method_;
    //     $membership_cost = $request->membership_cost;
    //     $membership_name = $request->membership_name;
    //     $membership_id = $request->membership_id;
    //     $user_name = $request->user_name;        
    //     $membership_type = $request->membership_type;        
    //     $subscription_period = $request->subscription_period;        
    //     $email = $request->email;

    //     Session::set('membershipID', $membership_id);
    //     Session::set('page', $page);
    //     Session::set('paymentMethod', $option);
    //     // for delete/cancel the previous subscription
    //     Session::set('old_stripesubscriptioID', Auth::user()->stripe_subscription_id);
    //     Session::set('old_paypalsubscriptioID', Auth::user()->paypal_subscription_id);

        
    //     // if cost greater then 0 or not free
    //     if($membership_cost > 0 && $membership_type != 'Free' ) {

    //         // Stripe secret Key
    //         $this->stripeKey;            

    //         if ($subscription_period != 'Lifetime') {

    //             Session::set('subscription', '');

    //             // for subscription plan
    //             if($option == 'stripe'){

    //                 $stripe_subscription = new \Stripe\Subscription();

    //                 // Create customer id using stripe token
    //                 $stripeCustomer = new \Stripe\Customer();

    //                 $createdCustomer = $stripeCustomer->create([
    //                   "description" => "Customer for ". $email,
    //                   "source" => $request->stripe_token, // obtained with Stripe.js
    //                   "email" => $email
    //                 ]);

    //                 // retrived from db
    //                 $stripeplanId = $request->stripeplanId;                                        
                    
    //                 // Create a new subscription
    //                 try{

    //                     $createdSubscription = $stripe_subscription->create([
    //                         "customer" => $createdCustomer->id,
    //                           "items" => [
    //                             [
    //                               "plan" => $stripeplanId,
    //                             ],
    //                           ]
    //                     ]); 

    //                     $stripeEndingPeriod = $createdSubscription->current_period_end);
                        
    //                     $stripeEndingPeriod = date('Y-m-d',$stripeEndingPeriod);

    //                     Session::set('new_stripe_sub_ID', $createdSubscription->id);
    //                     Session::set('stripe_ending_period', $stripeEndingPeriod);

    //                     return redirect('/update_membership/true');

    //                 }catch (\Ecxeption $e){
                        
    //                     \Session::flash('message',$e->getMessage());
                        
    //                      return redirect('/profile');

    //                 }

    //             }                
    //             else{ 

    //                 Session::set('new_stripe_sub_ID', '');
    //                 Session::set('stripe_ending_period', '');

    //                 // setting the agreement start time after two minutes
    //                 $st = date("Y-m-d\TH:i:s\Z",strtotime('+2 minute'));

    //                 // plan id
    //                 $planId = $request->planId;
                    
    //                 // create agreement between marchant and buyer
    //                 $agreement = new Agreement();

    //                 $agreement->setName('Agreement For subscribing '.$membership_name)
    //                     ->setDescription('Agreement is placed at cost '.$membership_cost.'/- for every '.$subscription_period)                                      
    //                     ->setStartDate($st);

    //                 // getting plan for which agreement is created

    //                 $plan = new Plan();
                    
    //                 $plan->setId($planId);

    //                 $agreement->setPlan($plan);

    //                 // setting up payer

    //                 $payer = new Payer();
                    
    //                 $payer->setPaymentMethod('paypal');
                    
    //                 $agreement->setPayer($payer);                    
                    
    //                 try{


    //                     $agreement = $agreement->create($this->apiContext);
                        
    //                     $approvalUrl = $agreement->getApprovalLink();

    //                     return redirect($approvalUrl);

    //                 }catch( Exception $e){
    //                     echo $e->getData();
    //                 }
    //             }               
    //         }
    //         else{

    //             Session::set('subscription', 'Lifetime');

    //             // for one time payment
    //             if($option == 'stripe'){
                    
    //                 try{
    //                     Charge::create(array(
    //                         'amount' => $membership_cost*100,
    //                         'currency' => 'USD',
    //                         'source' => $request->stripe_token,
    //                         'description' => 'Membership upgraded to '.$membership_name
    //                     ));                         
                        
    //                     // Session::set('new_stripe_sub_ID', '');
    //                     // Session::set('stripe_ending_period', '');
                        

    //                     return redirect('/update_membership/true');
                        

    //                 } catch(\Exception $e){

    //                 \Session::flash('message',$e->getMessage());
    //                 echo $e->getMessage();
    //                  return redirect('/profile');
    //                 }                   
                   
    //             }
    //             else{

    //                 // Session::set('new_stripe_sub_ID', '');
    //                 // Session::set('stripe_ending_period', '');

    //                 $payer = new Payer();
    //                 $payer->setPaymentMethod("paypal");

    //                 $item = new Item();
    //                 $item->setName($membership_name)
    //                     ->setCurrency('USD')
    //                     ->setQuantity(1)
    //                     ->setSku($membership_id) // Similar to `item_number` in Classic API
    //                     ->setPrice($membership_cost); 

    //                 $itemList = new ItemList();
    //                 $itemList->setItems(array($item));

    //                 $details = new Details();
    //                 $details->setShipping(0)
    //                     ->setTax(0)
    //                     ->setSubtotal($membership_cost);

    //                 $amount = new Amount();
    //                 $amount->setCurrency("USD")
    //                     ->setTotal($membership_cost)
    //                     ->setDetails($details);                    

    //                 $transaction = new Transaction();
    //                 $transaction->setAmount($amount)
    //                     ->setItemList($itemList)
    //                     ->setDescription("Payment description")
    //                     ->setInvoiceNumber(uniqid());   

    //                 $redirectUrls = new RedirectUrls();
    //                 $redirectUrls->setReturnUrl(url("/update_membership/true"))
    //                     ->setCancelUrl(url("/update_membership/false"));  

    //                 $payment = new Payment();
    //                 $payment->setIntent("sale")
    //                     ->setPayer($payer)
    //                     ->setRedirectUrls($redirectUrls)
    //                     ->setTransactions(array($transaction));            

    //                 $payment->create($this->apiContext);

    //                 $approvalUrl = $payment->getApprovalLink();                   
                    
    //                 Session::set('paymentMethod', '');

    //                 return redirect($approvalUrl);


    //             }

    //             Session::set('new_stripe_sub_ID', '');
    //             Session::set('stripe_ending_period', '');
    //         } 

                         
    //     }   
    //     else{
    //         return redirect('/update_membership/true');
    //         // old one time payment
    //         // return redirect('/update_membership/'.$membership_id.'/'.$page);
    //     }
    // }
   
    // public function update_membership($status){

    //     if ($status == 'true') {            

    //         $membership_id = Session::get('membershipID');
    //         $page = Session::get('page');          
    //         $payment_method = Session::get('paymentMethod');
    //         $subscriptionid = Session::get('new_stripe_sub_ID');
    //         $stripeSubscriptionID = Session::get('old_stripesubscriptioID');
    //         $paypalSubscriptionID = Session::get('old_paypalsubscriptioID');
    //         $subscription = Session::get('subscription');

    //         $stripe_subscription = new \Stripe\Subscription();

    //         // First delete the previous susbcription
    //         if(!empty($stripeSubscriptionID)){               
                
    //             $sub = $stripe_subscription->retrieve($stripeSubscriptionID);
              
    //             $sub->cancel();                                            
                
    //         }

    //         if(!empty($paypalSubscriptionID)){
    //             echo "delete paypal agreement id";
    //         }


    //         $subscriptionID = ['stripe_subscription_id' => $subscriptionid];

    //         $page_id = explode(":", $page);

    //         if ($subscription == 'Lifetime') {
                
    //             $paymentId = $_GET['paymentId'];
    //             $payment = Payment::get($paymentId, $this->apiContext);

    //             $execution = new PaymentExecution();
    //             $execution->setPayerId($_GET['PayerID']);

    //             $result = $payment->execute($execution, $this->apiContext);
              
    //         }

    //         if ($payment_method == 'paypal') {
               
    //             $token = $_GET['token'];

    //             $agreement = new \PayPal\Api\Agreement();

    //             $agreement->execute($token, $this->apiContext);

    //             dump($agreement);
                
    //             exit();
    //         } 



            
    //         // subscribing from registration page
    //         if ($page_id[0] == 'registration') {            

    //             // Update Subscription id
    //             DB::table('users')->where('id', $page_id[1])->update($subscriptionID);

    //             // Update Membership
    //             $update_membership = ['membership_id' => $membership_id];
                
    //             DB::table('users')->where('id', $page_id[1])->update($update_membership);

    //             // Get user name

    //             $fetch_uname = DB::table('users')->where('id', $page_id[1])->first();
    //             $name = $fetch_uname->name;
    //             $email = $fetch_uname->email;

    //             // Get email content from database
    //             $get_email_content = DB::table('email_templates')->Where('options', 'Registeration')->whereNull('deleted_at')->first();
    //             $content = $get_email_content->email_content;
    //             $subject = $get_email_content->subject;

    //             // Get membership name
    //             $get_membership_name = DB::table('memberships')->Where('id', $membership_id)->whereNull('deleted_at')->first();
    //             $membership_name = $get_membership_name->membership_name;
                


    //             $name_updated = str_replace('[username]', $name, $content);
    //             $registration_content = str_replace('[membership]', $membership_name, $name_updated);




    //             $data = array( 'email' => $email, 'subject' => $subject, 'message' => $registration_content);

    //             // Send email

    //             Mail::send([], $data, function ($m) use($data) {                    
    //                 $m->to($data['email'])->subject($data['subject'])->setBody($data['message'], 'text/html');
    //             });

    //             return redirect('/signin')->with('message', 'Registerd Successfully Kindly Login');
    //         }
    //         // upgrading from profile page
    //         else{
    //              // Update Subscription id
    //             DB::table('users')->where('id', Auth::user()->id)->update($subscriptionID);

    //             $update_membership = ['membership_id' => $membership_id];
                
    //             DB::table('users')->where('id', Auth::user()->id)->update($update_membership);

    //             $fetch_name = DB::table('memberships')->where('id', $membership_id)->first();

    //             $membership_name = $fetch_name->membership_name;

    //             // get upgrade membership email content

    //             $fetch_content = DB::table('email_templates')->where('options','Upgrade')->first();

    //             $upgrade_content = $fetch_content->email_content;
    //             $upgrade_subject = $fetch_content->subject;


    //             $name_updated = str_replace('[username]', Auth::user()->name, $upgrade_content);
    //             $membership_updated = str_replace('[membership]', $membership_name, $name_updated); 
                

    //             // Fetch admin email

    //             $fetch_admin_email = DB::table('users')->where('id',1)->first();

    //             $admin_email = $fetch_admin_email->email;

    //              $data = array( 'email' => [Auth::user()->email, $admin_email], 'subject' => $upgrade_subject, 'message' => $membership_updated);         

    //             Mail::send([], $data, function ($m) use($data) {                    
    //                  $m->to($data['email'])->subject($data['subject'])->setBody($data['message'], 'text/html');
    //              });

    //             \Session::flash('message','Account Upgraded');
                
    //             return redirect("/profile");
    //         }
            
    //         Session::forget('membershipID');
    //         Session::forget('page');

    //     }else{
    //         echo "false";
    //     }
    // }


        /************ Subscription work end (Paypal/Stripe)*************/






   

   

}


