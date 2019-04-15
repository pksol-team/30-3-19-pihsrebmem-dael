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

				@if(Session::has('message'))
				    <div class="alert alert-info">
				        {!! session('message') !!}
				    </div>
				@endif
		
	

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
										<td><a href ="{{ $link }}"> {{ $list->file_name }}</a></td>

									@php
										

								}else{
										
									@endphp
								<td>
									<a data-toggle="modal" data-target="#myModal"> {{ $list->file_name }} </a>
								</td>
								@php
								}
								@endphp	


								@php
									}	else{												
										@endphp
										<td><a href ="{{ $link }}"> {{ $list->file_name }}</a></td>
										
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


@php

    $membership = DB::table('memberships')->whereNull('deleted_at')->get();

	$membership_count = count($membership);

if($membership_count > 3){
  if($membership_count/3 > round($membership_count/3)){
    $total_rows = (round($membership_count/3)) + 1 ;
  }else{
    $total_rows = round($membership_count/3);
  }
         
}
else{
  $total_rows = 1;
}  

	@endphp
	{{-- memberships upgrade section --}}
	<div class="modal" id="myModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Upgrade Your Account</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">					
					@for ($i = 0; $i < $total_rows ; $i++)					
					
						@php
							$starting = $i*3;
						    $membership = DB::table('memberships')->whereNull('deleted_at')->skip($starting)->take(3)->get();
						    $count = count($membership);
						    if($count){
						    	@endphp
						    	<div class="row">
						    		@if ($count == 1)
							    		<div class="col-lg-4 ">
							    			<div class="borderimage text-center">
							    				<div class="free">
							    					<span>{{ $membership[0]->cost }}/-</span>
							    					<h1>{{ $membership[0]->type }}</h1>
							    				</div>
							    				<div class="fee-plan">
							    					<span>{{ $membership[0]->membership_name }}</span>
						    						<h1 style="font-size:18px;">${{ $membership[0]->cost }} / {{ $membership[0]->subscription_period }}</h1>
							    				</div>
							    				<div class="freebtn">
							    					@if (!Auth::user())           
							    					<a href="/signup">SIGN UP</a>
							    					@else 

								    					{!! Form::open(['action' => 'IndexController@payment_page', 'id' => 'form_data']) !!}
								    					<input type="hidden" name="user_name" value="{{ Auth::user()->name }}">
								    					<input type="hidden" name="membership_cost" value="{{ $membership[0]->cost }}">
								    					<input type="hidden" name="membership_name" value="{{ $membership[0]->membership_name }}">
								    					<input type="hidden" name="membership_id" value="{{ $membership[0]->id }}">
								    					<input type="hidden" name="membership_type" value="{{ $membership[0]->type }}">
								    					<input type="hidden" name="subscription_period" value="{{ $membership[0]->subscription_period }}">
								    					<input type="hidden" name="unsubscribe" value="{{ (Auth::user()->membership_id == $membership[0]->id)? 'true': 'false' }}">
								    					@if ($membership[0]->type == 'Paid' && $membership[0]->subscription_period != 'Lifetime')
								    						<input type="hidden" name="planId" value="{{ $membership[0]->plan_id }}">								    						
								    						<input type="hidden" name="stripeplanId" value="{{ $membership[0]->stripe_plan_id }}">								    						
								    					@endif
								    					@if (Auth::user()->membership_id == $membership[0]->id)
								    						
								    					{!! Form::submit('Unsubscribe', ['class'=>'btn btn-danger']) !!}
								    					@else
								    					{!! Form::submit('Subscribe', ['class'=>'btn btn-success']) !!}

								    					@endif
								    					{!! Form::close() !!}
							    					@endif
							    				</div>
							    			</div>
							    		</div>	
							    	@elseif($count == 2)
							    		<div class="col-lg-4 ">
							    			<div class="borderimage text-center">
							    				<div class="free">
							    					<span>{{ $membership[0]->cost }}/-</span>
							    					<h1>{{ $membership[0]->type }}</h1>
							    				</div>
							    				<div class="fee-plan">
							    					<span>{{ $membership[0]->membership_name }}</span>
						    						<h1 style="font-size:18px;">${{ $membership[0]->cost }} / {{ $membership[0]->subscription_period }}</h1>

							    				</div>
							    				<div class="freebtn">
							    					@if (!Auth::user())           
							    					<a href="/signup">SIGN UP</a>
							    					@else
							    						{!! Form::open(['action' => 'IndexController@payment_page', 'id' => 'form_data']) !!}
							    						<input type="hidden" name="user_name" value="{{ Auth::user()->name }}">
							    						<input type="hidden" name="membership_cost" value="{{ $membership[0]->cost }}">
							    						<input type="hidden" name="membership_name" value="{{ $membership[0]->membership_name }}">
							    						<input type="hidden" name="membership_id" value="{{ $membership[0]->id }}">
							    						<input type="hidden" name="membership_type" value="{{ $membership[0]->type }}">
							    						<input type="hidden" name="subscription_period" value="{{ $membership[0]->subscription_period }}">
								    					<input type="hidden" name="unsubscribe" value="{{ (Auth::user()->membership_id == $membership[0]->id)? 'true': 'false' }}">
							    						
							    						@if ($membership[0]->type == 'Paid' && $membership[0]->subscription_period != 'Lifetime')
							    							<input type="hidden" name="planId" value="{{ $membership[0]->plan_id }}">								    						
							    							<input type="hidden" name="stripeplanId" value="{{ $membership[0]->stripe_plan_id }}">								    						
							    						@endif
							    						@if (Auth::user()->membership_id == $membership[0]->id)
								    						
								    					{!! Form::submit('Unsubscribe', ['class'=>'btn btn-danger']) !!}
								    					@else
								    					{!! Form::submit('Subscribe', ['class'=>'btn btn-success']) !!}

								    					@endif
							    						{!! Form::close() !!}	
							    					{{-- <span><a class="Subscribe">Subscribe</a></span> --}}
							    					@endif
							    				</div>
							    			</div>
							    		</div>
							    		<div class="col-lg-4 ">
							    			<div class="borderimage text-center">
							    				<div class="free">
							    					<span>{{ $membership[1]->cost }}/-</span>
							    					<h1>{{ $membership[1]->type }}</h1>
							    				</div>
							    				<div class="fee-plan">
							    					<span>{{ $membership[1]->membership_name }}</span>
						    						<h1 style="font-size:18px;">${{ $membership[1]->cost }} / {{ $membership[1]->subscription_period }}</h1>

							    				</div>
							    				<div class="freebtn">
							    					@if (!Auth::user())           
							    					<a href="/signup">SIGN UP</a>
							    					@else
						    							{!! Form::open(['action' => 'IndexController@payment_page', 'id' => 'form_data']) !!}
						    							<input type="hidden" name="user_name" value="{{ Auth::user()->name }}">
						    							<input type="hidden" name="membership_cost" value="{{ $membership[1]->cost }}">
						    							<input type="hidden" name="membership_name" value="{{ $membership[1]->membership_name }}">
						    							<input type="hidden" name="membership_id" value="{{ $membership[1]->id }}">
						    							<input type="hidden" name="membership_type" value="{{ $membership[1]->type }}">
						    							<input type="hidden" name="subscription_period" value="{{ $membership[1]->subscription_period }}">
								    					<input type="hidden" name="unsubscribe" value="{{ (Auth::user()->membership_id == $membership[1]->id)? 'true': 'false' }}">
						    							
						    							@if ($membership[1]->type == 'Paid' && $membership[1]->subscription_period != 'Lifetime')
						    								<input type="hidden" name="planId" value="{{ $membership[1]->plan_id }}">								    						
						    								<input type="hidden" name="stripeplanId" value="{{ $membership[1]->stripe_plan_id }}">								    						
						    							@endif
						    							@if (Auth::user()->membership_id == $membership[1]->id)
								    						
								    					{!! Form::submit('Unsubscribe', ['class'=>'btn btn-danger']) !!}
								    					@else
								    					{!! Form::submit('Subscribe', ['class'=>'btn btn-success']) !!}

								    					@endif
						    							{!! Form::close() !!}	
							    					@endif
							    				</div>
							    				</div>
							    		</div>
							    	@elseif($count == 3)
							    		<div class="col-lg-4 ">
							    			<div class="borderimage text-center">
							    				<div class="free">
							    					<span>{{ $membership[0]->cost }}/-</span>
							    					<h1>{{ $membership[0]->type }}</h1>
							    				</div>
							    				<div class="fee-plan">
							    					<span>{{ $membership[0]->membership_name }}</span>
						    						<h1 style="font-size:18px;">${{ $membership[0]->cost }} / {{ $membership[0]->subscription_period }}</h1>
							    				</div>
							    				<div class="freebtn">
							    					@if (!Auth::user())           
							    					<a href="/signup">SIGN UP</a>
							    					@else
							    						{!! Form::open(['action' => 'IndexController@payment_page', 'id' => 'form_data']) !!}
							    						<input type="hidden" name="user_name" value="{{ Auth::user()->name }}">
							    						<input type="hidden" name="membership_cost" value="{{ $membership[0]->cost }}">
							    						<input type="hidden" name="membership_name" value="{{ $membership[0]->membership_name }}">
							    						<input type="hidden" name="membership_id" value="{{ $membership[0]->id }}">
							    						<input type="hidden" name="membership_type" value="{{ $membership[0]->type }}">
							    						<input type="hidden" name="subscription_period" value="{{ $membership[0]->subscription_period }}">
								    					<input type="hidden" name="unsubscribe" value="{{ (Auth::user()->membership_id == $membership[0]->id)? 'true': 'false' }}">
							    						
							    						@if ($membership[0]->type == 'Paid' && $membership[0]->subscription_period != 'Lifetime')
							    							<input type="hidden" name="planId" value="{{ $membership[0]->plan_id }}">								    						
							    							<input type="hidden" name="stripeplanId" value="{{ $membership[0]->stripe_plan_id }}">								    						
							    						@endif
							    						@if (Auth::user()->membership_id == $membership[0]->id)
								    						
								    					{!! Form::submit('Unsubscribe', ['class'=>'btn btn-danger']) !!}
								    					@else
								    					{!! Form::submit('Subscribe', ['class'=>'btn btn-success']) !!}

								    					@endif
							    						{!! Form::close() !!}
							    					@endif
							    				</div>
							    			</div>
							    		</div>
							    		<div class="col-lg-4 ">
							    			<div class="borderimage text-center">
							    				<div class="free">
							    					<span>{{ $membership[1]->cost }}/-</span>
							    					<h1>{{ $membership[1]->type }}</h1>
							    				</div>
							    				<div class="fee-plan">
							    					<span>{{ $membership[1]->membership_name }}</span>
						    						<h1 style="font-size:18px;">${{ $membership[1]->cost }} / {{ $membership[1]->subscription_period }}</h1>							    					
							    				</div>
							    				<div class="freebtn">
							    					@if (!Auth::user())           
							    					<a href="/signup">SIGN UP</a>
							    					@else
								    					{!! Form::open(['action' => 'IndexController@payment_page', 'id' => 'form_data']) !!}
								    					<input type="hidden" name="user_name" value="{{ Auth::user()->name }}">
								    					<input type="hidden" name="membership_cost" value="{{ $membership[1]->cost }}">
								    					<input type="hidden" name="membership_name" value="{{ $membership[1]->membership_name }}">
								    					<input type="hidden" name="membership_id" value="{{ $membership[1]->id }}">
								    					<input type="hidden" name="membership_type" value="{{ $membership[1]->type }}">
								    					<input type="hidden" name="subscription_period" value="{{ $membership[1]->subscription_period }}">
								    					<input type="hidden" name="unsubscribe" value="{{ (Auth::user()->membership_id == $membership[1]->id)? 'true': 'false' }}">
								    					@if ($membership[1]->type == 'Paid' && $membership[1]->subscription_period != 'Lifetime')
								    						<input type="hidden" name="planId" value="{{ $membership[1]->plan_id }}">								    						
								    						<input type="hidden" name="stripeplanId" value="{{ $membership[1]->stripe_plan_id }}">								    						
								    					@endif
								    					@if (Auth::user()->membership_id == $membership[1]->id)
								    						
								    					{!! Form::submit('Unsubscribe', ['class'=>'btn btn-danger']) !!}
								    					@else
								    					{!! Form::submit('Subscribe', ['class'=>'btn btn-success']) !!}

								    					@endif
								    					{!! Form::close() !!}
							    					@endif
							    				</div>
							    				</div>
							    		</div>
							    		<div class="col-lg-4 ">
							    			<div class="borderimage text-center">
							    				<div class="free">
							    					<span>{{ $membership[2]->cost }}/-</span>
							    					<h1>{{ $membership[2]->type }}</h1>
							    				</div>
							    				<div class="fee-plan">
							    					<span>{{ $membership[2]->membership_name }}</span>
						    						<h1 style="font-size:18px;">${{ $membership[2]->cost }} / {{ $membership[2]->subscription_period }}</h1>							    					
							    				</div>
							    				<div class="freebtn">
							    					@if (!Auth::user())           
							    					<a href="/signup">SIGN UP</a>
							    					@else
							    						{!! Form::open(['action' => 'IndexController@payment_page', 'id' => 'form_data']) !!}
							    						<input type="hidden" name="user_name" value="{{ Auth::user()->name }}">
							    						<input type="hidden" name="membership_cost" value="{{ $membership[2]->cost }}">
							    						<input type="hidden" name="membership_name" value="{{ $membership[2]->membership_name }}">
							    						<input type="hidden" name="membership_id" value="{{ $membership[2]->id }}">
							    						<input type="hidden" name="membership_type" value="{{ $membership[2]->type }}">
							    						<input type="hidden" name="subscription_period" value="{{ $membership[2]->subscription_period }}">
								    					<input type="hidden" name="unsubscribe" value="{{ (Auth::user()->membership_id == $membership[2]->id)? 'true': 'false' }}">
							    						@if ($membership[2]->type == 'Paid' && $membership[2]->subscription_period != 'Lifetime')
							    							<input type="hidden" name="planId" value="{{ $membership[2]->plan_id }}">								    						
							    							<input type="hidden" name="stripeplanId" value="{{ $membership[2]->stripe_plan_id }}">								    						
							    						@endif
							    						@if (Auth::user()->membership_id == $membership[2]->id)
								    						
								    					{!! Form::submit('Unsubscribe', ['class'=>'btn btn-danger']) !!}
								    					@else
								    					{!! Form::submit('Subscribe', ['class'=>'btn btn-success']) !!}

								    					@endif
							    						{!! Form::close() !!}
							    					@endif
							    				</div>
							    			</div>
							    		</div>		
						    		@endif
						    	</div>
						    	@php
						    }
						@endphp						
					@endfor
				</div>
			</div>
		</div>
	</div>

    
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