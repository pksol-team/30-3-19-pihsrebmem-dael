@extends("la.layouts.app")

@section("contentheader_title", "Emails")
@section("contentheader_description", "Email listing")
{{-- @section("section", "Offers")
@section("sub_section", "Listing") --}}
@section("htmlheader_title", "Email Listing")


@section("main-content")

<div class="box box-success">
	<!--<div class="box-header"></div>-->
	<div class="box-body">
		<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
			
				<h4 class="modal-title" id="myModalLabel">Add Email</h4>
			</div>
			{!! Form::open(['action' => 'LA\OffersController@store', 'id' => 'offer-add-form']) !!}
			<div class="modal-body">
				<div class="box-body">
					<div class="form-group">
						<label for="membership_id">Select Option :</label>
						<select class="form-control select2-hidden-accessible email_option" data-placeholder="Enter Membership" rel="select2" name="membership_id" tabindex="-1" aria-hidden="true">
							<option value="">Select Option</option>
							<option value="1">Specific Member</option>
							<option value="2">For Membership</option>
							<option value="3">All Members</option>
						</select>
					</div>
					
					<div class="form-group option_1" style="display: none;">
						<label for="membership_id">Search Member By Email</label>
						<input class="form-control search_by_email" placeholder="Enter Email" data-rule-maxlength="256" required="1" name="specific_email" type="text" value="" aria-required="true">
					</div>

					<ul class="show_email_list">
					</ul>


					<div class="form-group option_2" style="display: none;">
						<label for="membership_id">Select Option :</label>
						<select class="form-control select2-hidden-accessible email_option" data-placeholder="Enter Membership" rel="select2" name="membership_id" tabindex="-1" aria-hidden="true">
							<option value="">Select Option</option>
							@foreach ($membership as $value)
								<option value="{{ $value->id }}">{{ $value->membership_name }}</option>
							@endforeach
						</select>
					</div>

					

            	<textarea name="question_ask" id="question_ask" cols="50" rows="100" class="question_ask form-control"></textarea>
                    
					
				</div>
			</div>
			<div class="modal-footer">
				
				{!! Form::submit( 'Send', ['class'=>'btn btn-success']) !!}
			</div>
			{!! Form::close() !!}
		</div>
	</div>
	
	</div>
</div>



@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>


{{-- ************************Summernote**************** --}}

<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>



<script>

	jQuery(document).ready(function($) {


		$('#question_ask').summernote({
		   placeholder: 'Type Your Question Here',
		   tabsize: 2,
		   height: 100

		});


		$(document).on('change', '.email_option', function(event) {
			event.preventDefault();
			var option = $(this).val();
			if(option == 1){

				$('div.option_1').show();
				$('div.option_2').hide();
			}
			else if(option == 2){
				$('div.option_2').show();
				$('div.option_1').hide();

			}
			else if(option == 3){
				$('div.option_2').hide();
				$('div.option_1').hide();
				
			}
		
		});

		$(document).on('keyup', '.search_by_email', function(event) {
			
			var email = $(this).val();

			$.ajax({
				url: '/get_email',
				type: 'POST',
				data: {
					email: email,
					_token: "{{ csrf_token() }}",
				},
			})
			.done(function(response) {
				$(".show_email_list").html(response);
			});
			
		});


		$(document).on('click', '.email_list', function(event) {
			event.preventDefault();
			var email = $(this).text();
			$(".show_email_list").hide();

			$('.search_by_email').val(email);

		});
	});
// $(function () {
// 	$("#example1").DataTable({
// 		processing: true,
//         serverSide: true,
//         ajax: "{{ url(config('laraadmin.adminRoute') . '/offer_dt_ajax') }}",
// 		language: {
// 			lengthMenu: "_MENU_",
// 			search: "_INPUT_",
// 			searchPlaceholder: "Search"
// 		},
// 		{{-- @if($show_actions)
// 		columnDefs: [ { orderable: false, targets: [-1] }],
// 		@endif --}}
// 	});
// 	$("#offer-add-form").validate({
		
// 	});
// });
</script>
@endpush
