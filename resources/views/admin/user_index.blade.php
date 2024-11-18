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

		<div class="mb-3">
			<form method="GET" class="d-flex">

				<input type="text" name="search" class="form-control form-control-sm mr-2" 
				value="{{ $search }}">

				<select name="status" class="form-control form-control-sm mr-2" 
				onchange="this.form.submit()">
					<option value=""  @if($status == '')  selected @endif>ALL Status</option>
					<option value="1" @if($status == '1') selected @endif>Active</option>
					<option value="0" @if($status == '0') selected @endif>Inactive</option>
				</select>

				<button type="submit" class="btn btn-primary btn-sm">Submit</button>

			</form>
		</div>

		@if(session('message'))
			<div class="badge badge-success" style="padding:10px; width:100%; margin-bottom:10px;">
				{{ session('message') }}
			</div>
		@endif

		<table class="table table-bordered table-sm">
			<tr>
				<th>#</th>
				<th>Department</th>
				<th>User Name</th>
				<th>Email</th>
				<th>Status</th>
				<th>Action</th>
			</tr>
			@php($i = ($users->currentPage() - 1) * $users->perPage())
			@foreach($users as $user)
			<tr>
				<td>{{ ++$i }}</td>
				<td>
					@if($user->department) 
						{{ $user->department->name }}
					@endif
				</td>
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

		{{ $users->appends($_GET)->links() }}

	</div>
</div>
@endsection