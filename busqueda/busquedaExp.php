<?

/**
 * @author  YULLIE QUICANO
 * @mail    yquicano@cra.gov.co
 * @version     1.0
 */
$ruta_raiz = "../";
session_start();
error_reporting(0);
require_once($ruta_raiz."include/db/ConnectionHandler.php");

if (!$db)	$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

//En caso de no llegar la dependencia recupera la sesi�n
if(empty($_SESSION)) include $ruta_raiz."rec_session.php";

include ("common.php");
$fechah = date("ymd") . "_" . time("hms");

$mostarTodosRads = $_REQUEST["mostrarTodosRads"];
?>
<!DOCTYPE html>
<html>
<head>
<?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
<title>Consultas Expedientes</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?php echo $ruta_raiz?>estilos/orfeo.css">

<script language="JavaScript" src="<?=$ruta_raiz?>/js/formchek.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	function Consultar() {
	window.open("<?=$ruta_raiz?>/expediente/conExp.php?krd=<?=$krd?>&numRad=<?=$verrad?>&dependencia=<?=$dependencia?>","Consulta Expedientes Existentes","height=800,width=1500,scrollbars=yes");
}
 

  	function noPermiso(){
		alert ("No tiene permiso para acceder");
	}
	
	function verHistExpediente(numeroExpediente,codserie,tsub,tdoc,opcionExp) {
  <?php
		$isqlDepR = "SELECT RADI_DEPE_ACTU,RADI_USUA_ACTU from radicado
		            WHERE RADI_NUME_RADI = '$numrad'";
		$rsDepR = $db->conn->Execute($isqlDepR);
		$coddepe = $rsDepR->fields['RADI_DEPE_ACTU'];
		$codusua = $rsDepR->fields['RADI_USUA_ACTU'];
		$ind_ProcAnex = "N";

  ?>
  window.open("<?=$ruta_raiz?>/expediente/verHistoricoExp.php?sessid=<?=session_id()?>&opcionExp="+opcionExp+"&numeroExpediente="+numeroExpediente+"&nurad=<?=$verrad?>&krd=<?=$krd?>&ind_ProcAnex=<?=$ind_ProcAnex?>","HistExp<?=$fechaH?>","height=800,width=1060,scrollbars=yes");
}
</script>


   <tr>
        
      </td>
    </tr>
</table> 
</head>
<body>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

 
<body onLoad="crea_var_idlugar_defa(<?php echo "'".($_SESSION['cod_local'])."'"; ?>);">
<?
$params = session_name()."=".session_id()."&krd=$krd";
?>
<script>
 function limpiar()
		{
	   document.formSeleccion.elements['nume_expe'].value = "";
	   document.formSeleccion.elements['dep'].value = "0";
	   document.formSeleccion.elements['nume_radi'].value = "";
	   document.formSeleccion.elements['nomexpe'].value = "";
	 
  }
</script> 
<form action="busquedaExp.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formSeleccion" id="formSeleccion" " >
 <table width="60%"  border="1" >
  	<tr bordercolor="#FFFFFF">
    <td colspan="2" class="titulos4">
	<center>
	<p><B><span class=etexto><a name="formSeleccion">B&uacute;squeda de Expedientes y Radicados Asociados</a></span></B> </p></td> 
       
    
     </td>
          </tr>
    <table border=1 width=60% class=t_bordeGris><tr> 
    <td class="titulos5">Expediente </td>
    <td class="listado5"><input class="tex_area" type="text" name="nume_expe" maxlength="17" value="<?=$nume_expe?>" size="25" ></td>
    <?php
      if($mostrarTodosRads) $datoss = "checked"; else $datoss = "";
    ?>
    <TD><input type="checkbox" name="mostrarTodosRads" value="1"  <?=$datoss?> >Mostrar Todos los Radicados(De lo contrario ) </TD>
    </tr>
     <table border=1 width="60%" class=t_bordeGris><tr> 
    <td class="titulos5">Radicado </td>
    <td class="listado5">   <input class="tex_area" type="text" name="nume_radi" maxlength="17" value="<?=$nume_radi?>" size="25" >
      </td>
          </tr>   
              <table border=1 width=60% class=t_bordeGris><tr> 
             <td class="titulos5">Dependencia Responsable</td>
            <td class="listado5"> 
  <?PHP
	$sql = "SELECT 'Todas las dependencias' as DEPE_NOMB, 0 AS DEPE_CODI FROM DEPENDENCIA 
	          UNION  SELECT DEPE_NOMB, DEPE_CODI AS DEPE_CODI FROM DEPENDENCIA
            WHERE DEPE_CODI NOT IN (900,905,999,997)
				 order by depe_nomb DESC";
    //$db->conn->debug = true;
    $rsDep = $db->conn->Execute($sql);
    if(!$s_DEPE_CODI) $s_DEPE_CODI= 0;
    print $rsDep->GetMenu2("dep","$dep",false, false, 0," class='select' onChange='submit();'");	  
    
    if(!$dep) $dep="0";
    if($dep!=0) $whereDep = " and depe_codi = $dep";
    
	?>
  </td>
