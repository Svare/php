<?php

	require_once('display_error.php');
	require_once('connectPostgreSQL.php');

	$connection = new ConnectPostgreSQL();
	$connection->connect('127.0.0.1', '5432', 'becario', 'sins', 'hola123.,');

	if(!session_id()) {

		session_start();

		if(!empty($_POST)) {

			$status = $connection->validate($_POST['user'], hash("sha256", $_POST['password']));

			if($status) {
				$_SESSION['username'] = $_POST['user'];
				$_SESSION['hashPass'] = hash("sha256", $_POST['password']);
			}
		}
	}

	if(isset($_REQUEST['logout'])) {
		unset($_SESSION['access_token']);
		unset($_SESSION['info_user']);
		unset($_SESSION['username']);
		unset($_SESSION['hashPass']);
		session_unset();
		session_destroy();
	}

	if(!isset($_SESSION['username']) && !isset($_SESSION["access_token"]))
	  header("Location: index.php");

	#phpinfo();

?>

<br/>
<h1> Bienvenido </h1>
<br/>
<a href="<?php $_SERVER['PHP_SELF'] ?>?logout">Salir</a>
