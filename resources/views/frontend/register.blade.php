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
							  	{{-- <input type="text" name="user_email" class="" value="" placeholder=""></div> --}}
							  {{-- <div class="iump-form-line-register iump-form-text" id="ihc_reg_text_2573"><label class="iump-labels-register"><span style="color: red;">*</span>First Name</label><input type="text" name="first_name" class="" value="" placeholder=""></div>
							  <div class="iump-form-line-register iump-form-text" id="ihc_reg_text_6096"><label class="iump-labels-register"><span style="color: red;">*</span>Last Name</label><input type="text" name="last_name" class="" value="" placeholder=""></div> --}}
							  <div class="iump-form-line-register iump-form-password" id="ihc_reg_password_3477">
							    <label class="iump-labels-register"><span style="color: red;">*</span>Password</label>
							    <input title="6 characters Minimum" minlength="6" name="password" type="password" class="form-control element-block" placeholder="Password *" required>
							    {{-- <input type="password" name="pass1" class="" value="" placeholder="" data-rules="6,1"> --}}
							    {{-- <div class="ihc-strength-wrapper">
							      <ul class="ihc-strength">
							        <li class="point"></li>
							        <li class="point"></li>
							        <li class="point"></li>
							        <li class="point"></li>
							        <li class="point"></li>
							      </ul>
							      <div class="ihc-strength-label"></div>
							    </div> --}}
							  </div>
							  {{-- <div class="iump-form-line-register iump-form-password" id="ihc_reg_password_1190">
							    <label class="iump-labels-register"><span style="color: red;">*</span>Confirm Password</label>
							    <input type="password" class="form-control" placeholder="Retype password" name="password_confirmation" required/>
							    <input type="password" name="pass2" class="" value="" placeholder="" data-rules="6,1">
							    <div class="ihc-strength-wrapper">
							      <ul class="ihc-strength">
							        <li class="point"></li>
							        <li class="point"></li>
							        <li class="point"></li>
							        <li class="point"></li>
							        <li class="point"></li>
							      </ul>
							      <div class="ihc-strength-label"></div>
							    </div>
							  </div> --}}
							  {{-- <div class="iump-form-line-register iump-form-upload_image" id="ihc_reg_upload_image_1027">
							    <label class="iump-labels-register">Avatar</label>
							    
							    <div class="">
							      <div class="ihc-upload-image-wrapp">
							        <div class="ihc-no-avatar ihc-member-photo"></div>
							        <div class="ihc-clear"></div>
							      </div>
							      <div class="ihc-content-left">
							        <div class="ihc-avatar-trigger" id="js_ihc_trigger_avatar9289">
							          <div id="ihc-avatar-button" class="ihc-upload-avatar">Upload</div>
							</div>
							<span style="visibility: hidden;" class="ihc-upload-image-remove-bttn" id="ihc_upload_image_remove_bttn_9289">Remove</span>
							</div>
							<input type="hidden" value="" name="ihc_avatar" id="ihc_upload_hidden_9289">
							</div>
							</div> --}}
							{{-- <div class="ihc-tos-wrap" id="ihc_tos_field_parent_339">
							  <input type="checkbox" value="1" name="tos" class="">
							  <a href="https://xpromailer.pksol.com/iump-tos-page/" target="_blank">Accept our Terms&amp;Conditions</a>
							</div> --}}

							<div class="iump-form-line-register iump-form-text" id="ihc_reg_text_416"><label class="iump-labels-register"><span style="color: red;">*</span>Membership</label>
								{{-- <input value="{{ old('email') }}" name="email" type="email" class="form-control element-block" placeholder="Email address *" required> --}}
								<select name="membership_id">
									<option value="">Select Membership</option>	      		
									@foreach ($memeberships as $package)
									<option value="{{ $package->id }}">{{ $package->membership_name }} : {{ $package->cost }}/-</option>
									@endforeach	
								</select>
							</div>

							{{-- <div class="form-group">
							   
							</div> --}}
							<input type="hidden" name="ihc_payment_gateway" value="paypal"><input type="hidden" name="ihcaction" class="" value="register"><input type="hidden" name="lid" class="" value="1">
							<div class="iump-submit-form">
							  <div class="iump-register-row-left"><input type="submit" value="Register" name="Submit" id="ihc_submit_bttn" class="button button-primary button-large"></div>
							  <div class="iump-register-row-right">
							    <div class="ihc-login-link"><a href="/signin">LogIn</a></div>
							  </div>
							  <div class="iump-clear"></div>
							</div>
							</form>
							{{-- <div id="ihc_cart_wrapper">
							<div class="iump-level-details-register ">
							<div class="ihc-order-title">Payment details</div> --}}



		
        	                    <!-- LEVEL PRICE -->
             {{--    <span class="iump-level-details-register-name">free</span>
                <span class="iump-level-details-register-price">Free</span>
                <div class="iump-clear"></div>
 --}}
                <!-- DISCOUNT VALUE --->
                                {{-- <div class="iump-clear"></div> --}}

                <!-- TAXES VALUE -->
                                {{-- <div class="iump-clear"></div> --}}

			<!-- FINAL PRICE -->
						 {{--  <div class="iump-totalprice-wrapper">
						<span class="iump-level-details-register-name">Final Price: </span>
						<span class="iump-level-details-register-price">0USD</span>
						<div class="iump-clear"></div>
			  </div>
						<div class="iump-clear"></div> --}}
		


	</div>
			{{-- <input type="hidden" id="iumpfinalglobalp" value="0">
			<input type="hidden" id="iumpfinalglobalc" value="USD">
			<input type="hidden" id="iumpfinalglobal_ll" value="free"> --}}
			</div>
						</div>
						<div class="col-lg-3"></div>

					</div>
				</div>
			</div>
		</div>
	</div>
	{{-- <div class="col-xs-12 col-md-12">
	   
	      @if(session()->has('message'))
	          <div class="alert alert-info">
	              {!! session()->get('message') !!}
	          </div>
	      @endif
	   <form method="post" action="/frontend/register_check" class="user-log-form">
	      <input type="hidden" name="_token" value="{{ csrf_token() }}">
	      <h2>Register Form</h2>
	      <div class="form-group">
	         <input value="{{ old('first_name') }}" name="name" type="text" class="form-control element-block" placeholder="First Name *" required>
	      </div>	     
	      <div class="form-group">
	         <input value="{{ old('email') }}" name="email" type="email" class="form-control element-block" placeholder="Email address *" required>
	      </div>
	      <div class="form-group">
	         <input title="6 characters Minimum" minlength="6" name="password" type="password" class="form-control element-block" placeholder="Password *" required>
	      </div>
	      <div class="form-group">
	      	<select name="membership_id">
	      		<option value="">Select Membership</option>	      		
	      		@foreach ($memeberships as $package)
	      		<option value="{{ $package->id }}">{{ $package->membership_name }} : {{ $package->cost }}/-</option>
	      		@endforeach	
	      	</select>
	         
	      </div>
	      <div class="btns-wrap">
	         <div class="wrap">
	            <button type="submit" class="btn btn-theme btn-warning fw-bold font-lato text-uppercase">Register</button>
	         </div>
	      </div>
	   </form>
	</div> --}}

	
@endsection