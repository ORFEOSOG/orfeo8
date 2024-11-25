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

$x='0';
if(!$ruta_raiz) $ruta_raiz= "..";

include("$ruta_raiz/dbconfig.php");
include_once "$ruta_raiz/processConfig.php";
$db   = new ConnectionHandler("$ruta_raiz");

$rad = $_GET['rad'];
$nextval = $db->conn->nextId("sec_sgd_certificado");
$idCertificado = $nextval;
// consulta peticionario

if(!empty($dato6))
{
	$sql1="insert into sgd_certificado (id_certificado, radi_nume_radi, sgd_tipo_certificado, dato6, dato7, dato8, dato9, dato10, dato11) values ('$idCertificado','$rad','1','$dato6', '$dato7', '$dato8', '$dato9', '$dato10', '$dato11' )";
	$Rsql1=$db->conn->Execute($sql1);

	if($Rsql1)
	{
	    $x=1;
	    $mensajeA1='Correcto';
	    $mensajeA2= "'<p style='margin:0cm;font-size:16px;font-family:'Aptos',sans-serif;'><strong><span style='color:black;'>Datos guardados correctamente</span></strong></p>'";
	    $icon='success';
	}
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
<title> .:Datos Establecimiento:. </title>
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
			<h1 class="heading"> Datos del Establecimiento</h1>
                        <div class="form-group">
                        <table class="form-table">
                        <tr>
                        <td class="form-td">
                            <label class="control-label">Nombre Establecimiento</label>
                            <input name="dato6" type="text" class="form-control" />
                        </td>
                        <td class="form-td">
                            <label class="control-label">Nit.</label>
                            <input name="dato7" type="numeric" class="form-control" />
                        </td>
                        </tr>
                        <tr>
                        <td class="form-td">
                            <label class="control-label">Dirección Establecimiento</label>
                            <input name="dato8" type="text" class="form-control" />
                        </td>
                        </tr>
                        
                        
                        <tr>
                        <td class="form-td">
                            <label class="control-label">* Datos Contrato Mantenimiento de Camaras</label>
                        </td>
                        </tr>
                        <tr>
                        <td class="form-td">
                            <label class="control-label">Número Contrato</label>
                            <input name="dato9" type="text" class="form-control" />
                        </td>
                        <td class="form-td">
                            <label class="control-label">Nombre Establecimiento</label>
                            <input name="dato10" type="text" class="form-control" />
                        </td>
                        </tr>
                        <tr>
                        <td class="form-td">
                            <label class="control-label">Contrato vigente</label>
            			    <select name="dato11" class="form-control">
            			    <option value="1">Si</option>
            			    <option value="0" selected>No</option>
            			    </select>
                        </td>
                        </tr>
                        </table>

                            

                            <button type="submit" class="btn btn-default">Guardar <i class="fa fa-arrow-circle-right fa-2x"></i></button>
                        </div>
                        
                    </form>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
	var x1="<?php echo $x; ?>";
if (x1!=0)
{
 $(document).ready(function () {
                Swal.fire({
                    title: "<?php echo $mensajeA1; ?>",
                    html: "<?php echo $mensajeA2; ?>",
                    icon: "<?php echo $icon; ?>",
                    confirmButtonText: 'Entendido'
                });
            });
}

</script>
<a class="form-footer">Desarrollado por la Oficina TICs Comunicaciones Alcaldía de Sogamoso - 2024 </a>
                </div>
            </div>
        </div>
    </div>
    

</body>
</html>
