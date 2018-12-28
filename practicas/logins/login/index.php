<?php

	require_once('display_error.php');

?>

<!DOCTYPE html>
	<html>
		<head>
			<title>Sins</title>
		</head>
		<body>
			<form method="POST" action="dbConnection.php">
				<p>User: </p>
				<input type="text" name="user">
				<p>Password: </p>
				<input type="text" name="password">
				<br/>
				<br/>
				<input type="submit" name="form" value="Sign in">
			</form>

			<form method="POST" action="dbConnection.php">
				<p>User: </p>
				<input type="text" name="user">
				<p>Password: </p>
				<input type="text" name="password">
				<br/>
				<br/>
				<input type="submit" name="form" value="Register">
			</form>
		</body>
	</html>
