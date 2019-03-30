@extends('frontend.template.layout')
@section('title') <?= $title; ?> @stop
@section('content')

<?php use App\Http\Controllers\Frontend\IndexController; ?>
<?php use Illuminate\Support\Collection; ?>

<a href="{{ url('/logout') }}" class="btn btn-default btn-flat">Sign out</a>

<div class="upgrade_account" style="display: none;">
	<span class="close_upgrade">x</span>
	<p>Upgrade your system</p> 
	@foreach ($membership as $package)
		<a href="#">{{ $package->membership_name }}</a> : {{ $package->cost }} <br>
	@endforeach
</div>
<br>
@php	
		$user_memebership = Auth::user()->membership_id;
		$user_memebership_level = $membership_level;
@endphp
<table>
	<tr>
		<th>ID</th>
		<th>File</th>
		<th>Membership Level</th>
	</tr>
	<tbody>
		@foreach ($files as $list)
			<tr>
				<td>{{ $list->id }}</td>
				@php
					$get_file = DB::table('uploads')->where('id', $list->file)->whereNull('deleted_at')->first();
					if($get_file){						
						$name = $get_file->name;
						$hash = $get_file->hash;
						$link = 'files/'.$hash.'/'.$name;
					}	
			if($list->membership_id != Auth::user()->membership_id){
				
				if($membership_level >= $list->memberships->membership_level){
					@endphp
						<td><a href ="{{ $link }}"> {{ $name }}</a></td>

					@php
						

				}


				@endphp	

				<td><a class="upgrade"> {{ $name }} </a></td>

				@php
					}	else{												
						@endphp
						<td><a href ="{{ $link }}"> {{ $name }}</a></td>
						
						@php
							
					}

						@endphp
	
						<td>{{ $list->memberships->membership_level }}</td>
		
			</tr>
		@endforeach	

		
	</tbody>
</table>




	
@endsection