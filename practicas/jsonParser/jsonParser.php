<?php

# JSON Parser
# Jesús Enrique Pacheco Franco
# UNAM - CERT
# Dic 20 2018 12:13 am

class JSONParser {

  private $stack;

  public function __construct() {
    $this->stack = array();
  }

  private function readKey(&$jsonStr) {

    /* Evalua que la llave venga entre comillas dobles, guarda la llave leida en keyBuffer y
        valida que despues de la llave vengan :, ignora espacios en blanco y deja la cadena
        jsonStr lista para ser tratada por readValue, el primer caracter de jsonStr sera el
        primer caracter del valor */

    # Se evalua que la cadena empiece con "

    #echo "<br/>??? ".$this->jsonStr."<br/>";

    if($jsonStr[0] != "\"")
      exit("<br/><br/>Error:: Las llaves de un json se especifican entre comillas dobles, se encontró: ".
            $jsonStr[0]."<br/><br/>");

    $key = substr($jsonStr, 1, strpos($jsonStr, "\"", 1) - 1); # Se guarda la llave

    # Eliminamos la llave de la cadena y la dejamos lista para evaluar si lo que sigue son dos puntos ":"

    $jsonStr = trim(substr($jsonStr, strpos($jsonStr, "\"", 1) + 1));

    # Se evalua si tenemos :

    if($jsonStr[0] == ":")
      $jsonStr = trim(substr($jsonStr, 1)); # Dejamos lista la cadena para la funcion readValue
    else
      exit("<br/><br/>Error:: Se esperaba \":\" despues de la llave json, se encontró: ".
            $jsonStr[0]."<br/><br/>");

    return $key;

  }

  private function readValue(&$jsonStr, &$sentinel, $array = false) {

    /* Esta funcion se encarga de entregar un valor como cadena de texto
        se ejecuta despues de readKey y el primer caracter de la cadena
        que recibe a la entrada es el primer caracter del valor a entregar
        entonces se evalua si el primer caracter de la cadena que se recibe es
        { ó [ se procede a leer caracter por caracter utilizando un algoritmo
        de pilas { ó [ se meten a la pila } ó ] provocan que se elimine un caracter
        de la pila, paramos de leer cuando la pila este vacia, en caso de que no
        empiece con estos simbolos ({ ó [) se lee hasta encontrar una , ó } y si
        se encontró un } al final quiere decir que terminamos de parsear la cadena
        y colocamos el valor de centinel en true. */

    $value = "";
    $index = 0;

    if($jsonStr[0] == "{" || $jsonStr[0] == "[") {

      do {

        $value .= $jsonStr[$index];

        if($jsonStr[$index] == "{" || $jsonStr[$index] == "[") {
          array_push($this->stack, $jsonStr[$index]);
        } elseif($jsonStr[$index] == "}") {
          if(array_pop($this->stack) != "{")
            exit("Error objeto indicado incorrectamente {...}<br/>");
        } elseif($jsonStr[$index] == "]") {
          if(array_pop($this->stack) != "[")
            exit("Error arreglo indicado incorrectamente [...]<br/>");
        }

        $index += 1;

      } while($this->stack);

    } else {

      while($jsonStr[$index] != "," && $jsonStr[$index] != "}" && $jsonStr[$index] != "]") {
        $value .= $jsonStr[$index];
        $index += 1;
      } // Fin while

    } // Fin if

    $jsonStr = trim(substr($jsonStr, $index));

    if($jsonStr[0] == "}" || ($array && $jsonStr[0] == "]")) {
        $sentinel = true;
    } elseif($jsonStr[0] == ",") {
      $jsonStr = trim(substr($jsonStr, 1));
    } else {
      exit("Error:: Se esperaba \",\" o \"}\"");
    }

    $value = trim($value);
    return $value;

  } // Fin readValue

  public function parse($json) {

    /* Esta funcion es el motor del programa, se encarga de validar que el json
        a parsear tenga venga entre corchetes, posteriormente se prepara la cadena
        para la funcion readKey y con el while se empieza a llamar a las fucniones
        readKey y readValue para ir creando el arreglo asociativo, la funcion readValue
        es la encargada de decidir cuando para el bucle por eso se le pasa el centinela
        y cuando esta detecta que se acabo lo pone en un valor true. */

    $jsonStr = trim($json);
    $sentinel = false;

    // Validamos que la cadena empiece con { y termine con }
    if($jsonStr[0] != '{' || $jsonStr[strlen($jsonStr) - 1] != '}')
      exit("<br/><br/>Error:: Se esperaba que la cadena empezara con \"{\" y terminara con \"}\"<br/><br/>");

    $jsonStr = trim(substr($jsonStr, 1));

    $jsonArray = array();

    while (!$sentinel) {
      $jsonArray[$this->readKey($jsonStr)] = $this->decodeValue($this->readValue($jsonStr, $sentinel));
    } // Fin while

    return $jsonArray;

  } // Fin parse

  private function parseArray($value) {

    /* Funciona de manera similar a parse solo que crea un areglo indexado
        por numeros en vez de un arreglo asociativo como lo hace parse. */

    $jsonStr = $value;
    $sentinel = false;

    // Validamos que la cadena empiece con { y termine con }
    if($jsonStr[0] != '[' || $jsonStr[strlen($jsonStr) - 1] != ']')
      exit("<br/><br/>Error:: Se esperaba que la cadena empezara con \"[\" y terminara con \"]\"<br/><br/>");

    $jsonStr = trim(substr($jsonStr, 1));

    $jsonArray = array();

    while (!$sentinel) {
      array_push($jsonArray, $this->decodeValue($this->readValue($jsonStr, $sentinel, true)));
    } // Fin while

    return $jsonArray;
  }

  private function decodeValue($value) {

    /* Esta funcion recibe como parametro el valor devuelto por la funcion
        readValue que es una cadena de caracteres y dependiendo de como este
        formada esa cadena va a retornar un cierto valor listo para ingresarlo
        al arreglo en donde estamos decodificando el json, aqui es donde se hace
        uso de la recursividad. */

    if($value[0] == "{")
      return $this->parse($value);
    elseif($value[0] == "[")
      return $this->parseArray($value);
    elseif($value[0] == "\"")
      return substr($value, 1, -1);
    elseif(is_numeric($value)){
      if(strpos($value, "."))
        return floatval($value);
      else
        return intval($value);
    }
    elseif($value == "true")
      return true;
    elseif($value == "false")
      return false;
    elseif($value == "null")
      return null;
  }

} // Fin class

// CÓDIGO //

$json = new JSONParser();

$x = $json->parse(file_get_contents('example.json'));
var_dump($x);
echo "<br/><br/>";
$x = $json->parse(file_get_contents('example2.json'));
var_dump($x);
echo "<br/><br/>";
$x = $json->parse(file_get_contents('example3.json'));
var_dump($x);
echo "<br/><br/>";


?>
