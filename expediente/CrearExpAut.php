<?php
/**
* @module session_orfeo
*
* @author Jairo sanchez   <cyberdyne.gnu@gmail.com>
* @license  GNU AFFERO GENERAL PUBLIC LICENSE
* @copyright

OrfeoGPL Models are the data definition of OrfeoGPL Information System


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
//en este archivo se crea de forma automatica los expedientes segun dependencias y tabla de retencion. esto con el fiun de que sea un proceso transparente para el usuario final.

$ruta_raiz = '..';
include_once "$ruta_raiz/include/db/ConnectionHandler.php";
include_once "$ruta_raiz/include/tx/Expediente.php";
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

// se consulta la base de datos para saber trd por dependencia.

$Rquery = "select depe_codi, sgd_id_serie, sgd_id_subserie from sgd_mrd_matrird where sgd_mrd_esta='1' group by 1,2,3";
	$Rrs = $db->conn->Execute($Rquery);


while(!$Rrs->EOF)
{
	$dependencia = $Rrs->fields["DEPE_CODI"];
	$codiSRD = $Rrs->fields["SGD_ID_SERIE"];
	$codiSBRD = $Rrs->fields["SGD_ID_SUBSERIE"];


	$Uquery ="select usua_doc from usuario where usua_codi='1' and usua_esta='1' and depe_codi='$dependencia'";
	$Urs = $db->conn->Execute($Uquery);

	$usua_doc = $Urs->fields["USUA_DOC"];
	$usuaDocExp = $usua_doc;

	$nivelExp='1';
        $digCheck = "E";
	$anoExp = date("Y");	
	$trdExp = substr("00" . $codiSRD, -2) . substr("00" . $codiSBRD, -2);
	$consecutivoExp = substr("00000" . $secExp, -5);
	$numeroExpediente = $anoExp . $dependencia . $trdExp . $consecutivoExp . $digCheck;


	//se llama a la funcion crear expediente
	$expediente = new Expediente($db);
	$numeroExpedienteE = $expediente->crearExpediente($numeroExpediente, $nurad, $dependencia, $codusuario, $usua_doc, $usuaDocExp, $codiSRD, $codiSBRD, 'false', $fechaExp, $_POST['codProc'], $arrParametro, $nivelExp);


	echo $numeroExpedienteE;
	$Rrs->MoveNext();
}
?>
