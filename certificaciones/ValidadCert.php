<?php
/**
* @module ValidaCert.php
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
foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

$x='0';

if(!$ruta_raiz) $ruta_raiz= "..";
include("$ruta_raiz/dbconfig.php");
include_once "$ruta_raiz/processConfig.php";

$db   = new ConnectionHandler("$ruta_raiz");

$ValRad="select radi_nume_radi from radicado where radi_nume_radi=$rad and sgd_rad_codigoverificacion=$CodValida";
$Rvalida=$db->query($ValRad);

if ($Rvalida)
	{
		$x=1;
	    $mensajeA1='Correcto';
	    $mensajeA2= "'<p style='margin:0cm;font-size:16px;font-family:'Aptos',sans-serif;'><strong><span style='color:black;'>Certificado es Valido</span></strong></p>'";
	    $icon='success';
	}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">

<title> .:Datos Establecimiento:. </title>
</head>

<body>
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
</body>
</html>
