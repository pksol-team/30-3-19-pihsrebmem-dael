@extends('frontend.template.layout')
@section('title') <?= $title; ?> @stop
@section('content')

<?php use App\Http\Controllers\Frontend\IndexController; ?>
<?php use Illuminate\Support\Collection; ?>


<div class="body_wrapper">
        <div class="container-fluid">
           <div class="row mid-banner-table">
			<div class="container">

				@if(Session::has('message'))
				    <div class="alert alert-info">
				        {!! session('message') !!}
				    </div>
				@endif
	

	<div class="upgrade_account" style="display: none;">
		<span class="close_upgrade">x</span>
		<p>Upgrade your system</p> 
		@foreach ($membership as $package)
			@if (Auth::user()->membership_id != $package->id)
			@endif
			<a href="#" data-price='{{ $package->cost }}' data-name='{{ $package->membership_name }}' data-id='{{ $package->id }}' class="upgrade_memebership {{ (Auth::user()->membership_id == $package->id)? 'disabled': '' }}">{{ $package->membership_name }}</a> : {{ $package->cost }} <br>
		@endforeach
	</div>
	<div class="pay_option_div" style="display: none;">
		{!! Form::open(['action' => 'IndexController@payment_integration', 'id' => 'form_data']) !!}
		<input type="hidden" name="user_name" value="{{ Auth::user()->name }}">
		<input type="hidden" name="membership_cost" value="">
		<input type="hidden" name="membership_name" value="">
		<input type="hidden" name="membership_id" value="">
		<label class="control-label col-xs-12 col-sm-3 col-md-4 align-left">Select Payment Method</label>
		<div class="col-xs-12 col-sm-9 col-md-8">
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
		</div>

		<div class="row strip_data" style=" display: none;">
		    <div class="col-sm-12">
		        <div id="charge-error" class="alert alert-danger {{ !Session::has('error') ? 'hidden' : ''}}"> 
		        </div>


		      <h1><img src="https://stripe.com/favicon.ico"><svg width="62" height="25"><title>Stripe</title><path d="M5 10.1c0-.6.6-.9 1.4-.9 1.2 0 2.8.4 4 1.1V6.5c-1.3-.5-2.7-.8-4-.8C3.2 5.7 1 7.4 1 10.3c0 4.4 6 3.6 6 5.6 0 .7-.6 1-1.5 1-1.3 0-3-.6-4.3-1.3v3.8c1.5.6 2.9.9 4.3.9 3.3 0 5.5-1.6 5.5-4.5.1-4.8-6-3.9-6-5.7zM29.9 20h4V6h-4v14zM16.3 2.7l-3.9.8v12.6c0 2.4 1.8 4.1 4.1 4.1 1.3 0 2.3-.2 2.8-.5v-3.2c-.5.2-3 .9-3-1.4V9.4h3V6h-3V2.7zm8.4 4.5L24.6 6H21v14h4v-9.5c1-1.2 2.7-1 3.2-.8V6c-.5-.2-2.5-.5-3.5 1.2zm5.2-2.3l4-.8V.8l-4 .8v3.3zM61.1 13c0-4.1-2-7.3-5.8-7.3s-6.1 3.2-6.1 7.3c0 4.8 2.7 7.2 6.6 7.2 1.9 0 3.3-.4 4.4-1.1V16c-1.1.6-2.3.9-3.9.9s-2.9-.6-3.1-2.5H61c.1-.2.1-1 .1-1.4zm-7.9-1.5c0-1.8 1.1-2.5 2.1-2.5s2 .7 2 2.5h-4.1zM42.7 5.7c-1.6 0-2.5.7-3.1 1.3l-.1-1h-3.6v18.5l4-.7v-4.5c.6.4 1.4 1 2.8 1 2.9 0 5.5-2.3 5.5-7.4-.1-4.6-2.7-7.2-5.5-7.2zm-1 11c-.9 0-1.5-.3-1.9-.8V10c.4-.5 1-.8 1.9-.8 1.5 0 2.5 1.6 2.5 3.7 0 2.2-1 3.8-2.5 3.8z"></path></svg></h1>

		      

		          <div class="form-group">
		            <input type="text" class="form-control" id="email" placeholder="Card Number" name="number">
		          </div>
		          <div class="form-group">
		            <input type="text" class="form-control" id="pwd" placeholder="CVC" name="cvc">
		          </div>
		          <div class="row">
		            <div class="col-sm-6">
		              <div class="form-group">
		                <input type="text" class="form-control" id="pwd" placeholder="Expire Month" name="exp_month">
		              </div>
		            </div>
		            <div class="col-sm-6">
		              <div class="form-group">
		                <input type="text" class="form-control" id="pwd" placeholder="Expire Year" name="exp_year">
		              </div>          
		            </div>
		          </div>
		              <div class="form-group">
		                <input type="text" class="form-control" id="pwd" placeholder="Name" name="client_name" value="{{ Auth::user()->name }}">
		              </div>
		          <div class="form-group">
		            <input type="text" class="form-control" id="pwd" placeholder="Phone Number" name="phone">
		          </div>
		        
		    </div>
		</div>
		{!! Form::submit( 'Proceed to checkout', ['class'=>'btn btn-success payment_stripe']) !!}
		{!! Form::close() !!}
	</div>
				@php	
					$user_memebership = Auth::user()->membership_id;
					$user_memebership_level = $membership_level;

				@endphp
				<div id="table-area" class="col-lg-12">
					<table>
					<thead>
						<tr>
							<th>ID</th>
							<th>File</th>
							<th>Membership</th>
							<th>Membership Level</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($files as $list)
							<tr>
								<td>{{ $list->id }}</td>
						
								@php
									$get_file = DB::table('uploads')->where('id', $list->file)->whereNull('deleted_at')->first();
									if($get_file){						
										$name = $get_file->name;
										$hash = $get_file->hash;
										$link = 'files/'.$hash.'/'.$name;
									}	
							if($list->membership_id != Auth::user()->membership_id){
								
								if($membership_level >= $list->memberships->membership_level){
									@endphp
										<td><a href ="{{ $link }}"> {{ $name }}</a></td>

									@php
										

								}else{
										
									@endphp
								<td>
									{{-- <a data-toggle="modal" data-target="#myModal"> {{ $name }} </a> --}}
									<a class="upgrade" data-toggle="modal" data-target="#myModal"> {{ $name }} </a>
								</td>
								@php
								}
								@endphp	


								@php
									}	else{												
										@endphp
										<td><a href ="{{ $link }}"> {{ $name }}</a></td>
										
										@php
											
									}

										@endphp
						
										<td>{{ $list->memberships->membership_name }}</td>
										<td>{{ $list->memberships->membership_level }}</td>

						
							</tr>
						@endforeach
					</tbody>
				</table>
				</div>
			</div>
		</div>
	</div>
