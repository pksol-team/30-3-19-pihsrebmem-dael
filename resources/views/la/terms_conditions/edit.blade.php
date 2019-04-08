@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/terms_conditions') }}">Terms Condition</a> :
@endsection
@section("contentheader_description", $terms_condition->$view_col)
@section("section", "Terms Conditions")
@section("section_url", url(config('laraadmin.adminRoute') . '/terms_conditions'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Terms Conditions Edit : ".$terms_condition->$view_col)

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
				{!! Form::model($terms_condition, ['route' => [config('laraadmin.adminRoute') . '.terms_conditions.update', $terms_condition->id ], 'method'=>'PUT', 'id' => 'terms_condition-edit-form']) !!}
					@la_form($module)
					
					{{--
					@la_input($module, 'file')
					--}}
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/terms_conditions') }}">Cancel</a></button>
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
	$("#terms_condition-edit-form").validate({
		
	});
});
</script>
@endpush
