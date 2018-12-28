<?php

	require_once('display_error.php');

	class ConnectPostgreSQL {

		private $dbConnection;

		public function connect($host, $port, $dbName, $user, $password) {
			$this->dbConnection = pg_connect("host=$host port=$port dbname=$dbName user=$user password=$password") or die ("Could not connect.");
			#echo "Successful Connection <br/>";
		}

		public function insert($user, $password) {
			return pg_insert($this->dbConnection, 'becario', array("name" => $user, "password" => hash("sha256", $password)));
		}

		public function validate($user, $password) {
			$result = pg_query_params($this->dbConnection, 'SELECT * FROM becario WHERE name=$1 AND password=$2',
																array($user, $password)) or die ("There was a mistake<br/>");
			$row = pg_fetch_assoc($result);
			return !empty($row);
		}

	}

	#phpinfo();

	$connection = new ConnectPostgreSQL();
	$connection->connect('127.0.0.1', '5432', 'becario', 'sins', 'hola123.,');

	if($_POST['form'] === 'Sign in') {

		if($connection->validate($_POST['user'], hash("sha256", $_POST['password']))) {
			header('Location: welcome.php');
		} else {
			echo "<br/><h1> ACCESO DENEGADO </h1><br/>";
		};

	} elseif ($_POST['form'] === 'Register') {

		$status = $connection->insert($_POST['user'], $_POST['password']);

		if($status) {
			echo "User has been registered.<br/>";
		} else {
			echo "Valió Barriga<br/>";
		}

	} else {
		echo "How did you get in here?<br/>";
	}

?>
