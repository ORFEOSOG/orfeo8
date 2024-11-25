<?php
/**
* @module index.php
*
* @author John J Sanchez   <cyberdyne.gnu@gmail.com>
* @license  GNU AFFERO GENERAL PUBLIC LICENSE
* @copyright

SIIM2 Models are the data definition of SIIM2 Information System

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
$x=0;
include "./TxFormulario.php";

if ($pasa=='1')
{
    $datosEnvio = "&rad=$rad&cod=$cod";
    header ("Location: ./dat_peticionario.php?$datosEnvio"); "";
}
?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<link rel="stylesheet" href="css/style.css">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<title> .:Formulario Zonas Seguras:. </title>
</head>

<body>
 <div class="demo form-bg">
        <div class="container-fluid">
            <div class="row text-center">
                <div class="col-lg-12">
                    <h1 class="heading"> Formulario Certificación Zonas Seguras </h1>
		    <h1 class="heading"> Oficina TICs Comunicaciones</h1>
                </div>
            </div>
	    <p>
	    <p>	

            <div class="row">
                <div class="col-md-offset-3 col-md-6">
                    <form action="./index.php" method="post">
			<h1 class="heading"> Ingrese la siguiente información</h1>
                        <div class="form-group">
			    <img src="./img/Stick.png" width="400" height="150"/>

                            <label class="control-label">Número de Radicado</label>
				
                            <input name="rad" type="numeric" class="form-control" />

                            <label class="control-label">Número de Cédula</label>
                            <input name="cod" type="text" class="form-control""/>

                            

                            <button name="envio" type="submit" class="btn btn-default">Enviar <i class="fa fa-arrow-circle-right fa-2x"></i></button>
                        </div>
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
	var x1="<?php echo $x; ?>";
if (x1!=0)
{
 $(document).ready(function () {
                Swal.fire({
                    title: 'Atención',
                    html: "<?php echo $mensaje; ?>",
                    icon: 'warning',
                    confirmButtonText: 'Entendido'
                });
            });
}

</script>
                    </form>
<a class="form-footer">Desarrollado por la Oficina TICs Comunicaciones Alcaldía de Sogamoso - 2024 </a>
                </div>

            </div>
        </div>
    </div>
    

</body>
</html>
