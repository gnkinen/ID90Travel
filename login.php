<?php 
include('ApiCallClass.php');
use \API\Http\ApiCallClass;

//obtenemos el listado de aerolines
//$arr_airlines = CallAPI("GET", "https://beta.id90travel.com/airlines", "");

$curl = new ApiCallClass("GET", "https://beta.id90travel.com/airlines", "");

try {
    $arr_airlines = $curl();
} catch (\RuntimeException $ex) {
    die(sprintf('Http error %s with code %d', $ex->getMessage(), $ex->getCode()));
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
  <title>Web Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>

<body>

  <div class="container">
    <h2>Formulario Login</h2>
    <?php if(isset($_GET["err_mge"]) && $_GET["err_mge"] == 1){ ?> 
      <div class="alert alert-danger alert-dismissible fade show">El Usuario y/o Contraseña no son correctos </div>
    <?php } ?> 

    <?php if(isset($_GET["err_mge"]) && $_GET["err_mge"] == 2){ ?> 
      <div class="alert alert-danger alert-dismissible fade show">La sesión caducó, deberá ingresar nuevamente. </div>
    <?php } ?> 

    <form action="process_login.php" method="post" class="needs-validation" novalidate>
      <div class="form-group">
      <label for="uname">Aerolínea:</label>

        <select name="session[airline]" class="form-control" required>
          <option value=""></option>
          <?php 
          foreach($arr_airlines as $airline){
            echo "<option value=\"{$airline[display_name]}\">{$airline[display_name]}</option>";
          }
          ?>
          
        </select>
        <div class="valid-feedback">Valid.</div>
        <div class="invalid-feedback">Debe seleccionar la Aerolínea.</div>
      </div>

      <div class="form-group">
        <label for="uname">Nombre Usuario:</label>
        <input type="text" class="form-control" id="uname" placeholder="Ingrese Usuario" name="session[username]" required>
        <div class="valid-feedback">Valid.</div>
        <div class="invalid-feedback">Debe completar el Usuario.</div>
      </div>
      <div class="form-group">
        <label for="pwd">Contraseña:</label>
        <input type="password" class="form-control" id="pwd" placeholder="Ingrese contraseña" name="session[password]" required>
        <div class="valid-feedback">Valid.</div>
        <div class="invalid-feedback">Debe completar la Contraseña.</div>
      </div>
      <input type="hidden" id="remeberme" name="session[remember_me]" value="1" >

      <button type="submit" class="btn btn-primary">Ingresar</button>
    </form>
  </div>

  <script>
    // Disable form submissions if there are invalid fields
    (function() {
      'use strict';
      window.addEventListener('load', function() {
        // Get the forms we want to add validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
          form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add('was-validated');
          }, false);
        });
      }, false);
    })();
  </script>

</body>

</html>