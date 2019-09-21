<?php
include('ApiCallClass.php');
use \API\Http\ApiCallClass;

session_start();


//tomamos los datos del login que vienen por post, en forma de arreglo 
$arr_params = array();
$arr_params = $_POST["session"];

//recorremos $arr_params para armar el string con los datos para enviar a la API
$str_params = "";
foreach($arr_params as $k => $v){
    $str_params .= "session[$k]=$v&";
}

//quitamos el ultimo & a la cadena de parametros ya que no debe ir
$str_params = substr($str_params, 0, -1);

//hacemos la llamada a la API para consultar si los datos corresponden a los de un usuario registrado
$curl = new ApiCallClass('POST', 'https://beta.id90travel.com/session.json', $str_params);

try {
    $login_result = $curl();
} catch (\RuntimeException $ex) {
    die(sprintf('Http error %s with code %d', $ex->getMessage(), $ex->getCode()));
}

//si se encontro un usuario con los datos enviados, se direcciona al formulario de busqueda. Si no, se retorna a la pantalla de login
if(isset($login_result["member"]["id"])){
    header("location:hotels_search.php");
}else{
    $_SESSION["is_logged"] = 1;
    header("location:login.php?err_mge=1");
}



