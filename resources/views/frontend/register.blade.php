@extends('frontend.template.layout')
@section('title') <?= $title; ?> @stop
@section('content')

<?php use App\Http\Controllers\Frontend\IndexController; ?>
<?php use Illuminate\Support\Collection; ?>

@section('class') {{ 'class="register"' }} @stop


	<div class="body_wrapper">
		<div class="container-fluid">
			<div class="mid_banner" style="margin: 170px 0 100px 0;">
			@if(session()->has('message'))
			    <div class="alert alert-info">
			        {!! session()->get('message') !!}
			    </div>
			@endif
				<div class="container">
					<div class="row">


						<div class="col-lg-3"></div>
						<div class="col-md-6">
							
							<form action="/frontend/register_check" method="post" name="createuser" id="createuser" class="ihc-form-create-edit" enctype="multipart/form-data">
	      					<input type="hidden" name="_token" value="{{ csrf_token() }}">
							  
							  <div class="iump-form-line-register iump-form-text" id="ihc_reg_text_3289"><label class="iump-labels-register"><span style="color: red;">*</span>Username</label>
							  	<input value="{{ old('first_name') }}" name="name" type="text" class="form-control element-block" placeholder="Enter Name *" required>
							  </div>
							  	{{-- <input type="text" name="user_login" class="" value="" placeholder=""></div> --}}
							  <div class="iump-form-line-register iump-form-text" id="ihc_reg_text_416"><label class="iump-labels-register"><span style="color: red;">*</span>Email</label>
							  	<input value="{{ old('email') }}" name="email" type="email" class="form-control element-block" placeholder="Email address *" required>
							  </div>
								  <div class="iump-form-line-register iump-form-password" id="ihc_reg_password_3477">
							    <label class="iump-labels-register"><span style="color: red;">*</span>Password</label>
							    <input title="6 characters Minimum" minlength="6" name="password" type="password" class="form-control element-block" placeholder="Password *" required>
							  </div>
						
							<div class="iump-form-line-register iump-form-text" id="ihc_reg_text_416"><label class="iump-labels-register"><span style="color: red;">*</span>Membership</label>
								<select name="membership_id">
									<option value="">Select Membership</option>	      		
									@foreach ($memeberships as $package)
									<option value="{{ $package->id }}">{{ $package->membership_name }} : {{ $package->cost }}/-</option>
									@endforeach	
								</select>
							</div>
							<input type="hidden" name="ihc_payment_gateway" value="paypal"><input type="hidden" name="ihcaction" class="" value="register"><input type="hidden" name="lid" class="" value="1">
							<div class="iump-submit-form">
							  <div class="iump-register-row-left"><input type="submit" value="Register" name="Submit" id="ihc_submit_bttn" class="button button-primary button-large"></div>
							  <div class="iump-register-row-right">
							    <div class="ihc-login-link"><a href="/signin">LogIn</a></div>
							  </div>
							  <div class="iump-clear"></div>
							</div>
							</form>
						

						</div>
					</div>
				</div>
				<div class="col-lg-3"></div>

					</div>
				</div>
			</div>
		</div>
	</div>


	
@endsection