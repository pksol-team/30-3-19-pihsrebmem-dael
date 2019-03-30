@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/offers') }}">Offer</a> :
@endsection
@section("contentheader_description", $offer->$view_col)
@section("section", "Offers")
@section("section_url", url(config('laraadmin.adminRoute') . '/offers'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Offers Edit : ".$offer->$view_col)

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
				{!! Form::model($offer, ['route' => [config('laraadmin.adminRoute') . '.offers.update', $offer->id ], 'method'=>'PUT', 'id' => 'offer-edit-form']) !!}
					@la_form($module)
					
					{{--
					@la_input($module, 'membership_id')
					@la_input($module, 'file')
					--}}
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/offers') }}">Cancel</a></button>
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
	$("#offer-edit-form").validate({
		
	});
});
</script>
@endpush
