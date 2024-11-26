<?
session_start();
if (!$ruta_raiz)
	$ruta_raiz = "..";
if (!$dependencia)   include "$ruta_raiz/rec_session.php";
$usua_doc=$_SESSION[usua_doc];

?>
<html>

  <title>Sistema de informaci&oacute;n <?=$entidad_largo?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap core CSS -->
  <?php include_once "../htmlheader.inc.php"; ?>

          <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

</head>

<body>
<div id="amr" title="Basic dialog">
    <p></p>
</div>
 <div id=message>
 </div>
 

  <div id="content" style="opacity: 1;">

    <div class="row">
<table>
<tr>
      <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
      <h1 class="page-title txt-color-blueDark"><i class="glyphicon glyphicon-inbox"></i> Bandeja <span>Documentos para Firma</span></h1>
      </div>
</tr>
</table>
</div>

<?
 
 include_once "$ruta_raiz/include/db/ConnectionHandler.php";
error_reporting(7);
 $db = new ConnectionHandler("$ruta_raiz");	 
 $accion_sal = "Firmar Documentos";
 $nomcarpeta = "Documentos pendientes de firma digital";
 $pagina_sig = "firmarDocumentos.php";
 
 if ($orden_cambio==1)  {
 	if (!$orderTipo)  {
	   $orderTipo=" DESC";
	}else  {
	   $orderTipo="";
	}
 }
 
 	if (!$orderNo)  {
	   		$orderNo=0;
			$orderTipo=" desc ";
	}

	if($busqRadicados)
	{
   
    $busqRadicados = trim($busqRadicados);
    $textElements = split (",", $busqRadicados);
    $newText = "";
	$dep_sel = $dependencia;
    foreach ($textElements as $item)
    {
         $item = trim ( $item );
         if ( strlen ( $item ) != 0)
		 {
 	        $busqRadicadosTmp .= " fr.radi_nume_radi like '%$item%' or";
		 }
    }
	if(substr($busqRadicadosTmp,-2)=="or")
	{
	 $busqRadicadosTmp = substr($busqRadicadosTmp,0,strlen($busqRadicadosTmp)-2);
	}
	if(trim($busqRadicadosTmp)) 
	{
	 $whereFiltro .= "and ( $busqRadicadosTmp ) ";
	}

	}
	 

 $encabezado = "".session_name()."=".session_id()."&ruta_raiz=$ruta_raiz&krd=$krd&filtroSelect=$filtroSelect&tpAnulacion=$tpAnulacion&orderTipo=$orderTipo&radicado=$radicado&orderNo=";
 $linkPagina = "$PHP_SELF?$encabezado&orderTipo=$orderTipo&orderNo=$orderNo";
 $carpeta = "nada";
 include "../envios/paEncabeza.php";
 $pagina_actual = $PHP_SELF;
 include "../envios/paBuscar.php";   

 
 include "../envios/paOpciones.php";   

	/*  GENERACION LISTADO DE RADICADOS
	 *  Aqui utilizamos la clase adodb para generar el listado de los radicados
	 *  Esta clase cuenta con una adaptacion a las clases utiilzadas de orfeo.
	 *  el archivo original es adodb-pager.inc.php la modificada es adodb-paginacion.inc.php
	 */
error_reporting(7);

?>
  <form name=formEnviar action='./<?=$pagina_sig?>?<?=$encabezado?>' method=post>
  
 <?

//$db->conn->debug=true;
	include "$ruta_raiz/include/query/firma/queryCuerpoPendientesFirma.php";
	$rs=$db->conn->Execute($query);
	
	if (!$rs->EOF)  {
		$pager = new ADODB_Pager($db,$query,'adodb', true,$orderNo,$orderTipo);
		$pager->toRefLinks = $linkPagina;
		$pager->toRefVars = $encabezado;
		$pager->checkTitulo = true; 
		$pager->Render($rows_per_page=20,$linkPagina,$checkbox=chkEnviar);		
	} 
	else{
		echo "<hr><center><b>NO se encontro nada con el criterio de busqueda</center></b></hr>";	
	}
	
 ?>
  </form>

</body>

</html>

