@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/upload_offers') }}">Upload Offer</a> :
@endsection
@section("contentheader_description", $upload_offer->$view_col)
@section("section", "Upload Offers")
@section("section_url", url(config('laraadmin.adminRoute') . '/upload_offers'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Upload Offers Edit : ".$upload_offer->$view_col)

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
				{!! Form::model($upload_offer, ['route' => [config('laraadmin.adminRoute') . '.upload_offers.update', $upload_offer->id ], 'method'=>'PUT', 'id' => 'upload_offer-edit-form']) !!}
					@la_form($module)
					
					{{--
					@la_input($module, 'membership_id')
					@la_input($module, 'file')
					--}}
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/upload_offers') }}">Cancel</a></button>
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
	$("#upload_offer-edit-form").validate({
		
	});
});
</script>
@endpush
