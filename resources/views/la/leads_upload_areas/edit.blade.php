@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/leads_upload_areas') }}">Leads Upload Area</a> :
@endsection
@section("contentheader_description", $leads_upload_area->$view_col)
@section("section", "Leads Upload Areas")
@section("section_url", url(config('laraadmin.adminRoute') . '/leads_upload_areas'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Leads Upload Areas Edit : ".$leads_upload_area->$view_col)

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
				{!! Form::model($leads_upload_area, ['route' => [config('laraadmin.adminRoute') . '.leads_upload_areas.update', $leads_upload_area->id ], 'method'=>'PUT', 'id' => 'leads_upload_area-edit-form']) !!}
					@la_form($module)
					
					
					{{-- @la_input($module, 'membership_id')
					@la_input($module, 'file_name')
					@la_input($module, 'file') --}}
					
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/leads_upload_areas') }}">Cancel</a></button>
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
	$("#leads_upload_area-edit-form").validate({
		
	});
});
</script>
@endpush