</div>


  										<!-- The Modal -->
  										<div class="modal" id="myModal">
    										<div class="modal-dialog">
      												<div class="modal-content">
      
        												<!-- Modal Header -->
        												<div class="modal-header">
          												<h4 class="modal-title">Upgrade Your Account</h4>
          												<button type="button" class="close" data-dismiss="modal">&times;</button>
        												</div>
        
        												<!-- Modal body -->
        												<div class="modal-body">
          												<div class="row">
															<div class="col-lg-4">
																<div class="borderimage text-center">
																	<div class="free">
																		<span>FREE</span>
																		<h1>Free</h1>
																	</div>
																	<div class="fee-plan">
																		<span>Fee Plan</span>
																	</div>
																	<div class="freebtn">
																	<a href="#">SIGN UP</a>
																	</div>
																</div>
															</div>
															<div class="col-lg-4">
																<div class="borderimage text-center">
																	<div class="free">
																		<span>GOLD</span>
																		<h1>$100</h1>
																	</div>
																	<div class="fee-plan">
																		<span>Gold Plan</span>
																	</div>
																	<div class="freebtn">
																		<a href="#">SIGN UP</a>
																	</div>
																</div>
															</div>
															<div class="col-lg-4">
																<div class="borderimage text-center">
																	<div class="free">
																		<span>PLATNIUM</span>
																		<h1>$500</h1>
																	</div>
																	<div class="fee-plan">
																		<span>Platinum Plan</span>
																	</div>
																	<div class="freebtn">
																		<a href="#">SIGN UP</a>
																	</div>
																</div>
															</div>
														</div>
														<div id="modal-line-2" class="row">
															<div class="col-lg-4">
																<div class="borderimage text-center">
																	<div class="free">
																		<span>FREE</span>
																		<h1>Free</h1>
																	</div>
																	<div class="fee-plan">
																		<span>Fee Plan</span>
																	</div>
																	<div class="freebtn">
																	<a href="#">SIGN UP</a>
																	</div>
																</div>
															</div>
															<div class="col-lg-4">
																<div class="borderimage text-center">
																	<div class="free">
																		<span>GOLD</span>
																		<h1>$100</h1>
																	</div>
																	<div class="fee-plan">
																		<span>Gold Plan</span>
																	</div>
																	<div class="freebtn">
																		<a href="#">SIGN UP</a>
																	</div>
																</div>
															</div>
															<div class="col-lg-4">
																<div class="borderimage text-center">
																	<div class="free">
																		<span>PLATNIUM</span>
																		<h1>$500</h1>
																	</div>
																	<div class="fee-plan">
																		<span>Platinum Plan</span>
																	</div>
																	<div class="freebtn">
																		<a href="#">SIGN UP</a>
																	</div>
																</div>
															</div>
														</div>
        
        												<!-- Modal footer -->
        												<div class="modal-footer">
        													<div class="text-center">
          														<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        													</div>
        												</div>
       												</div>
      										</div>
  										</div>


