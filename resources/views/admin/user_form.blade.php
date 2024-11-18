@extends('layouts.template')

@section('header')
<h1 class="h3 mb-0 text-gray-800">User Details</h1>
<a href="{{ route('user.index') }}" 
class="btn btn-sm btn-primary">
	<i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
</a>
@endsection

@section('content')
<div class="card">
	<div class="card-body">

		@if($user->id)
			@php($route = route('user.update', $user->id))
			@php($method = 'PUT')
		@else
			@php($route = route('user.store'))
			@php($method = 'POST')
		@endif
		
		<form method="POST" action="{{ $route }}">
			<input type="hidden" name="_method" value="{{ $method }}">
			@csrf

			<div class="form-group">
				<label>Name</label>
				<input type="text" name="name" id="name" class="form-control bg-light" 
				value="{{ old('name', $user->name) }}">
				@error('name')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>

			<div class="form-group">
				<label>Email</label>
				<input type="text" name="email" id="email" class="form-control bg-light" autocomplete="new-email" value="{{ old('email', $user->email) }}">
				@error('email')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>

			<div class="form-group">
				<label>Password</label>
				<input type="password" name="password" id="password" class="form-control bg-light" autocomplete="new-password">
				@error('password')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>

			<div class="form-group">
				<label>Password Confirm</label>
				<input type="password" name="password_confirmation" id="password_confirmation" class="form-control bg-light">
				@error('password_confirmation')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>

			<div class="form-group">
				<label>Department</label>
				<select name="department_id" id="department_id" class="form-control bg-light">
					@foreach(App\Models\Department::all() as $department)
					<option value="{{ $department->id }}" 
						@if(old('deparment_id', $user->department_id) == $department->id) selected @endif>
						{{ $department->name }}
					</option>
					@endforeach
				</select>
				@error('department_id')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>

			<div class="form-group">
				<label>Status</label>
				<select name="status" id="status" class="form-control bg-light">
					<option value="1" @if(old('status', $user->status) == 1) selected @endif>Active</option>
					<option value="0" @if(old('status', $user->status) == 0) selected @endif>Inactive</option>
				</select>
				@error('status')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>

			<div class="form-group">
				<a href="{{ route('user.index') }}" class="btn btn-danger">
					Cancel
				</a>
				<button type="reset" class="btn btn-info">
					Reset
				</button>
				<button type="submit" class="btn btn-primary">
					Submit
				</button>
			</div>

		</form>

	</div>
</div>
@endsection