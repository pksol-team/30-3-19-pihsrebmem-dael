@extends('frontend.template.layout')
@section('title') <?= $title; ?> @stop
@section('content')

<?php use App\Http\Controllers\Frontend\IndexController; ?>
<?php use Illuminate\Support\Collection; ?>

@section('class') {{ 'class=subscription' }} @stop

<div class="body_wrapper">
    <div class="container-fluid">
       <div class="row mid-banner-table">
			<div class="container">
				<div class="row">
					<div class="col-lg-3"></div>
					<div class="col-md-6">		
					{{-- {!! Form::open(['action' => '/payment_integration', 'id' => 'form_data']) !!} --}}
					{!! Form::open(['action' => 'IndexController@payment_integration', 'id' => 'form_data']) !!}
							  <div class="iump-form-line-register iump-form-text" id="ihc_reg_text_416"><label class="iump-labels-register"><span style="color: red;">*</span>Membership</label>
						 		<input type="text" disabled="true" class="form-control element-block" value="{{ $form_data[1] }}">							  	
						 		<input type="hidden" name="membership_name" class="form-control element-block" value="{{ $form_data[1] }}">							  	
							  </div>
							  <div class="iump-form-line-register iump-form-text" id="ihc_reg_text_416"><label class="iump-labels-register"><span style="color: red;">*</span>Membership Price</label>
						 		
						 		<input type="text" class="form-control element-block" value="{{ $form_data[2] }}" disabled="true">
						 		<input type="hidden" name="membership_cost" class="form-control element-block" value="{{ $form_data[2] }}">
							  </div>
						 		<input type="hidden" name="membership_id" class="form-control element-block" value="{{ $form_data[3] }}">							  							 		
						 		<input type="hidden" name="membership_type" class="form-control element-block" value="{{ $form_data[4] }}">							  							 		
						 		<input type="hidden" name="subscription_period" class="form-control element-block" value="{{ $form_data[5] }}">							  							 		

						 		@if ($form_data[4] != 'Free' && $form_data[5] != 'Lifetime')
						 			<input type="hidden" name="planId" class="form-control element-block" value="{{ $form_data[6] }}">							  							 								 			
						 			<input type="hidden" name="stripeplanId" class="form-control element-block" value="{{ $form_data[7] }}">							  							 								 			
						 		@endif

						 		<input type="hidden" value="{{ Auth::user()->email }}" name="email">
		
							  {{-- Payment Option --}}
		
							  <div class="row">
							      <div class="col-xs-7 col-sm-8" style="padding-right: 5px;">
							          <div class="form-group" id="dive">
							             <input type="radio" name="payment_method_" value="paypal" id="paypal">
							              <label for="paypal">Paypal</label>
							              <input type="radio" name="payment_method_" value="stripe" id="stripe">
							               <label for="stripe">Stripe</label>
							          </div>
							      </div>
							  </div>

							  {{-- Stripe Form --}}
	
							  	<div class="row strip_data" style=" display: none;">
							     	<div class="col-sm-12">
							          	<div id="charge-error" class="alert alert-danger {{ !Session::has('error') ? 'hidden' : ''}}"> 
							          	</div>
								        <h1><img src="https://stripe.com/favicon.ico"><svg width="62" height="25"><title>Stripe</title><path d="M5 10.1c0-.6.6-.9 1.4-.9 1.2 0 2.8.4 4 1.1V6.5c-1.3-.5-2.7-.8-4-.8C3.2 5.7 1 7.4 1 10.3c0 4.4 6 3.6 6 5.6 0 .7-.6 1-1.5 1-1.3 0-3-.6-4.3-1.3v3.8c1.5.6 2.9.9 4.3.9 3.3 0 5.5-1.6 5.5-4.5.1-4.8-6-3.9-6-5.7zM29.9 20h4V6h-4v14zM16.3 2.7l-3.9.8v12.6c0 2.4 1.8 4.1 4.1 4.1 1.3 0 2.3-.2 2.8-.5v-3.2c-.5.2-3 .9-3-1.4V9.4h3V6h-3V2.7zm8.4 4.5L24.6 6H21v14h4v-9.5c1-1.2 2.7-1 3.2-.8V6c-.5-.2-2.5-.5-3.5 1.2zm5.2-2.3l4-.8V.8l-4 .8v3.3zM61.1 13c0-4.1-2-7.3-5.8-7.3s-6.1 3.2-6.1 7.3c0 4.8 2.7 7.2 6.6 7.2 1.9 0 3.3-.4 4.4-1.1V16c-1.1.6-2.3.9-3.9.9s-2.9-.6-3.1-2.5H61c.1-.2.1-1 .1-1.4zm-7.9-1.5c0-1.8 1.1-2.5 2.1-2.5s2 .7 2 2.5h-4.1zM42.7 5.7c-1.6 0-2.5.7-3.1 1.3l-.1-1h-3.6v18.5l4-.7v-4.5c.6.4 1.4 1 2.8 1 2.9 0 5.5-2.3 5.5-7.4-.1-4.6-2.7-7.2-5.5-7.2zm-1 11c-.9 0-1.5-.3-1.9-.8V10c.4-.5 1-.8 1.9-.8 1.5 0 2.5 1.6 2.5 3.7 0 2.2-1 3.8-2.5 3.8z"></path></svg></h1>
								        <div class="row">
							            	<div class="col-sm-12">
									            <div class="form-group">
									              <input type="text" class="form-control" id="email" placeholder="Card Number" name="number" required="">
									            </div>
							            	</div>
							            </div>
							            <div class="row">
							            	<div class="col-sm-12">
									            <div class="form-group">
									              <input type="text" class="form-control" id="pwd" placeholder="CVC" name="cvc" required="">
									            </div>	
								            </div>						            	
							            </div>
							            <div class="row">
							              <div class="col-sm-6">
							                <div class="form-group">
							                  <input type="text" class="form-control" id="pwd" placeholder="Expire Month" name="exp_month" required="">
							                </div>
							              </div>
							              <div class="col-sm-6">
							                <div class="form-group">
							                  <input type="text" class="form-control" id="pwd" placeholder="Expire Year" name="exp_year" required="">
							                </div>          
							              </div>
							            </div>
							            <div class="row">
							            	<div class="col-sm-12">
								                <div class="form-group">
								                  <input type="text" class="form-control" id="pwd" placeholder="Name" value="{{ Auth::user()->name }}" disabled="true">
								                  <input type="hidden" class="form-control" id="pwd" placeholder="Name" name="client_name" value="{{ Auth::user()->name }}">
								                </div>
							            	</div>
							            </div>
							            <div class="row">
							            	<div class="col-sm-12">
									            <div class="form-group">
									              <input type="text" class="form-control" id="pwd" placeholder="Phone Number(Optional)" name="phone">
									            </div>
							            	</div>
							            </div>
							      	</div>
							  	</div>
							  	<div class="row">
					            	<div class="col-sm-12">
										{!! Form::submit( 'Proceed to checkout', ['class'=>'btn btn-success payment_stripe']) !!}
					            	</div>
					            </div>
							
					{!! Form::close() !!}
							
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


@endsection