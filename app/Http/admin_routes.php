<?php


/* ================== Frontend ================== */
// Login
Route::get('/signin', 'IndexController@user_login_page');
Route::post('/frontend/login_check', 'IndexController@login_check');
// Registratoin
Route::get('/signup/{id}', 'IndexController@user_subscription_page');
Route::get('/signup', 'IndexController@user_registration_page');
Route::post('/frontend/register_check', 'IndexController@register_check');
// Forgot Password

Route::get('/forgot_password', 'IndexController@forgot_password');
Route::post('/frontend/update_password', 'IndexController@update_password');

// FAQ
Route::get('/faq', 'IndexController@faq');

// Subscription
Route::get('/subscription', 'IndexController@subscription');

// Homepage
Route::get('/profile', 'IndexController@profile');


	/*==============Payment Integration ======================*/

// Payment Page
Route::post('/payment_page', 'IndexController@payment_page');

// for registration page 
Route::post('/payment_integration/{page}', 'IndexController@payment_integration');

// for profile page
Route::post('/payment_integration', 'IndexController@payment_integration');

// Update membership according to status
Route::get('/update_membership/{status}', 'IndexController@update_membership');


/*============================= CRON JOB  =============================*/
Route::post('/cronjob', 'IndexController@cronJob');



// Successfull Payment
// Route::get('/payment_succ/{id}/{page}', 'IndexController@successful_payment');
// Update Membership
// Route::get('/update_membership/{id}/{page}', 'IndexController@update_membership');


	
// Successfull Payment
// Route::get('/payment_succ/{id}', 'IndexController@successful_payment');
// Update Membership old
// Route::get('/update_membership/{id}', 'IndexController@update_membership');


// Payment Failed
// Route::get('/payment_failed', 'IndexController@unsuccessful_payment');







/* ================== Homepage ================== */
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');
Route::auth();

/* ================== Access Uploaded Files ================== */
Route::get('files/{hash}/{name}', 'LA\UploadsController@get_file');

/*
|--------------------------------------------------------------------------
| Admin Application Routes
|--------------------------------------------------------------------------
*/

$as = "";
if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
	$as = config('laraadmin.adminRoute').'.';
	
	// Routes for Laravel 5.3
	Route::get('/logout', 'Auth\LoginController@logout');
}

Route::group(['as' => $as, 'middleware' => ['auth', 'permission:ADMIN_PANEL']], function () {

	





	/* ================== Mailing System ================== */
	
	Route::get(config('laraadmin.adminRoute'). '/mailing_system', 'IndexController@mail_system_page');
	// Get email by ajax 

	Route::post('/get_email', 'IndexController@get_emails');
	Route::post('/send_mail', 'IndexController@send_mail');
























	
	/* ================== Dashboard ================== */
	
	Route::get(config('laraadmin.adminRoute'), 'LA\DashboardController@index');
	Route::get(config('laraadmin.adminRoute'). '/dashboard', 'LA\DashboardController@index');
	
	/* ================== Users ================== */
	Route::resource(config('laraadmin.adminRoute') . '/users', 'LA\UsersController');
	Route::get(config('laraadmin.adminRoute') . '/user_dt_ajax', 'LA\UsersController@dtajax');
	
	/* ================== Uploads ================== */
	Route::resource(config('laraadmin.adminRoute') . '/uploads', 'LA\UploadsController');
	Route::post(config('laraadmin.adminRoute') . '/upload_files', 'LA\UploadsController@upload_files');
	Route::get(config('laraadmin.adminRoute') . '/uploaded_files', 'LA\UploadsController@uploaded_files');
	Route::post(config('laraadmin.adminRoute') . '/uploads_update_caption', 'LA\UploadsController@update_caption');
	Route::post(config('laraadmin.adminRoute') . '/uploads_update_filename', 'LA\UploadsController@update_filename');
	Route::post(config('laraadmin.adminRoute') . '/uploads_update_public', 'LA\UploadsController@update_public');
	Route::post(config('laraadmin.adminRoute') . '/uploads_delete_file', 'LA\UploadsController@delete_file');
	
	/* ================== Roles ================== */
	Route::resource(config('laraadmin.adminRoute') . '/roles', 'LA\RolesController');
	Route::get(config('laraadmin.adminRoute') . '/role_dt_ajax', 'LA\RolesController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/save_module_role_permissions/{id}', 'LA\RolesController@save_module_role_permissions');
	
	/* ================== Permissions ================== */
	Route::resource(config('laraadmin.adminRoute') . '/permissions', 'LA\PermissionsController');
	Route::get(config('laraadmin.adminRoute') . '/permission_dt_ajax', 'LA\PermissionsController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/save_permissions/{id}', 'LA\PermissionsController@save_permissions');
	
	/* ================== Departments ================== */
	Route::resource(config('laraadmin.adminRoute') . '/departments', 'LA\DepartmentsController');
	Route::get(config('laraadmin.adminRoute') . '/department_dt_ajax', 'LA\DepartmentsController@dtajax');
	
	/* ================== Employees ================== */
	Route::resource(config('laraadmin.adminRoute') . '/employees', 'LA\EmployeesController');
	Route::get(config('laraadmin.adminRoute') . '/employee_dt_ajax', 'LA\EmployeesController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/change_password/{id}', 'LA\EmployeesController@change_password');
	
	/* ================== Organizations ================== */
	Route::resource(config('laraadmin.adminRoute') . '/organizations', 'LA\OrganizationsController');
	Route::get(config('laraadmin.adminRoute') . '/organization_dt_ajax', 'LA\OrganizationsController@dtajax');

	/* ================== Backups ================== */
	Route::resource(config('laraadmin.adminRoute') . '/backups', 'LA\BackupsController');
	Route::get(config('laraadmin.adminRoute') . '/backup_dt_ajax', 'LA\BackupsController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/create_backup_ajax', 'LA\BackupsController@create_backup_ajax');
	Route::get(config('laraadmin.adminRoute') . '/downloadBackup/{id}', 'LA\BackupsController@downloadBackup');

	/* ================== Memberships ================== */
	Route::resource(config('laraadmin.adminRoute') . '/memberships', 'LA\MembershipsController');
	Route::get(config('laraadmin.adminRoute') . '/membership_dt_ajax', 'LA\MembershipsController@dtajax');








	/* ================== Email_Templates ================== */
	Route::resource(config('laraadmin.adminRoute') . '/email_templates', 'LA\Email_TemplatesController');
	Route::get(config('laraadmin.adminRoute') . '/email_template_dt_ajax', 'LA\Email_TemplatesController@dtajax');


	

	/* ================== Terms_Conditions ================== */
	Route::resource(config('laraadmin.adminRoute') . '/terms_conditions', 'LA\Terms_ConditionsController');
	Route::get(config('laraadmin.adminRoute') . '/terms_condition_dt_ajax', 'LA\Terms_ConditionsController@dtajax');

	/* ================== Faqs ================== */
	Route::resource(config('laraadmin.adminRoute') . '/faqs', 'LA\FaqsController');
	Route::get(config('laraadmin.adminRoute') . '/faq_dt_ajax', 'LA\FaqsController@dtajax');




	/* ================== Leads_Upload_Areas ================== */
	Route::resource(config('laraadmin.adminRoute') . '/leads_upload_areas', 'LA\Leads_Upload_AreasController');
	Route::get(config('laraadmin.adminRoute') . '/leads_upload_area_dt_ajax', 'LA\Leads_Upload_AreasController@dtajax');
});