</tr>
  <td class="titulos5">Usuario Responsable</td>
  <td class="listado5"> 
    <?PHP
              
  $sql = "(SELECT 'Todos los Usuarios' as USUA_NOMB, 0 AS USUA_CODI, USUA_DOC, DEPE_CODI FROM USUARIO where depe_codi=900 limit 1)
                 UNION  SELECT USUA_NOMB, USUA_CODI, USUA_DOC, DEPE_CODI  FROM USUARIO
                 WHERE DEPE_CODI NOT IN (900,905,999, 997) and depe_codi = $dep
         order by usua_nomb DESC";
  //$db->conn->debug = true;
  $rsDep = $db->conn->Execute($sql);
  if(!$s_DEPE_CODI) $s_DEPE_CODI= 0;
  print $rsDep->GetMenu2("usuaDoc","$usuaDoc",false, false, 0," class='select'");
  ?>
            </td>
          </tr>
          
              <table border=1 width=60% class=t_bordeGris><tr> 
            <td class="titulos5">Etiqueta o Nombre de Expediente</td>
            <td class="listado5">  <input class="tex_area" type="text" name="nomexpe" maxlength="4000" value="<?=$nomexpe?>" size="80" ></td>
             <table border=1 width=60% class=t_bordeGris><tr> <td class="titulos5" align="right">
            <input  class="botones" value="Limpiar" onClick="limpiar();" type="button">
           	<input name="Busqueda" type="submit"  class="botones" id="envia22"   value="Busqueda">&nbsp;&nbsp;
            </td>
          
          </tr>
 
           </td>
          </tr>
          </tbody> 
        </table>
</form>        
   
