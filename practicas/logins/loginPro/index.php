<?php

	require_once('display_error.php');
	require_once('/var/www/google-api-php-client-2.2.2/vendor/autoload.php');

	if(!session_id()) {
		session_start();
	}

	/* El username y el access_token son los que controlan la sesion si tienen
			valor significa que hay una sesion activa y por eso redirigimos la pagina */

	if(isset($_SESSION['username']) || isset($_SESSION['access_token']))
		header("Location: ingresar.php");

	$clientId = '353775224261-3jj195i8ii8bgvefik0fruud6iecpvg7.apps.googleusercontent.com';
	$clientSecret = 'uiiE98dfQkzgHZpWqDqJgD_V';
	$redirectURL = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

	$client = new Google_Client();
	$client->setApplicationName("Cliente web 1");
	$client->setClientId($clientId);
	$client->setClientSecret($clientSecret);
	$client->setRedirectUri($redirectURL);
	$client->setScopes(array(Google_Service_Plus::PLUS_ME));
	$plus = new Google_Service_Plus($client);

	# Lo del if se ejecuta cuando hacemos la redireccion

	if(isset($_GET['code'])){
		if(strval($_SESSION['state']) !== strval($_GET['state'])) {
			echo 'el estado de la sesion no coincide.';
			exit(1);
		}
		$client->authenticate($_GET['code']);
		$_SESSION['access_token'] = $client->getAccessToken();
		$_SESSION['info_user'] = $plus->people->get('me');
		header('Location: ingresar.php');
	}

	if (isset($_SESSION['access_token'])) {
		$client->setAccessToken($_SESSION['access_token']);
	}

	$state = mt_rand();
	$client->setState($state);
	$_SESSION['state'] = $state;
	$hReflogin = $client->createAuthUrl();

?>

<!DOCTYPE html>
	<html>
		<head>
			<title>Sins</title>
		</head>
		<body>
			<form method="POST" action="ingresar.php">
				<p>User: </p>
				<input type="text" name="user">
				<p>Password: </p>
				<input type="password" name="password">
				<br/>
				<br/>
				<input type="submit" name="form" value="Sign in">
			</form>

			<form method="POST" action="registerError.php">
				<p>User: </p>
				<input type="text" name="user">
				<p>Password: </p>
				<input type="password" name="password">
				<br/>
				<br/>
				<input type="submit" name="form" value="Register">
			</form>

			<br/>

			<a href="<?php echo $hReflogin;?>" >
				Sign in with Google
			</a>

		</body>
	</html>
