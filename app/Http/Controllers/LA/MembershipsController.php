<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */



namespace App\Http\Controllers\LA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use DB;
use Validator;
use Datatables;
use Collective\Html\FormFacade as Form;
use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Models\ModuleFields;
use App\Models\Membership;



// Create Subscription Plan

use PayPal\Api\Plan;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Currency;
use PayPal\Api\ChargeModel;

// Update Subscription Plan
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;

// Update status
use PayPal\Common\PayPalModel;




class MembershipsController extends Controller
{
	public $apiContext;
	public $show_action = true;
	public $view_col = 'membership_name';
	public $listing_cols = ['id', 'membership_name', 'type', 'cost', 'membership_level', 'subscription_period'];
	
	public function __construct() {
		// Field Access of Listing Columns
		if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
			$this->middleware(function ($request, $next) {
				$this->listing_cols = ModuleFields::listingColumnAccessScan('Memberships', $this->listing_cols);
				return $next($request);
			});
		} else {
			$this->listing_cols = ModuleFields::listingColumnAccessScan('Memberships', $this->listing_cols);
		}

		$this->apiContext = new \PayPal\Rest\ApiContext(
		        new \PayPal\Auth\OAuthTokenCredential(
		        	env('PAYPAL_CLIENT_ID'),// ClientID
		        	env('PAYPAL_SECRET')// ClientSecret
		        )
		);

		

	}
	
	/**
	 * Display a listing of the Memberships.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module = Module::get('Memberships');
		
		if(Module::hasAccess($module->id)) {
			return View('la.memberships.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $this->listing_cols,
				'module' => $module
			]);
		} else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}

	/**
	 * Show the form for creating a new membership.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created membership in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{	

		// Subscription Plan

		// $random_number = $this->randomNumber();

		// if ($request->subscription_period != 'Lifetime') {
			
		// 	if($request->cost > 0 && $request->type == 'Paid'){

		// 		$frequency_interval = explode(' ', $request->subscription_period);

		// 		$frequency = $frequency_interval[1];
				
		// 		$frequencyInterval = $frequency_interval[0];			

		// 		// Create Stripe Plan 				
		// 		$this->stripePlanCreated($random_number, $frequency, $frequencyInterval, $request->cost, $request->membership_name);

		// 		$stripePlanID = $random_number;


		// 		// Create Paypal Plan 
		// 		$paypalPlanId = $this->createdPaypalPlan($request->membership_name, $request->cost, $request->subscription_period, $frequency, $frequencyInterval, $request->type);

		// 			$plan_id = $paypalPlanId;	
				
		// 	}else{
		// 		$plan_id = '';
		// 		$stripePlanID = '';
		// 	}

		// }
		// else{
		// 		$plan_id = '';
		// 		$stripePlanID = '';				
		// }



		if(Module::hasAccess("Memberships", "create")) {
		
			$rules = Module::validateRules("Memberships", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			
			$insert_id = Module::insert("Memberships", $request);
			
			// $update_planId = ['plan_id' => $plan_id, 'stripe_plan_id' => $stripePlanID];
			
			// DB::table('memberships')->where('id', $insert_id)->update($update_planId);

			return redirect()->route(config('laraadmin.adminRoute') . '.memberships.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Display the specified membership.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if(Module::hasAccess("Memberships", "view")) {
			
			$membership = Membership::find($id);
			if(isset($membership->id)) {
				$module = Module::get('Memberships');
				$module->row = $membership;
				
				return view('la.memberships.show', [
					'module' => $module,
					'view_col' => $this->view_col,
					'no_header' => true,
					'no_padding' => "no-padding"
				])->with('membership', $membership);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("membership"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Show the form for editing the specified membership.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		if(Module::hasAccess("Memberships", "edit")) {			
			$membership = Membership::find($id);
			if(isset($membership->id)) {	
				$module = Module::get('Memberships');
				
				$module->row = $membership;
				
				return view('la.memberships.edit', [
					'module' => $module,
					'view_col' => $this->view_col,
				])->with('membership', $membership);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("membership"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Update the specified membership in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{		
        // $type = $request->type;
        
        // $cost = $request->cost;
        
        // $subscription_period = $request->subscription_period;        

        // $fetch_membership = DB::table('memberships')->where('id', $id)->first();

        // // Paypal Plan id
        // $planId = $fetch_membership->plan_id;
        
        // // Stripe Plan id
        // $stripeplanId = $fetch_membership->stripe_plan_id;        
        
        
        // updating plan id if plan is not lifetime
        
    //     if ($subscription_period != 'Lifetime') {
        
	   //      if ($type == 'Paid' && $cost > 0) {	     
	        	
				// $frequency_interval = explode(' ', $subscription_period);

				// $frequency = $frequency_interval[1];
				
				// $frequencyInterval = $frequency_interval[0];

				// // Stripe subscription updation				

				// if (!empty($stripeplanId)) {
				// 	// first delete the plan
					
				// 	$this->deleteStripePlan($stripeplanId);					

				// 	$this->stripePlanCreated($stripeplanId, $frequency, $frequencyInterval, $cost, $request->membership_name);
					
				// }else{
					
				// 	$random_number = $this->randomNumber();

				// 	$this->stripePlanCreated($random_number, $frequency, $frequencyInterval, $cost, $request->membership_name);

				// 	$stripeplanId = $random_number;
					
				// }			

				// // Paypal Subscription Updation

				// if (!empty($planId)) {
					
				// 	// $this->deletePaypalPlan($planId);
					
				// }
				

	   //      }        	
    //     }else{
    // 		if (!empty($stripeplanId)) {
    // 			$this->deleteStripePlan($stripeplanId);
    // 			$stripeplanId = '';
    // 		}

    // 		if (!empty($planId)) {
    // 			echo $planId;
    // 			$planId = '';
    // 		}  
        	
    //     }

     
        
		if(Module::hasAccess("Memberships", "edit")) {
			
			$rules = Module::validateRules("Memberships", $request, true);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();;
			}
			
			$insert_id = Module::updateRow("Memberships", $request, $id);

			// $update_planId = ['plan_id' => $planId, 'stripe_plan_id' => $stripeplanId];
			
			// DB::table('memberships')->where('id', $insert_id)->update($update_planId);

			return redirect()->route(config('laraadmin.adminRoute') . '.memberships.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Remove the specified membership from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if(Module::hasAccess("Memberships", "delete")) {
			Membership::find($id)->delete();
			
			// Redirecting to index() method
			return redirect()->route(config('laraadmin.adminRoute') . '.memberships.index');
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}
	
	/**
	 * Datatable Ajax fetch
	 *
	 * @return
	 */
	public function dtajax()
	{
		$values = DB::table('memberships')->select($this->listing_cols)->whereNull('deleted_at');
		$out = Datatables::of($values)->make();
		$data = $out->getData();

		$fields_popup = ModuleFields::getModuleFields('Memberships');
		
		for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($this->listing_cols); $j++) { 
				$col = $this->listing_cols[$j];
				if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}
				if($col == $this->view_col) {
					$data->data[$i][$j] = '<a href="'.url(config('laraadmin.adminRoute') . '/memberships/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
				}
				// else if($col == "author") {
				//    $data->data[$i][$j];
				// }
			}
			
			if($this->show_action) {
				$output = '';
				if(Module::hasAccess("Memberships", "edit")) {
					$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/memberships/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				
				// if(Module::hasAccess("Memberships", "delete")) {
				// 	$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.memberships.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline']);
				// 	$output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
				// 	$output .= Form::close();
				// }
				$data->data[$i][] = (string)$output;
			}
		}
		$out->setData($data);
		return $out;
	}

	public function randomNumber(){

		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		   
		$charactersLength = strlen($characters);
		
		$randomString = '';
		
		for ($i = 0; $i < 10; $i++) {
		
		    $randomString .= $characters[rand(0, $charactersLength - 1)];
		
		}	

		return $randomString;	
	}

	public function stripePlanCreated($random_number, $frequency, $frequencyInterval, $cost, $membership_name){
		// Stripe Subscription Plan

		$stripe = new \Stripe\Stripe();

		$stripe_plan = new \Stripe\Plan();

		$stripe->setApiKey("sk_test_doaAddzso5GZH5xoQ4YwDbQO");

		$stripe_plan->create([
		  "amount" => $cost * 100,
		  "interval" => strtolower($frequency),
		  "interval_count" => $frequencyInterval,
		  "product" => [
		    "name" => $membership_name
		  ],
		  "currency" => "usd",
		  "id" => $random_number
		]);

		
	}

	public function deleteStripePlan($id){
		$stripe = new \Stripe\Stripe();

		$stripe_plan = new \Stripe\Plan();

		$stripe->setApiKey("sk_test_doaAddzso5GZH5xoQ4YwDbQO");

		$plan = $stripe_plan->retrieve($id);
		
		$plan->delete();
	}

	public function createdPaypalPlan($membership_name, $cost, $subscription_period, $frequency, $frequencyInterval, $type){

		$plan = new Plan();

		
		$patch = new Patch();

	    $patchRequest = new PatchRequest();	

		$plan->setName($membership_name)
		    ->setDescription("Membership Name: ".$membership_name."<br>Type: ".$type."<br>Cost: ".$cost."<br>Subscription Period: ".$subscription_period)
		    ->setType('infinite');  

		$paymentDefinition = new PaymentDefinition();

		$paymentDefinition->setName('Infinite Payments')
		    ->setType('REGULAR')
		    ->setFrequency($frequency)
		    ->setFrequencyInterval($frequencyInterval)
		    ->setCycles("0")
		    ->setAmount(new Currency(array('value' => $cost, 'currency' => 'USD')));

		$merchantPreferences = new MerchantPreferences();

		$merchantPreferences->setReturnUrl(url("/update_membership/true"))
		    ->setCancelUrl(url("/update_membership/false"))
		    ->setAutoBillAmount("yes")
		    ->setInitialFailAmountAction("CONTINUE")
		    ->setMaxFailAttempts("0")
		    ->setSetupFee(new Currency(array('value' => 0, 'currency' => 'USD')));
		
		$plan->setPaymentDefinitions(array($paymentDefinition));
		
		$plan->setMerchantPreferences($merchantPreferences);
		
		$output = $plan->create($this->apiContext);		

		$plan_id = $output->id;

		$createdPlan = $plan->get($plan_id, $this->apiContext);
		
		$value = new PayPalModel('{		    		
		       "state":"ACTIVE"		      
		     }');			

	    $patch->setOp('replace')
	        ->setPath('/')
	        ->setValue($value);
	    
	    $patchRequest->addPatch($patch);

	    $createdPlan->update($patchRequest, $this->apiContext);

		$updatedPlan = $plan->get($plan_id, $this->apiContext);

		return $plan_id;
	}

	public function deletePaypalPlan($id){

		$result = $createdPlan->delete($this->apiContext);
	}
}