<?php 

	
if(!empty($_POST['Busqueda'])&& ($_POST['Busqueda']=="Busqueda")){

	 
			
		 			
$where=null;
		   					   			
				
			$where=(!empty($_POST['dep']) && ($_POST['dep'])!="")?(
							($where!="")? $where." AND S.DEPE_CODI=".$_POST['dep']."":" WHERE S.DEPE_CODI= ".$_POST['dep']."") 			
							:$where;
							
			$where=(!empty($_POST['usuaDoc']) && ($_POST['usuaDoc'])!="")?(
							($where!="")? $where." AND E.USUA_CODI=".$_POST['usuaDoc']."":" WHERE E.USUA_CODI= ".$_POST['usuaDoc']."") 			
							:$where;
			
			$where=(!empty($_POST['nume_radi']) && trim($_POST['nume_radi'])!="")?(
							($where!="")? $where." AND E.RADI_NUME_RADI LIKE '%".strtoupper(trim($_POST['nume_radi']))."%'":" WHERE E.RADI_NUME_RADI LIKE '%".strtoupper(trim($_POST['nume_radi'])."%' ")) 			
							:$where;
														
			$where=(!empty($_POST['nume_expe']) && trim($_POST['nume_expe'])!="")?(
							($where!="")? $where." AND S.SGD_EXP_NUMERO LIKE '%".strtoupper(trim($_POST['nume_expe']))."%'":" WHERE S.SGD_EXP_NUMERO LIKE '%".strtoupper(trim($_POST['nume_expe'])."%' ")) 			
							:$where;
						
			$where=(!empty($_POST['nomexpe']) && trim($_POST['nomexpe'])!="")?(
							($where!="")? $where." AND (s.sgd_sexp_parexp1||s.sgd_sexp_parexp2||s.sgd_sexp_parexp3||s.sgd_sexp_parexp4||s.sgd_sexp_parexp5) LIKE '%".strtoupper(trim($_POST['nomexpe']))."%'":" WHERE (s.sgd_sexp_parexp1||s.sgd_sexp_parexp2||s.sgd_sexp_parexp3||s.sgd_sexp_parexp4||s.sgd_sexp_parexp5) LIKE '%".strtoupper(trim($_POST['nomexpe'])."%'")) 			
							:$where;				
			

#echo "in" .($_SESSION['dependencia'])  ;			
if($mostarTodosRads){

 
$camposConcatenar = "concat(s.sgd_sexp_parexp1,' /',s.sgd_sexp_parexp2,' /',s.sgd_sexp_parexp3,' /',s.sgd_sexp_parexp4,' /',s.sgd_sexp_parexp5)";
                                                    
                                                    
  $isql= "select distinct( e.radi_nume_radi), tp.sgd_tpr_descrip, s.sgd_exp_numero, r.ra_asun, r.radi_fech_radi, dir.sgd_dir_nomremdes, u.usua_nomb,  r.radi_path,
            s.depe_codi
            , d.depe_nomb
            , E.SGD_EXP_FECH
            , $camposConcatenar as PARAMETRO, 
            (se.sgd_srd_descrip||' - '||su.sgd_sbrd_descrip) AS SESUB
            ,s.sgd_cerrado
            ,u.usua_nomb
          from sgd_exp_expediente E
            INNER JOIN SGD_SEXP_SECEXPEDIENTES S ON E.sgd_exp_numero = S.sgd_exp_numero
            INNER JOIN 	RADICADO R ON E.RADI_NUME_RADI = R.RADI_NUME_RADI
            inner join sgd_dir_drecciones dir on dir.radi_nume_radi = r.radi_nume_radi,
            dependencia d,
            usuario u,
            sgd_srd_seriesrd se,
            SGD_TPR_TPDCUMENTO tp,
            sgd_sbrd_subserierd su
            {$where} AND 
            s.sgd_srd_codigo = se.sgd_srd_codigo and
            tp.sgd_tpr_codigo = R.tdoc_codi and
            s.sgd_sbrd_codigo = su.sgd_sbrd_codigo and
            s.sgd_srd_codigo  = su.sgd_srd_codigo and
            s.depe_codi not in (900,905,910,999,360) and
            s.depe_codi = d.depe_codi and s.usua_doc_responsable = u.usua_doc and e.sgd_exp_estado <> 2
            order by SGD_EXP_NUMERO ASC, SGD_EXP_FECH DESC";
}else{


			$camposConcatenar = "concat(min(s.sgd_sexp_parexp1),' /',min(s.sgd_sexp_parexp2),' /',min(s.sgd_sexp_parexp3),' /',min(s.sgd_sexp_parexp4),' /',min(s.sgd_sexp_parexp5))";
                                                    
  $isql= "select min(e.radi_nume_radi) radi_nume_radi
            ,count(e.radi_nume_radi) RA_ASUN
            ,s.sgd_exp_numero
            ,min(s.depe_codi) depe_codi
            , min(d.depe_nomb) depe_nomb
            , min(E.SGD_EXP_FECH) SGD_EXP_FECH
            ,$camposConcatenar as PARAMETRO
            ,concat(min(se.sgd_srd_descrip),' - ',min(su.sgd_sbrd_descrip)) AS SESUB
            ,min(u.usua_nomb) usua_nomb
            ,min(s.sgd_cerrado) sgd_cerrado
          from SGD_SEXP_SECEXPEDIENTES S
            LEFT OUTER JOIN sgd_exp_expediente E  ON (E.sgd_exp_numero = S.sgd_exp_numero and e.sgd_exp_estado <> 2),
            dependencia d,
            usuario u,
            sgd_srd_seriesrd se,
            sgd_sbrd_subserierd su
            {$where} AND 
            s.sgd_srd_codigo = se.sgd_srd_codigo and
            s.sgd_sbrd_codigo = su.sgd_sbrd_codigo and
            s.sgd_srd_codigo  = su.sgd_srd_codigo and
            s.depe_codi not in (900,905,910,999,360) and
            s.depe_codi = d.depe_codi and s.usua_doc_responsable = u.usua_doc 
            group by S.SGD_EXP_NUMERO
            order by SGD_EXP_NUMERO ASC";

}

/**
            --, 
            --(se.sgd_srd_descrip||' - '||su.sgd_sbrd_descrip) AS SESUB
            --,s.sgd_cerrado
            */
 //$db->conn->debug = true;
 $rssql = $db->conn->Execute($isql);
 //$db->conn->debug = FALSE;
?>
<table align="center" class="borde_tab" width="80%" >
  <tr>
    <td class="titulos4" >RADICADOS ASOCIADOS Y EXPEDIENTE:</td>
  </tr>
<table border=2 align="center" class="borde_tab" width="80%">
  <tr>
  <?php
  if($mostarTodosRads){
  ?>
    <td class="titulos2" align="center">EXPEDIENTE</td>
    <td class="titulos2" align="center">FECHA ASOC.</td>
    <td class="titulos2" align="center">RADICADO</td>
    <td class="titulos2" align="center">FECHA RAD</td>
    <td class="titulos2" align="center">ASUNTO</td>
    <td class="titulos2" align="center">TIPO DOC</td>
    <td class="titulos2" align="center">REMITENTE/DESTINO</td>
    <td class="titulos2"  align="center">NOMBRE</td>
    <td class="titulos2" align="center">SERIE/SUBSERIE</td>
    <td class="titulos2" align="center">DEPENDENCIA</td>
    <td class="titulos2" align="center">USUARIO</td>
    <td class="titulos2" align="center">ESTADO</td>
  </tr>
  <?php
  }else{
  ?>
    <td class="titulos2" align="center">EXPEDIENTE</td>
    <td class="titulos2" align="center">FECHA ASOC.</td>
    <td class="titulos2" align="center">RADICADO</td>
    <td class="titulos2" align="center"></td>
    <td class="titulos2" align="center">NUMERO RADICADOS</td>
    <td class="titulos2" align="center"></td>
    <td class="titulos2" align="center"></td>
    <td class="titulos2"  align="center">NOMBRE</td>
    <td class="titulos2" align="center">SERIE/SUBSERIE</td>
    <td class="titulos2" align="center">DEPENDENCIA</td>
    <td class="titulos2" align="center">USUARIO</td>
    <td class="titulos2" align="center">ESTADO</td>  
  <?php
  }
  ?>
<?php

	while(!$rssql->EOF){
		$radi        	= $rssql->fields['RADI_NUME_RADI'];
		$fechradi     = $rssql->fields['RADI_FECH_RADI'];
		$dir			    = $rssql->fields['SGD_DIR_NOMREMDES'];
		$asun			    = $rssql->fields['RA_ASUN'];
		$tipo_doc			= $rssql->fields['SGD_TPR_DESCRIP'];
		$num_expediente = $rssql->fields['SGD_EXP_NUMERO'];
		$fechexp     	= $rssql->fields['SGD_EXP_FECH'];
		$par		     	= $rssql->fields['PARAMETRO'];
		$sesub		   	= $rssql->fields['SESUB'];
		$depen	      = $rssql->fields['DEPE_NOMB'];
		$usua	       	= $rssql->fields['USUA_NOMB'];
		$cerrado	       	= $rssql->fields['SGD_CERRADO'];
				
	$linkInfGeneral = "<a class='vinculos' href='../verradicado.php?verrad=$radi&carpeta=8&nomcarpeta=Busquedas&tipo_carp=0'>";	
  if($radi){
	 $sql2="select radi_nume_radi, sgd_spub_codigo from radicado where  radi_nume_radi='$radi'";
   $rssql2 = $db->conn->Execute($sql2);
   $priv= $rssql2->fields["SGD_SPUB_CODIGO"];
  }
	?>
   <tr>
    <td class="info" align="center" width="20%"><?=$num_expediente?><span class="leidos2"> </span>&nbsp;<input type="button" value=".H." class=botones_2 onClick="verHistExpediente('<?=$num_expediente?>');"></td>
    <td class="info" align="center"><?=$fechexp?></td>
   <?PHP if ($priv==1){ ?>
    <td class="info" align="center"><?=$radi?></td>
    <td class="info" align="center"><?= tohtml($fechradi)?></td>
    <td class="info" align="center">{Doc Privado}</td>
   <?PHP }else{ ?>
    <td class="info" align="center"><?=$rads= "<a href=\"{$ruta_raiz}bodega".$rssql->fields['RADI_PATH']."\">".$rssql->fields['RADI_NUME_RADI']."";?></td> 
    <td class="info" align="center"><?=$linkInfGeneral?><?= tohtml($fechradi)?></a></td>
    <td class="info" align="center"><?=$asun?></td>
    
    <?PHP } ?>
    <td class="info" align="center"><?=$tipo_doc?></td>
    <td class="info" align="center"><?=$dir?></td>
    <td class="info" align="center"><?=$par?></td>
    <td class="info" align="center"><?=$sesub?></td>
    <td class="info" align="center"><?=$depen?></td>
    <td class="info" align="center"><?=$usua?></td>
    <td class="info" align="center"><?=$cerrado?></td>
   </tr>
  <?PHP
$rssql->MoveNext();
	}
	
?>
</table>
<?PHP
}
?>
</form>

</BODY>
</HTML>
