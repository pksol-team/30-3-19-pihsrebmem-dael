@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/memberships') }}">Membership</a> :
@endsection
@section("contentheader_description", $membership->$view_col)
@section("section", "Memberships")
@section("section_url", url(config('laraadmin.adminRoute') . '/memberships'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Memberships Edit : ".$membership->$view_col)

@section("main-content")

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="box">
	<div class="box-header">
		
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				{!! Form::model($membership, ['route' => [config('laraadmin.adminRoute') . '.memberships.update', $membership->id ], 'method'=>'PUT', 'id' => 'membership-edit-form']) !!}
					@la_form($module)
					
					{{--
					@la_input($module, 'membership_name')
					@la_input($module, 'type')
					@la_input($module, 'cost')
					@la_input($module, 'membership_level')
					@la_input($module, 'subscription_period')
					--}}
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/memberships') }}">Cancel</a></button>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
	$("#membership-edit-form").validate({
		
	});
});
</script>
@endpush
