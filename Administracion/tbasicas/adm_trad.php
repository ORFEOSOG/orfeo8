<?php
/**
* @module crearUsuario
*
* @author Jairo Losada   <jlosada@gmail.com>
* @author Cesar Gonzalez <aurigadl@gmail.com>
* @license  GNU AFFERO GENERAL PUBLIC LICENSE
* @copyright

SIIM2 Models are the data definition of SIIM2 Information System
Copyright (C) 2013 Infometrika Ltda.

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

    $ruta_raiz="../..";
    if (!$_SESSION['dependencia'])
        header ("Location: $ruta_raiz/cerrar_session.php");


function valueToJsValue($value, $encoding = false) {
	if (!is_numeric($value)) {
		$value = str_replace('\\', '\\\\', $value);
		$value = str_replace('"', '\"', $value);
		$value = '"'.$value.'"';
	}
	if ($encoding) {
		switch ($encoding) {
			case 'utf8' :
				return iconv("ISO-8859-2", "UTF-8", $value);
				break;
		}
	} else {
		return $value;
	}
	return ;
}

function arrayToJsArray( $array, $name, $nl = "\n", $encoding = false ) {
	if (is_array($array)) {
		$jsArray = $name . ' = new Array();'.$nl;
		foreach($array as $key => $value) {
			switch (gettype($value)) {
				case 'unknown type':
				case 'resource':
				case 'object':
					break;
				case 'array':
					$jsArray .= arrayToJsArray($value,
									$name.'['.valueToJsValue($key, $encoding).']',
									$nl);
					break;
				case 'NULL':
					$jsArray .= $name.'['.valueToJsValue($key,$encoding).'] = null;'.$nl;
					break;
				case 'boolean':
					$jsArray .= $name.'['.valueToJsValue($key,$encoding).'] = '.($value ? 'true' : 'false').';'.$nl;
					break;
				case 'string':
					$jsArray .= $name.'['.valueToJsValue($key,$encoding).'] = '.valueToJsValue($value, $encoding).';'.$nl;
					break;
				case 'double':
				case 'integer':
					$jsArray .= $name.'['.valueToJsValue($key,$encoding).'] = '.$value.';'.$nl;
					break;
				default:
					trigger_error('Hoppa, egy j  ERROR a PHP-ben?'.__CLASS__.'::'.__FUNCTION__.'()!', E_USER_WARNING);
			}
		}
		return $jsArray;
	} else {
		return false;
	}
}

require_once("$ruta_raiz/include/db/ConnectionHandler.php");
$db = new ConnectionHandler($ruta_raiz);
//$db->conn->debug=true;
$error = 0;


if ($db) {
	$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
	if (isset($_POST['btn_accion'])) {
		include($ruta_raiz."/include/class/tipoRadicado.class.php");
		$varRad = new TipRads($db);
		$record = array();
		$record['SGD_TRAD_CODIGO'] = $_POST['grpRad'];
		$record['SGD_TRAD_DESCR'] = "'".ucfirst(trim($_POST['txtnombre']))."'";
		($_POST['slcGRS']) ? $record['SGD_TRAD_GENRADSAL']=1: $record['SGD_TRAD_GENRADSAL']=0;

		switch ($_POST['btn_accion']) {
			case 'Agregar':
				$error = $varRad->SetInsDatosTipRad($record);
				break;
			case 'Modificar':
				$error = $varRad->SetModDatosTipRad($record);
				break;
			case 'Eliminar':
				$error = $varRad->SetDelDatosTipRad($record['SGD_TRAD_CODIGO']);
		}
		unset($record);
	}

	$sql =	"SELECT SGD_TRAD_CODIGO as ID,
			SGD_TRAD_DESCR as NOMB,
			SGD_TRAD_GENRADSAL as GRS
		FROM SGD_TRAD_TIPORAD
		ORDER BY SGD_TRAD_CODIGO";

	$v_tr = $db->conn->GetAll($sql);
} else {
	$error = 1;
}

if ($error) {
	$cad = '<tr bordercolor="#FFFFFF">
			<td width="3%" align="center" class="titulosError" colspan="3" bgcolor="#FFFFFF">';
	switch ($error) {
		case 1:	//NO CONECCION A BD
			$cad .= "Error al conectar a BD, comuniquese con el Administrador de sistema !!";
			break;
		case 2:	//ERROR EJECUCCION SQL
			$cad .= "Error al gestionar datos, comuniquese con el Administrador de sistema !!";
			break;
		case 3:	//ACUTALIZACION REALIZADA
			$cad .= "Informacion actualizada!!";
			break;
		case 4:	//INSERCION REALIZADA
			$cad .= "Registro creado satisfactoriamente!!";
			break;
		case 5:	//IMPOSIBILIDAD DE ELIMINAR ESP, TIENE HISTORIAL, ESTA LIGADO CON RADICADOS EXISTENTES
			$cad .= "No se puede eliminar registro, se encuentra ligado a radicados existentes.";
			break;
	}
	$cad .= '</td></tr>';
}
?>
<html>
<head>
<title>.:Orfeo - Administrador de Contactos :.</title>
<?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
<script language="JavaScript" src="<?=$ruta_raiz?>/js/formchek.js"></script>
<script language="JavaScript">
<?php
//HLP. Convertimos el vector de Tipos de Radicados a vector en JavaScript.
echo arrayToJsArray($v_tr, 'v_tr');
?>
function ver_listado()
{	window.open('listados.php?<?=session_name()."=".session_id()?>&var=tpr','','scrollbars=yes,menubar=no,height=600,width=800,resizable=yes,toolbar=no,location=no,status=no');
}

function ValidarInformacion(boton)
{	var strMensaje = 'Por favor ingrese las datos.';
	var clicked = false;
	var flag = true;
	var pos = false;
	var cual;
	// Recorreos para saber si selecciono algun id
	for (i=0; i<document.form1.grpRad.length; i++)
	{	if (document.form1.grpRad[i].checked)
		{
			clicked = true;
			cual = i;
	}	}
	//En caso de haber seleccionado....
	if (clicked)
	{	if(boton === "Eliminar")
		{	if (busca(cual) === false)
			{	alert('Codigo no asignado');
				flag = false;
		}	}
		if (boton === "Agregar")
		{	if (busca(cual) === false)
			{	if(isWhitespace(document.form1.txtnombre.value))
				{	alert('Debe digitar un nombre.\n' + strMensaje);
					document.form1.txtnombre.focus();
					flag = false;
				}
				if(isWhitespace(document.form1.slcGRS.value))
				{	alert('Seleccione si genera radicados de salida.\n' + strMensaje);
					document.form1.slcGRS.focus();
					flag = false;
			}	}
			else
			{	alert('Codigo ya esta asignado !!');
				flag = false;
		}	}
		if (boton === "Modificar")
		{	if (busca(cual) === false)
			{	alert('Codigo no asignado');
				flag = false;
			}
			else
			{	if(isWhitespace(document.form1.txtnombre.value))
				{	alert('Debe digitar un nombre.\n' + strMensaje);
					document.form1.txtnombre.focus();
					flag = false;
				}
				if(isWhitespace(document.form1.slcGRS.value))
				{	alert('Seleccione si genera radicados de salida.\n' + strMensaje);
					document.form1.slcGRS.focus();
					flag = false;
	}	}	}	}
	else
	{
		alert('No ha seleccionado un Tipo de Radicado');
	}
	return clicked&&flag;
}

function ver_datos(val) {
	hallado = busca(val);
	if (hallado === false) {
		document.form1.txtnombre.value = '';
		document.form1.slcGRS.value = '';
	} else {
		document.form1.txtnombre.value = v_tr[hallado]['NOMB'];
		document.form1.slcGRS.value = v_tr[hallado]['GRS'];
	}
}

/*
* Busca un codigo en el vector de Tipos de radicados existentes.
* Retorna false sino lo encuentra o la posicion encontrada.
*/
function busca(dato){
	var band;
	band = false;
	for (i=0; i<v_tr.length; i++)
		if (v_tr[i]['ID'] == dato)
		{	band = i;
		}
	return band;
}
</script>
</head>
<body>

  <form method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?krd='.$krd ?>" name="form1" id="form1">
  <input type='hidden' name='<?=session_name()?>' value='<?=session_id()?>'>

  <div class="col-sm-12">
    <!-- widget grid -->
    <section id="widget-grid">
      <!-- row -->
      <div class="row">
        <!-- NEW WIDGET START -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <!-- Widget ID (each widget will need unique ID)-->
          <div class="jarviswidget jarviswidget-color-darken" id="wid-id-1" data-widget-editbutton="false">

            <header>
              <h2>
                Administrador de Tipos de Radicados<br>
                <small><?=$tituloCrear ?></small>
              </h2>
            </header>

            <!-- widget div-->
            <div>
              <!-- widget content -->
              <div class="widget-body no-padding">

                <table class="table table-bordered table-striped">

                  <tr class=timparr>
                    <td width="3%" class="titulos2" height="26">1.</td>
                    <td width="25%" class="titulos2" height="26">Seleccione el C&oacute;digo</td>
                    <td width="72%" class="listado2" height="1">
                      <table class="table table-bordered table-striped">
                        <tr>
                          <td width="10%" align="center">0</td>
                          <td width="10%" align="center">1</td>
                          <td width="10%" align="center">2</td>
                          <td width="10%" align="center">3</td>
                          <td width="10%" align="center">4</td>
                          <td width="10%" align="center">5</td>
                          <td width="10%" align="center">6</td>
                          <td width="10%" align="center">7</td>
                          <td width="10%" align="center">8</td>
                          <td width="10%" align="center">9</td>
                        </tr>
                        <tr>
                          <td width="10%" align="center"><input type="radio" value="0" name="grpRad" onclick="ver_datos(this.value)" disabled></td>
                          <td width="10%" align="center"><input type="radio" value="1" name="grpRad" onclick="ver_datos(this.value)" disabled></td>
                          <td width="10%" align="center"><input type="radio" value="2" name="grpRad" onclick="ver_datos(this.value)" disabled></td>
                          <td width="10%" align="center"><input type="radio" value="3" name="grpRad" onclick="ver_datos(this.value)"></td>
                          <td width="10%" align="center"><input type="radio" value="4" name="grpRad" onclick="ver_datos(this.value)"></td>
                          <td width="10%" align="center"><input type="radio" value="5" name="grpRad" onclick="ver_datos(this.value)"></td>
                          <td width="10%" align="center"><input type="radio" value="6" name="grpRad" onclick="ver_datos(this.value)"></td>
                          <td width="10%" align="center"><input type="radio" value="7" name="grpRad" onclick="ver_datos(this.value)"></td>
                          <td width="10%" align="center"><input type="radio" value="8" name="grpRad" onclick="ver_datos(this.value)"></td>
                          <td width="10%" align="center"><input type="radio" value="9" name="grpRad" onclick="ver_datos(this.value)"></td>
                        </tr>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td width="3%" class="titulos2" height="26" rowspan="2">2.</td>
                      <td width="25%" class="titulos2" height="26">Nombre del T.R.</td>
                      <td width="72%" class="listado2">
                        <input type="text" name="txtnombre" id="txtnombre" size="30" maxlength="30" />
                      </td>
                    </tr>
                    <tr>
                      <td width="25%" class="titulos2" height="26">Genera radicado de salida?</td>
                      <td width="72%" class="listado2">
                        <select size="1" name="slcGRS">
                          <option value="">&nbsp;</option>
                          <option value="1">S I</option>
                          <option value="0">N O</option>
                        </select>
                      </td>
                    </tr>
                    <?PHP echo $cad ?>
                  </table>

                  <table class="table table-bordered table-striped">
                    <tr>
                      <td width="10%">&nbsp;</td>
                        <td width="20%" align="center"><input name="btn_accion" type="button" class="botones" id="btn_accion" value="Listado" onClick="ver_listado();" accesskey="L" alt="Alt + L"></td>
                      <td width="20%" align="center"><input name="btn_accion" type="submit" class="botones" id="btn_accion" value="Agregar" onClick="return ValidarInformacion(this.value);" accesskey="A" /></td>
                      <td width="20%" align="center"><input name="btn_accion" type="submit" class="botones" id="btn_accion" value="Modificar" onClick="return ValidarInformacion(this.value);" accesskey="M" /></td>
                      <td width="20%" align="center"><input name="btn_accion" type="submit" class="botones" id="btn_accion" value="Eliminar" onClick="return ValidarInformacion(this.value);" accesskey="E" /></td>
                      <td width="10%">&nbsp;</td>
                    </tr>
                  </table>

              </div>
            </div>
          </div>
        </article>
      </div>
    </section>
  </div>
</form>
</body>
</html>
