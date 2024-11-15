@extends('layouts.template')

@section('header')
<h1 class="h3 mb-0 text-gray-800">Manage Users</h1>
<a href="{{ route('user.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
	<i class="fas fa-plus fa-sm text-white-50"></i> Add New User
</a>
@endsection

@section('content')
<div class="card">
	<div class="card-body">

		@if(session('message'))
			<div class="badge badge-success" style="padding:10px; width:100%; margin-bottom:10px;">
				{{ session('message') }}
			</div>
		@endif

		<table class="table table-bordered table-sm">
			<tr>
				<th>#</th>
				<th>User Name</th>
				<th>Email</th>
				<th>Status</th>
				<th>Action</th>
			</tr>
			@php($i = 0)
			@foreach($users as $user)
			<tr>
				<td>{{ ++$i }}</td>
				<td>{{ $user->name }}</td>
				<td>{{ $user->email }}</td>
				<td>
					@if($user->status == 1)
						Active
					@else
						Inactive
					@endif
				</td>
				<td>
					<form method="POST" action="{{ route('user.destroy', $user->id) }}" 
						onsubmit="return confirm('Are you sure?');">
						<input type="hidden" name="_method" value="DELETE">
						@csrf
						<a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary btn-sm">Edit</a>
						<button type="submit" class="btn btn-danger btn-sm">
							Delete
						</button>
					</form>
				</td>
			</tr>
			@endforeach
		</table>
	</div>
</div>
@endsection