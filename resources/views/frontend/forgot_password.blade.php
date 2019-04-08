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
							
							<form action="/frontend/update_password" method="post" name="createuser" id="createuser" class="ihc-form-create-edit" enctype="multipart/form-data">
	      					<input type="hidden" name="_token" value="{{ csrf_token() }}">
							  
							 
							  <div class="iump-form-line-register iump-form-text" id="ihc_reg_text_416"><label class="iump-labels-register"><span style="color: red;">*</span>Email</label>
							  	<input value="{{ old('email') }}" name="email" type="email" class="form-control element-block" placeholder="Email address *" required>
							  </div>							 
							
							
							
							<div class="iump-submit-form">
							  <div class="iump-register-row-left"><input type="submit" value="Send Password" name="Submit" id="ihc_submit_bttn" class="button button-primary button-large"></div>
							
							  <div class="iump-clear"></div>
							</div>
							</form>
						
		


	</div></div>
						</div>
						<div class="col-lg-3"></div>

					</div>
				</div>
			</div>
		</div>
	</div>

	
@endsection