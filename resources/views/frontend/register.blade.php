@extends('frontend.template.layout')
@section('title') <?= $title; ?> @stop
@section('content')

<?php use App\Http\Controllers\Frontend\IndexController; ?>
<?php use Illuminate\Support\Collection; ?>



	<div class="col-xs-12 col-md-12">
	   <!-- user log form -->
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
	</div>

	
@endsection