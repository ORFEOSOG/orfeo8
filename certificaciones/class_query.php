<?php
/**
* @module Auditoria
*
* @author Jairo sanchez   <cyberdyne.gnu@gmail.com>
* @license  GNU AFFERO GENERAL PUBLIC LICENSE
* @copyright

OrfeoGPL Models are the data definition of OrfeoGPL Information System
Copyright (C) 2024.

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

class query{
	var $db; // conexion a base de datos
	var $radicado;
	var $codigo;
	

	function __construct($db){
		$this->db=$db;
	}

	function valida_rad($rad, $cod){
		$sql= "SELECT * FROM RADICADO WHERE RADI_NUME_RADI = '$rad' AND SGD_RAD_CODIGOVERIFICACION = '$cod' ";
		$rsql=$this->db->conn->Execute($sql);
		
		if (!$rsql->EOF)
		{
		    $mensaje ='no hay';
		    return $mensaje;
		}
	}

}
?>
