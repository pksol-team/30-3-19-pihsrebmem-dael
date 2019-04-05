@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/email_templates') }}">Email Template</a> :
@endsection
@section("contentheader_description", $email_template->$view_col)
@section("section", "Email Templates")
@section("section_url", url(config('laraadmin.adminRoute') . '/email_templates'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Email Templates Edit : ".$email_template->$view_col)

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
				{!! Form::model($email_template, ['route' => [config('laraadmin.adminRoute') . '.email_templates.update', $email_template->id ], 'method'=>'PUT', 'id' => 'email_template-edit-form']) !!}
					{{-- @la_form($module) --}}
					
					{{--
					@la_input($module, 'options')
					@la_input($module, 'email_content')
					@la_input($module, 'subject')
					--}}

					@la_input($module, 'options')

					@la_input($module, 'subject')

					@la_input($module, 'email_content', '', '', 'form-control summernote_description')
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/email_templates') }}">Cancel</a></button>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection

@push('scripts')

<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>

<script>
$(function () {
	$("#email_template-edit-form").validate({
		
	});
	$('.summernote_description').summernote();
	
});
</script>
@endpush
