<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Topic Created</title>
</head>
<body>

	<h1>Topic created!</h1>

	<p>Hello {{ $topic->user->name }},</p>

	<p>
		Your new topic named 
		<strong>{{ $topic->name }}</strong> 
		has been created!
	</p>

	<p>Thank you!</p>

</body>
</html>