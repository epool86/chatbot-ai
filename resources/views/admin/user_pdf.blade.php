<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<style type="text/css">
		@page {
			margin: 0;
			padding: 0;
		}
	</style>
</head>
<body>

	<table style="width:100%" cellpadding="2" cellspacing="0" border="1">
		<tr style="background-color: #CCC;">
			<th>#</th>
			<th>Department</th>
			<th>User Name</th>
			<th>Email</th>
			<th>Status</th>
			<th>QR</th>
		</tr>
		@php($i = 0)
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
				<img src="data:image/png;base64,{{ $user->qr_code }}" width="100">
			</td>
		</tr>
		@endforeach
	</table>

</body>
</html>