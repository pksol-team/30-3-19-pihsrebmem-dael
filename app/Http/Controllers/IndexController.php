<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Offer;



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
        // $this->middleware('auth');
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

            $offers = Offer::with('memberships')->whereNull('deleted_at')->get();

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
    echo "working";
}



/*********************** Email Template********************/

public function email_template(){

    // $membership = DB::table('memberships')->whereNull('deleted_at')->get();
echo "create page";
    // return view('frontend/email_system' , compact('membership'));
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
    
    
}