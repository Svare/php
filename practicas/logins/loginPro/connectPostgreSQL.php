<?php

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

  public function checkUser($user) {
    $result = pg_query_params($this->dbConnection, 'SELECT * FROM becario WHERE name=$1',
                              array($user)) or die ("There was a mistake<br/>");
    $row = pg_fetch_assoc($result);
    return !empty($row);
  }

}

?>
