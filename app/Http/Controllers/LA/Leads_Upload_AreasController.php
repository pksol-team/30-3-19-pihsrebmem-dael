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

use App\Models\Leads_Upload_Area;

class Leads_Upload_AreasController extends Controller
{
	public $show_action = true;
	public $view_col = 'membership_id';
	public $listing_cols = ['id', 'membership_id', 'file_name', 'file'];
	
	public function __construct() {
		// Field Access of Listing Columns
		if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
			$this->middleware(function ($request, $next) {
				$this->listing_cols = ModuleFields::listingColumnAccessScan('Leads_Upload_Areas', $this->listing_cols);
				return $next($request);
			});
		} else {
			$this->listing_cols = ModuleFields::listingColumnAccessScan('Leads_Upload_Areas', $this->listing_cols);
		}
	}
	
	/**
	 * Display a listing of the Leads_Upload_Areas.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module = Module::get('Leads_Upload_Areas');
		
		if(Module::hasAccess($module->id)) {
			return View('la.leads_upload_areas.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $this->listing_cols,
				'module' => $module
			]);
		} else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}

	/**
	 * Show the form for creating a new leads_upload_area.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created leads_upload_area in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(Module::hasAccess("Leads_Upload_Areas", "create")) {
		
			$rules = Module::validateRules("Leads_Upload_Areas", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			
			$insert_id = Module::insert("Leads_Upload_Areas", $request);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.leads_upload_areas.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Display the specified leads_upload_area.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if(Module::hasAccess("Leads_Upload_Areas", "view")) {
			
			$leads_upload_area = Leads_Upload_Area::find($id);
			if(isset($leads_upload_area->id)) {
				$module = Module::get('Leads_Upload_Areas');
				$module->row = $leads_upload_area;
				
				return view('la.leads_upload_areas.show', [
					'module' => $module,
					'view_col' => $this->view_col,
					'no_header' => true,
					'no_padding' => "no-padding"
				])->with('leads_upload_area', $leads_upload_area);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("leads_upload_area"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Show the form for editing the specified leads_upload_area.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		if(Module::hasAccess("Leads_Upload_Areas", "edit")) {			
			$leads_upload_area = Leads_Upload_Area::find($id);
			if(isset($leads_upload_area->id)) {	
				$module = Module::get('Leads_Upload_Areas');
				
				$module->row = $leads_upload_area;
				
				return view('la.leads_upload_areas.edit', [
					'module' => $module,
					'view_col' => $this->view_col,
				])->with('leads_upload_area', $leads_upload_area);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("leads_upload_area"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Update the specified leads_upload_area in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if(Module::hasAccess("Leads_Upload_Areas", "edit")) {
			
			$rules = Module::validateRules("Leads_Upload_Areas", $request, true);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();;
			}
			
			$insert_id = Module::updateRow("Leads_Upload_Areas", $request, $id);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.leads_upload_areas.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Remove the specified leads_upload_area from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if(Module::hasAccess("Leads_Upload_Areas", "delete")) {
			Leads_Upload_Area::find($id)->delete();
			
			// Redirecting to index() method
			return redirect()->route(config('laraadmin.adminRoute') . '.leads_upload_areas.index');
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
		$values = DB::table('leads_upload_areas')->select($this->listing_cols)->whereNull('deleted_at');
		$out = Datatables::of($values)->make();
		$data = $out->getData();

		$fields_popup = ModuleFields::getModuleFields('Leads_Upload_Areas');
		
		for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($this->listing_cols); $j++) { 
				$col = $this->listing_cols[$j];
				if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}
				if($col == $this->view_col) {
					$data->data[$i][$j] = '<a href="'.url(config('laraadmin.adminRoute') . '/leads_upload_areas/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
				}
				// else if($col == "author") {
				//    $data->data[$i][$j];
				// }
			}
			
			if($this->show_action) {
				$output = '';
				if(Module::hasAccess("Leads_Upload_Areas", "edit")) {
					$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/leads_upload_areas/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				
				if(Module::hasAccess("Leads_Upload_Areas", "delete")) {
					$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.leads_upload_areas.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline']);
					$output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
					$output .= Form::close();
				}
				$data->data[$i][] = (string)$output;
			}
		}
		$out->setData($data);
		return $out;
	}
}
