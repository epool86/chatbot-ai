@extends('layouts.template')

@section('header')
<h1 class="h3 mb-0 text-gray-800">Chat</h1>
<a href="{{ route('topic.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
	<i class="fas fa-plus fa-sm text-white-50"></i> Back to Topic
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

		<div id="chatbot" style="height: 400px; overflow-y: auto; margin-bottom: 20px;">
			@foreach($chats as $chat)

				<!-- User Message -->
				<div class="mb-3">
					<div class="bg-primary text-white rounded p-3">
						{{ $chat->user_message }}
					</div>
					<div class="text-right">
						<small>Sent at {{ $chat->created_at }}</small>
					</div>
				</div>

				<!-- AI Response -->
				<div class="mb-3">
					<div class="bg-light rounded p-3">
						{{ $chat->assistant_message }}
					</div>
					<div class="text-right">
						<small>AI Response</small>
					</div>
				</div>

			@endforeach
		</div>

		<form method="POST" action="{{ route('chat.store', $topic->id) }}">
			@csrf
			<div class="input-group">
				<input type="text" name="message" class="form-control">
				<div class="input-group-append">
					<button class="btn btn-primary" type="submit">Send</button>
				</div>
			</div>
		</form>

	</div>
</div>

<script type="text/javascript">
	
	function autoScroll() {
		const chatbot = document.getElementById('chatbot');
		if(chatbot){
			chatbot.scrollTop = chatbot.scrollHeight;
		}
	}

	document.addEventListener('DOMContentLoaded', function(){
		autoScroll();
	});


</script>
@endsection