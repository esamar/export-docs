<!DOCTYPE html>
<html lang="es">
<head>
	<title></title>
</head>
<body>
	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>Nombre</th>
				<th>Email</th>
			</tr>
		</thead>
		<tbody>
			@foreach($users as $user )
			<tr>			
				<td>{{ $user->id }}</td>
				<td>{{ $user->name }}</td>
				<td>{{ $user->email }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</body>
</html>