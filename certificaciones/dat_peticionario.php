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
session_start();
foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

$pasa='0';
if(!$ruta_raiz) $ruta_raiz= "..";

include("$ruta_raiz/dbconfig.php");
include_once "$ruta_raiz/processConfig.php";
$db   = new ConnectionHandler("$ruta_raiz");

$rad = $_GET['rad'];

// actualiza establecimiento
$sql1="select sgd_dir_nomremdes as Nombre, sgd_dir_doc as Cedula, sgd_dir_direccion as Direccion,sgd_dir_telefono as Telefono, sgd_dir_mail as email from radicado r inner join sgd_dir_drecciones d on d.radi_nume_radi= '$rad'  where r.radi_nume_radi=d.radi_nume_radi and d.sgd_dir_doc='$cod'";
$Rsql1=$db->query($sql1);

$Nombre=$Rsql1->fields["NOMBRE"];
$Cedula=$Rsql1->fields["CEDULA"];
$Direccion=$Rsql1->fields["DIRECCION"];
$Telefono=$Rsql1->fields["TELEFONO"];
$Email=$Rsql1->fields["EMAIL"];
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
<title> .:Datos Peticionario:. </title>
</head>

<body>
 <div class="demo form-bg">
        <div class="container-fluid">
            <div class="row text-center">
                <div class="col-lg-12">
                 
                </div>
            </div>
	    <p>
	    <p>	

            <div class="row">
                <div class="col-md-offset-3 col-md-6">
                    <form action="./dat_establecimiento.php?&rad=<?php echo $rad;?>" method="post">
			<h1 class="heading"> Datos del Peticionario</h1>
                        <div class="form-group">
                        <table class="form-table">
                        <tr>
                        <td class="form-td">
                            <label class="control-label">Nombre Peticionario</label>
                            <label class="form-control"><?php echo $Nombre;?></label>
                        </td>
                        <td class="form-td">
                            <label class="control-label">Número Identificación</label>
                            <label class="form-control"><?php echo $Cedula;?></label>
                        </td>
                        </tr>
                        
                        <tr>
                        <td class="form-td">
                            <label class="control-label">Dirección</label>
                            <label class="form-control"><?php echo $Direccion;?></label>
                        </td>
                        <td class="form-td">
                            <label class="control-label">Número Telefónico</label>
                            <label class="form-control"><?php echo $Telefono;?></label>
                        </td>
                        </tr>
                        
                        <tr>
                        <td class="form-td">
                            <label class="control-label">Email</label>
                            <label class="form-control"><?php echo $Email;?></label>
                        </td>
                        </tr>
                        </table>

                            

                            <button name="envio" type="submit" class="btn btn-default">Siguiente <i class="fa fa-arrow-circle-right fa-2x"></i></button>
                        </div>
                        
                    </form>
<a class="form-footer">Desarrollado por la Oficina TICs Comunicaciones Alcaldía de Sogamoso - 2024 </a>
                </div>
<?php if(!empty($mensaje)){ ?>
                  <a class="form-footer" >
                    <?=$mensaje?>
                  <a>
                  <?php }?>
            </div>
        </div>
    </div>
    

</body>
</html>
