@extends('layouts.template')

@section('header')
<h1 class="h3 mb-0 text-gray-800">Document Details</h1>
<a href="{{ route('document.index', $topic->id) }}" 
class="btn btn-sm btn-primary">
	<i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
</a>
@endsection

@section('content')
<div class="card">
	<div class="card-body">

		@if($document->id)
			@php($route = route('document.update', [$topic->id, $document->id]))
			@php($method = 'PUT')
		@else
			@php($route = route('document.store', [$topic->id]))
			@php($method = 'POST')
		@endif
		
		<form method="POST" action="{{ $route }}" enctype="multipart/form-data">
			<input type="hidden" name="_method" value="{{ $method }}">
			@csrf

			<div class="form-group">
				<label>Name</label>
				<input type="text" name="name" id="name" class="form-control bg-light" 
				value="{{ old('name', $document->name) }}">
				@error('name')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>

			<div class="form-group">
				<label>Description</label>
				<textarea name="description" id="description" class="form-control bg-light" rows="5">{{ old('description', $document->description) }}</textarea>
				@error('description')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>

			<div class="form-group">
				<label>Select File</label>
				<input type="file" name="file" id="file" class="form-control bg-light" 
				value="{{ old('file', $document->file) }}">
				@error('file')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>

			<div class="form-group">
				<label>Status</label>
				<select name="status" id="status" class="form-control bg-light">
					<option value="1" @if(old('status', $document->status) == 1) selected @endif>Active</option>
					<option value="0" @if(old('status', $document->status) == 0) selected @endif>Inactive</option>
				</select>
				@error('status')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>

			<div class="form-group">
				<a href="{{ route('document.index', $topic->id) }}" class="btn btn-danger">
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