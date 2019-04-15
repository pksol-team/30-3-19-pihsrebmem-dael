@extends('frontend.template.layout')
@section('title') <?= $title; ?> @stop
@section('content')

<?php use App\Http\Controllers\Frontend\IndexController; ?>
<?php use Illuminate\Support\Collection; ?>

@section('class') {{ 'class=subscription' }} @stop


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

<div class="body_wrapper">
        <div class="container-fluid">
           <div class="row mid-banner-table">
			<div class="container">
				@for ($i = 0; $i < $total_rows ; $i++)					
				
					@php
						$starting = $i*3;
					    $membership = DB::table('memberships')->whereNull('deleted_at')->skip($starting)->take(3)->get();
					    $count = count($membership);
					    if($count){
					    	@endphp
					    	<div class="row">
					    		@if ($count == 1)
						    		<div class="col-lg-4">
						    			<div class="borderimage text-center">
						    				<div class="free">
						    					<span>{{ $membership[0]->cost }}/-</span>
						    					<h1>{{ $membership[0]->type }}</h1>
						    				</div>
						    				<div class="fee-plan">
						    					<span>{{ $membership[0]->membership_name }}</span>
						    					<br>
						    					<h1 style="font-size:18px;">${{ $membership[0]->cost }} / {{ $membership[0]->subscription_period }}</h1>
						    					<br>
						    					
						    				</div>
						    				<div class="freebtn">
						    					@if (!Auth::user())           
						    					<a href="/signup/{{ $membership[0]->id }}">SIGN UP</a>
						    					@else 

							    					{!! Form::open(['action' => 'IndexController@payment_page', 'id' => 'form_data']) !!}
							    					<input type="hidden" name="user_name" value="{{ Auth::user()->name }}">
							    					<input type="hidden" name="membership_cost" value="{{ $membership[0]->cost }}">
							    					<input type="hidden" name="membership_name" value="{{ $membership[0]->membership_name }}">
							    					<input type="hidden" name="membership_id" value="{{ $membership[0]->id }}">
							    					{!! Form::submit( 'PURCHASE', ['class'=>'btn btn-success']) !!}
							    					{!! Form::close() !!}
						    					@endif
						    				</div>
						    			</div>
						    		</div>	
						    	@elseif($count == 2)
						    		<div class="col-lg-4">
						    			<div class="borderimage text-center">
						    				<div class="free">
						    					<span>{{ $membership[0]->cost }}/-</span>
						    					<h1>{{ $membership[0]->type }}</h1>
						    				</div>
						    				<div class="fee-plan">
						    					<span>{{ $membership[0]->membership_name }}</span>
						    					<br>
						    					<h1 style="font-size:18px;">${{ $membership[0]->cost }} / {{ $membership[0]->subscription_period }}</h1>
						    					<br>
						    					
						    				</div>
						    				<div class="freebtn">
						    					@if (!Auth::user())           
						    					<a href="/signup/{{ $membership[0]->id }}">SIGN UP</a>
						    					@else
						    						{!! Form::open(['action' => 'IndexController@payment_page', 'id' => 'form_data']) !!}
						    						<input type="hidden" name="user_name" value="{{ Auth::user()->name }}">
						    						<input type="hidden" name="membership_cost" value="{{ $membership[0]->cost }}">
						    						<input type="hidden" name="membership_name" value="{{ $membership[0]->membership_name }}">
						    						<input type="hidden" name="membership_id" value="{{ $membership[0]->id }}">
						    						{!! Form::submit( 'PURCHASE', ['class'=>'btn btn-success']) !!}
						    						{!! Form::close() !!}	
						    					{{-- <span><a class="purchase">PURCHASE</a></span> --}}
						    					@endif
						    				</div>
						    			</div>
						    		</div>
						    		<div class="col-lg-4">
						    			<div class="borderimage text-center">
						    				<div class="free">
						    					<span>{{ $membership[1]->cost }}/-</span>
						    					<h1>{{ $membership[1]->type }}</h1>
						    				</div>
						    				<div class="fee-plan">
						    					<span>{{ $membership[1]->membership_name }}</span>
						    					<br>
						    					<h1 style="font-size:18px;">${{ $membership[1]->cost }} / {{ $membership[1]->subscription_period }}</h1>
						    					<br>
						    					
						    				</div>
						    				<div class="freebtn">
						    					@if (!Auth::user())           
						    					<a href="/signup/{{ $membership[1]->id }}">SIGN UP</a>
						    					@else
					    							{!! Form::open(['action' => 'IndexController@payment_page', 'id' => 'form_data']) !!}
					    							<input type="hidden" name="user_name" value="{{ Auth::user()->name }}">
					    							<input type="hidden" name="membership_cost" value="{{ $membership[1]->cost }}">
					    							<input type="hidden" name="membership_name" value="{{ $membership[1]->membership_name }}">
					    							<input type="hidden" name="membership_id" value="{{ $membership[1]->id }}">
					    							{!! Form::submit( 'PURCHASE', ['class'=>'btn btn-success']) !!}
					    							{!! Form::close() !!}	
						    					@endif
						    				</div>
						    				</div>
						    		</div>
						    	@elseif($count == 3)
						    		<div class="col-lg-4">
						    			<div class="borderimage text-center">
						    				<div class="free">
						    					<span>{{ $membership[0]->cost }}/-</span>
						    					<h1>{{ $membership[0]->type }}</h1>
						    				</div>
						    				<div class="fee-plan">
						    					<span>{{ $membership[0]->membership_name }}</span>
						    					<br>
						    					<h1 style="font-size:18px;">${{ $membership[0]->cost }} / {{ $membership[0]->subscription_period }}</h1>
						    					<br>
						    					
						    				</div>
						    				<div class="freebtn">
						    					@if (!Auth::user())           
						    					<a href="/signup/{{ $membership[0]->id }}">SIGN UP</a>
						    					@else
						    						{!! Form::open(['action' => 'IndexController@payment_page', 'id' => 'form_data']) !!}
						    						<input type="hidden" name="user_name" value="{{ Auth::user()->name }}">
						    						<input type="hidden" name="membership_cost" value="{{ $membership[0]->cost }}">
						    						<input type="hidden" name="membership_name" value="{{ $membership[0]->membership_name }}">
						    						<input type="hidden" name="membership_id" value="{{ $membership[0]->id }}">
						    						{!! Form::submit( 'PURCHASE', ['class'=>'btn btn-success']) !!}
						    						{!! Form::close() !!}
						    					@endif
						    				</div>
						    			</div>
						    		</div>
						    		<div class="col-lg-4">
						    			<div class="borderimage text-center">
						    				<div class="free">
						    					<span>{{ $membership[1]->cost }}/-</span>
						    					<h1>{{ $membership[1]->type }}</h1>
						    				</div>
						    				<div class="fee-plan">
						    					<span>{{ $membership[1]->membership_name }}</span>
						    					<br>
						    					<h1 style="font-size:18px;">${{ $membership[1]->cost }} / {{ $membership[1]->subscription_period }}</h1>
						    					<br>
						    					
						    				</div>
						    				<div class="freebtn">
						    					@if (!Auth::user())           
						    					<a href="/signup/{{ $membership[1]->id }}">SIGN UP</a>
						    					@else
							    					{!! Form::open(['action' => 'IndexController@payment_page', 'id' => 'form_data']) !!}
							    					<input type="hidden" name="user_name" value="{{ Auth::user()->name }}">
							    					<input type="hidden" name="membership_cost" value="{{ $membership[1]->cost }}">
							    					<input type="hidden" name="membership_name" value="{{ $membership[1]->membership_name }}">
							    					<input type="hidden" name="membership_id" value="{{ $membership[1]->id }}">
							    					{!! Form::submit( 'PURCHASE', ['class'=>'btn btn-success']) !!}
							    					{!! Form::close() !!}
						    					@endif
						    				</div>
						    				</div>
						    		</div>
						    		<div class="col-lg-4">
						    			<div class="borderimage text-center">
						    				<div class="free">
						    					<span>{{ $membership[2]->cost }}/-</span>
						    					<h1>{{ $membership[2]->type }}</h1>
						    				</div>
						    				<div class="fee-plan">
						    					<span>{{ $membership[2]->membership_name }}</span>
						    					<br>
						    					<h1 style="font-size:18px;">${{ $membership[2]->cost }} / {{ $membership[2]->subscription_period }}</h1>
						    					<br>
						    					
						    				</div>
						    				<div class="freebtn">
						    					@if (!Auth::user())           
						    					<a href="/signup/{{ $membership[2]->id }}">SIGN UP</a>
						    					@else
						    						{!! Form::open(['action' => 'IndexController@payment_page', 'id' => 'form_data']) !!}
						    						<input type="hidden" name="user_name" value="{{ Auth::user()->name }}">
						    						<input type="hidden" name="membership_cost" value="{{ $membership[2]->cost }}">
						    						<input type="hidden" name="membership_name" value="{{ $membership[2]->membership_name }}">
						    						<input type="hidden" name="membership_id" value="{{ $membership[2]->id }}">
						    						{!! Form::submit( 'PURCHASE', ['class'=>'btn btn-success']) !!}
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