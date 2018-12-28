<?php

require_once('connectPostgreSQL.php');

$connection = new ConnectPostgreSQL();
$connection->connect('127.0.0.1', '5432', 'becario', 'sins', 'hola123.,');

if (isset($_POST['form']) && $_POST['form'] === 'Register') {

  if($connection->checkUser($_POST['user'])) {
    $usr = $_POST['user'];
    echo "Error el usuario: <strong> $usr </strong> ya ha sido registrado.";
  } else {

    $status = $connection->insert($_POST['user'], $_POST['password']);

    if($status) {
      echo "User has been registered.<br/>";
    } else {
      echo "Valió Barriga.<br/>";
    }
  }
}

echo "<br/><br/>";

?>

<a href="index.php">Regresar</a>
