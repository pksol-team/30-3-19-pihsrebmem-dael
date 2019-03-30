@extends('frontend.template.layout')
@section('title') <?= $title; ?> @stop
@section('content')

<?php use App\Http\Controllers\Frontend\IndexController; ?>
<?php use Illuminate\Support\Collection; ?>

 @if(session()->has('message'))
                            <div class="alert alert-info">
                                {!! session()->get('message') !!}
                            </div>
                        @endif



	<form method="post" action="/frontend/login_check" class="user-log-form">
	   <input type="hidden" name="_token" value="{{ csrf_token() }}">
	   <h2>Login Form</h2>
	   <div class="form-group">
	      <input value="{{ old('email') }}" name="email" type="email" class="form-control element-block" placeholder="Email address *" required>
	   </div>
	   <div class="form-group">
	      <input title="6 characters Minimum" minlength="6" name="password" type="password" class="form-control element-block" placeholder="Password *" required>
	   </div>
	   <div class="btns-wrap">
	      <div class="wrap">
	         <button type="submit" class="btn btn-theme btn-warning fw-bold font-lato text-uppercase">Login</button>
	      </div>
	   </div>
	</form>

	
@endsection