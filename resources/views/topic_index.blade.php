@extends('layouts.template')

@section('header')
<h1 class="h3 mb-0 text-gray-800">Manage Topics</h1>
<a href="{{ route('topic.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
	<i class="fas fa-plus fa-sm text-white-50"></i> Add New Topic
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
				<th>Name</th>
				<th>Description</th>
				<th>Status</th>
				<th>Action</th>
			</tr>
			@php($i = 0)
			@foreach($topics as $topic)
			<tr>
				<td>{{ ++$i }}</td>
				<td>{{ $topic->name }}</td>
				<td>{{ $topic->description }}</td>
				<td>
					@if($topic->status == 1)
						Active
					@else
						Inactive
					@endif
				</td>
				<td>
					<form method="POST" action="{{ route('topic.destroy', $topic->id) }}" 
						onsubmit="return confirm('Are you sure?');">
						<input type="hidden" name="_method" value="DELETE">
						@csrf
						<a href="{{ route('topic.edit', $topic->id) }}" class="btn btn-primary btn-sm">Edit</a>
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