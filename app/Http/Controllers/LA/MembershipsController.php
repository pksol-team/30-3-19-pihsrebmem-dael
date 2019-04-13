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
		if ($request->subscription_period != 'Lifetime') {
			
			if($request->cost > 0 && $request->type == 'Paid'){

				$frequency_interval = explode(' ', $request->subscription_period);

				$frequency = $frequency_interval[1];
				$frequencyInterval = $frequency_interval[0];			

				$plan = new Plan();

				$plan->setName($request->membership_name)
				    ->setDescription("Membership Name: ".$request->membership_name."<br>Type: ".$request->type."<br>Cost: ".$request->cost."<br>Subscription Period: ".$request->subscription_period)
				    ->setType('fixed');  

				$paymentDefinition = new PaymentDefinition();

				$paymentDefinition->setName('Regular Payments')
				    ->setType('REGULAR')
				    ->setFrequency($frequency)
				    ->setFrequencyInterval($frequencyInterval)
				    ->setCycles("12")
				    ->setAmount(new Currency(array('value' => $request->cost, 'currency' => 'USD')));

				$chargeModel = new ChargeModel();
				
				$chargeModel->setType('SHIPPING')
				    ->setAmount(new Currency(array('value' => 0, 'currency' => 'USD')));

				$paymentDefinition->setChargeModels(array($chargeModel));



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

				// update status

				$patch = new Patch();

				$plan = new Plan();

				$createdPlan = $plan->get($plan_id, $this->apiContext);

			    $value = new PayPalModel('{
				       "state":"ACTIVE"
				     }');

			    $patch->setOp('replace')
			        ->setPath('/')
			        ->setValue($value);
			    $patchRequest = new PatchRequest();
			    
			    $patchRequest->addPatch($patch);

			    $createdPlan->update($patchRequest, $this->apiContext);

				
				
			}else{
				$plan_id = '';
			}

		}
		else{
				$plan_id = '';
			}




		if(Module::hasAccess("Memberships", "create")) {
		
			$rules = Module::validateRules("Memberships", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			
			$insert_id = Module::insert("Memberships", $request);
			
			$update_planId = ['plan_id' => $plan_id];
			
			DB::table('memberships')->where('id', $insert_id)->update($update_planId);

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

        $fetch_membership = DB::table('memberships')->where('id', $id)->first();

        $planId = $fetch_membership->plan_id;

        $type = $request->type;
        
        $cost = $request->cost;
        
        $subscription_period = $request->subscription_period;        
        
        
        // updating plan id if plan is not lifetime
        
        if ($subscription_period != 'Lifetime') {
        
	        if (!empty($planId) && $type == 'Paid' && $cost > 0) {

	      //   	$params = array('page_size' => '20');
    			// $planList = Plan::all($params, $this->apiContext);
    			// dump($planList);
    			// exit();
	        	
				$frequency_interval = explode(' ', $subscription_period);

				$frequency = $frequency_interval[1];
				
				$frequencyInterval = $frequency_interval[0];

				$plan = new Plan();

				$createdPlan = $plan->get($planId, $this->apiContext);

				dump($createdPlan);
				exit();

				$patch = new Patch();

			    $paymentDefinitions = $createdPlan->getPaymentDefinitions();
			    
			    $paymentDefinitionId = $paymentDefinitions[0]->getId();		 

  // "name": "Updated Payment Definition",
	 //                           "frequency": "'.$frequency.'",
	 //                           "amount": {
	 //                               "currency": "USD",
	 //                               "value": "'.$cost.'"
	 //                           },
	 //                           "frequency_interval": "'.$frequencyInterval.'"
				$patch->setOp('replace')
			       ->setPath('/payment-definitions/' . $paymentDefinitionId)
			       ->setValue(json_decode(
			           '{

			           		"name": "Updated Payment Definition",
	   		                  "frequency": "Day",
	   		                  "amount": {
	   		                      "currency": "USD",
	   		                      "value": "50"
	   		                  }
	                         
	                   }'
			       ));
			    $patchRequest = new PatchRequest();
			   
			    $patchRequest->addPatch($patch);			   
			    // echo "patch request";
			    // dump($patchRequest);
			    $createdPlan->update($patchRequest, $this->apiContext);
				
				echo "updated plan";

			    dump($createdPlan);
			    exit();	
			    $plan = $plan->get($planId, $this->apiContext);
				

	        }        	
        }

		if(Module::hasAccess("Memberships", "edit")) {
			
			$rules = Module::validateRules("Memberships", $request, true);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();;
			}
			
			$insert_id = Module::updateRow("Memberships", $request, $id);

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
}
