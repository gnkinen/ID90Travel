<?php
include('ApiCallClass.php');
use \API\Http\ApiCallClass;

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();
//verificamos si el usuario esta logueado
if (!isset($_SESSION["is_logged"]) || $_SESSION["is_logged"] != 1) {
   header("location:login.php?err_mge=2");
}

$search_result = array();
//si se envian datos por post, proceso la busqueda
if ($_POST) {
  //tomamos los datos de los filtros que vienen por post, en forma de arreglo 
  $arr_params = array();
  $arr_params = $_POST["filter"];

  //creamos la cadena de parametros para enviar por GET
  $str_params = http_build_query($arr_params);
  
  //hacemos la llamada a la API para realizar la busqueda de hoteles segun el criterio de busqueda  
  $curl = new ApiCallClass("GET", "https://beta.id90travel.com/api/v1/hotels.json", $arr_params);

  try {
      $arr_airlines = $curl();
  } catch (\RuntimeException $ex) {
      die(sprintf('Http error %s with code %d', $ex->getMessage(), $ex->getCode()));
  }  
}

// print_r($arr_airlines);
// exit;
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">


  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
  <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

  <!-- Date range picker plug ins -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css" />
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.standalone.min.css">

  <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
  <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />

  <script type="text/javascript">
    $("#fechas").datepicker('clearDates');
  </script>

  <title></title>

  <script type="text/javascript">
    $(document).ready(function() {      
      $('#checkin').datepicker();
      $('#checkout').datepicker();
    });
  </script>

</head>

<body>

  <div class="container">
    <h2>Búsqueda de Hoteles</h2>
    <p></p>
    <p></p>
    <p></p>
    <div class="row">
      <div class="card">
        <div class="card-body">

          <form class="form-inline" method="post" action="#">
            <label for="destino" class="mr-sm-2">Destiono:</label>
            <input type="textbox" name="filter[destination]" class="form-control mb-2 mr-sm-2" >
            <label for="checkin" class="mr-sm-2">&nbsp;&nbsp;Check In:</label>
            <input type="textbox" name="filter[checkin]" id="checkin" class="form-control mb-2 mr-sm-2 w-5" style="width: 95px;">
            <label for="checkout" class="mr-sm-2">&nbsp;&nbsp;Check Out:</label>
            <input type="textbox" name="filter[checkout]" id="checkout" class="form-control mb-2 mr-sm-2" style="width: 125px;">
            <label for="cant_invitados" class="mr-sm-2"> &nbsp;&nbsp;Cantidad Invitados:</label>
            <input type="textbox" name="filter[guests]" class="form-control mb-2 mr-sm-2"  style="width: 80px;">
            
            <input type="hidden" name="filter[page]" value="1">
            <input type="hidden" name="filter[per_page]" value="20">
            <input type="hidden" name="filter[sort_orden]" value="asc">
            <input type="hidden" name="filter[sort_criteria]" value="Overall">
            

            <button type="submit" class="btn btn-primary mb-2">Buscar</button>
          </form>

        </div>
      </div>
      


      <div class="row p-3">

        <table class="table table-striped">
          <thead>
            <tr>
              <th>Invitados</th>
              <th>Check In</th>
              <th>Check Out</th>
              <th>Destino</th>
              <th>Habitaciones</th>
              <th>Moneda</th>
              <th>Precio Min.</th>
              <th>Precio Max.</th>
            </tr>
            <?php 
                foreach ($search_result as $k => $v) {
            ?>
            <tr>
              <td><?php $v['guests'] ?></td>
              <td><?php $v['checkin'] ?></td>
              <td><?php $v['checkout'] ?></td>
              <td><?php $v['destination'] ?></td>
              <td><?php $v['rooms'] ?></td>
              <td><?php $v['currency'] ?></td>
              <td><?php $v['high_price'] ?></td>
              <td><?php $v['low_price'] ?></td>              
            </tr>
            <?php 
                }
            ?>
          </thead>
          <tbody>
            
          </tbody>
        </table>
      </div>
</body>

</html>