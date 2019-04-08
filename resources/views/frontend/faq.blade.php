@extends('frontend.template.layout')
@section('title') <?= $title; ?> @stop
@section('content')

<?php use App\Http\Controllers\Frontend\IndexController; ?>
<?php use Illuminate\Support\Collection; ?>

@section('class') {{ 'class=subscription' }} @stop

<div class="body_wrapper">
        <div class="container-fluid">
           <div class="row mid-banner-table">
			<div class="container">
				<h1 style="font-size: 25px; margin-bottom: 3%;">FAQ</h1>

				<!--Accordion wrapper-->
				<div class="accordion md-accordion" id="accordionEx1" role="tablist" aria-multiselectable="true">
				

				@foreach ($fetch_faq as $key => $faq)
					<div class="card">

						<!-- Card header -->
						<div class="card-header" role="tab" id="headingTwo{{ $key }}">
						  <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx1" href="#collapseTwo{{ $key }}"
						    aria-expanded="false" aria-controls="collapseTwo{{ $key }}">
						    <h5 class="mb-0">
						    	{{ $faq->question}}
						    	<i class="fas fa-angle-down rotate-icon"></i>
						    </h5>
						  </a>
						</div>

						<!-- Card body -->
						<div id="collapseTwo{{ $key }}" class="collapse" role="tabpanel" aria-labelledby="headingTwo{{ $key }}"
						  data-parent="#accordionEx1">
						  <div class="card-body">{!! $faq->answer !!}</div>
						</div>					    

				  	</div>				
				@endforeach
				  
			</div>
		</div>
	</div>
</div>

	
@endsection