{{-- @if(Session::has('message'))
    <div class="alert alert-info">
        {!! session('message') !!}
    </div>
@endif
<div class="upgrade_account" style="display: none;">
	<span class="close_upgrade">x</span>
	<p>Upgrade your system</p> 
	@foreach ($membership as $package)
		@if (Auth::user()->membership_id != $package->id)
		@endif
		<a href="#" data-price='{{ $package->cost }}' data-name='{{ $package->membership_name }}' data-id='{{ $package->id }}' class="upgrade_memebership {{ (Auth::user()->membership_id == $package->id)? 'disabled': '' }}">{{ $package->membership_name }}</a> : {{ $package->cost }} <br>
	@endforeach
</div>
<div class="pay_option_div" style="display: none;">
	{!! Form::open(['action' => 'IndexController@payment_integration', 'id' => 'form_data']) !!}
	<input type="hidden" name="user_name" value="{{ Auth::user()->name }}">
	<input type="hidden" name="membership_cost" value="">
	<input type="hidden" name="membership_name" value="">
	<input type="hidden" name="membership_id" value="">
	<label class="control-label col-xs-12 col-sm-3 col-md-4 align-left">Select Payment Method</label>
	<div class="col-xs-12 col-sm-9 col-md-8">
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
	</div>

	<div class="row strip_data" style=" display: none;">
	    <div class="col-sm-12">
	        <div id="charge-error" class="alert alert-danger {{ !Session::has('error') ? 'hidden' : ''}}"> 
	        </div>


	      <h1><img src="https://stripe.com/favicon.ico"><svg width="62" height="25"><title>Stripe</title><path d="M5 10.1c0-.6.6-.9 1.4-.9 1.2 0 2.8.4 4 1.1V6.5c-1.3-.5-2.7-.8-4-.8C3.2 5.7 1 7.4 1 10.3c0 4.4 6 3.6 6 5.6 0 .7-.6 1-1.5 1-1.3 0-3-.6-4.3-1.3v3.8c1.5.6 2.9.9 4.3.9 3.3 0 5.5-1.6 5.5-4.5.1-4.8-6-3.9-6-5.7zM29.9 20h4V6h-4v14zM16.3 2.7l-3.9.8v12.6c0 2.4 1.8 4.1 4.1 4.1 1.3 0 2.3-.2 2.8-.5v-3.2c-.5.2-3 .9-3-1.4V9.4h3V6h-3V2.7zm8.4 4.5L24.6 6H21v14h4v-9.5c1-1.2 2.7-1 3.2-.8V6c-.5-.2-2.5-.5-3.5 1.2zm5.2-2.3l4-.8V.8l-4 .8v3.3zM61.1 13c0-4.1-2-7.3-5.8-7.3s-6.1 3.2-6.1 7.3c0 4.8 2.7 7.2 6.6 7.2 1.9 0 3.3-.4 4.4-1.1V16c-1.1.6-2.3.9-3.9.9s-2.9-.6-3.1-2.5H61c.1-.2.1-1 .1-1.4zm-7.9-1.5c0-1.8 1.1-2.5 2.1-2.5s2 .7 2 2.5h-4.1zM42.7 5.7c-1.6 0-2.5.7-3.1 1.3l-.1-1h-3.6v18.5l4-.7v-4.5c.6.4 1.4 1 2.8 1 2.9 0 5.5-2.3 5.5-7.4-.1-4.6-2.7-7.2-5.5-7.2zm-1 11c-.9 0-1.5-.3-1.9-.8V10c.4-.5 1-.8 1.9-.8 1.5 0 2.5 1.6 2.5 3.7 0 2.2-1 3.8-2.5 3.8z"></path></svg></h1>

	      

	          <div class="form-group">
	            <input type="text" class="form-control" id="email" placeholder="Card Number" name="number">
	          </div>
	          <div class="form-group">
	            <input type="text" class="form-control" id="pwd" placeholder="CVC" name="cvc">
	          </div>
	          <div class="row">
	            <div class="col-sm-6">
	              <div class="form-group">
	                <input type="text" class="form-control" id="pwd" placeholder="Expire Month" name="exp_month">
	              </div>
	            </div>
	            <div class="col-sm-6">
	              <div class="form-group">
	                <input type="text" class="form-control" id="pwd" placeholder="Expire Year" name="exp_year">
	              </div>          
	            </div>
	          </div>
	              <div class="form-group">
	                <input type="text" class="form-control" id="pwd" placeholder="Name" name="client_name" value="{{ Auth::user()->name }}">
	              </div>
	          <div class="form-group">
	            <input type="text" class="form-control" id="pwd" placeholder="Phone Number" name="phone">
	          </div>
	        
	    </div>
	</div>
	{!! Form::submit( 'Proceed to checkout', ['class'=>'btn btn-success payment_stripe']) !!}
	{!! Form::close() !!}
</div>


<br>
@php	
	$user_memebership = Auth::user()->membership_id;
	$user_memebership_level = $membership_level;

@endphp



<table>
	<tr>
		<th>ID</th>
		<th>File</th>
		<th>Membership Level</th>
	</tr>
	<tbody>

		@foreach ($files as $list)
			<tr>
				<td>{{ $list->id }}</td>
		
				@php
					$get_file = DB::table('uploads')->where('id', $list->file)->whereNull('deleted_at')->first();
					if($get_file){						
						$name = $get_file->name;
						$hash = $get_file->hash;
						$link = 'files/'.$hash.'/'.$name;
					}	
			if($list->membership_id != Auth::user()->membership_id){
				
				if($membership_level >= $list->memberships->membership_level){
					@endphp
						<td><a href ="{{ $link }}"> {{ $name }}</a></td>

					@php
						

				}


				@endphp	

				<td><a class="upgrade"> {{ $name }} </a></td>

				@php
					}	else{												
						@endphp
						<td><a href ="{{ $link }}"> {{ $name }}</a></td>
						
						@php
							
					}

						@endphp
	
						<td>{{ $list->memberships->membership_level }}</td>

		
			</tr>
		@endforeach	

		
	</tbody>
</table> --}}

      
    
<style>
	a.disabled{
		cursor: not-allowed;
		opacity: 0.5;
	}
	.hidden{
		display: none;
	}
</style>

	
@endsection