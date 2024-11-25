<?php
/**
* @module TxFormulario
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

$pasa='0';
if(!$ruta_raiz) $ruta_raiz= "..";

include("$ruta_raiz/dbconfig.php");
include_once "$ruta_raiz/processConfig.php";
$db   = new ConnectionHandler("$ruta_raiz");

$rad = $_POST['rad'];
$cod = $_POST['cod'];

if(!empty($rad))
{
	$sql= "select r.radi_nume_radi from radicado r inner join sgd_dir_drecciones d on d.radi_nume_radi='$rad' where d.radi_nume_radi = r.radi_nume_radi";
	$rsql=$db->query($sql);
		
		if (!$rsql->EOF)
		{
		    $pasa ='1';
		    $_SESSION['rad']=$rad;
		    $_SESSION['cod']=$cod;
		    
		    
		}
		else
		{
		    $mensaje ="'<p style='margin:0cm;font-size:16px;font-family:'Aptos',sans-serif;'><strong><span style='color:black;'>Datos Incorrectos</span></strong></p>'";
		    $x=1;
		    
		}
}
?>
