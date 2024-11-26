<?$ruta_raiz="../.."?>
<html>
<head>
<title>Sticker web</title>
<link rel="stylesheet" href="estilo_imprimir.css" TYPE="text/css" MEDIA="print">
<style type="text/css">

body {
    margin-bottom:0;
    margin-left:10px;
    margin-right:0;
    margin-top:0;
    padding-bottom:0;
    padding-left:0;
    padding-right:0;
    padding-top:0
    font-family: Arial, Helvetica, sans-serif;
}

span{
    font-size:   10px;
    line-height: 15px;
    clear:       both;
}
h3,p{
    margin: 0px;
}
td{
    width:auto;
}

</style>
</head>
<?php 
$dirLogo = "../../img/CNSC.favicon.png";
?>
<body topmargin="5" leftmargin="0"  onload="window.print()">
    <table  cellpadding="0" cellspacing="0">
	<tr>
<!--             <p><span><b> <?=$noRadBarras?> </b></span></p> -->
	</tr>
	<tr>
	    <td  width="20%" style="padding-right:10px;padding-top:3px"  align="right" rowspan=3>
		<img src="<?=$dirLogo?>" alt="<?=$entidad_corto?>"   width="60"  align="right">
                <p><span><b>  </b></span></p>
                
<!--                <p><span><b> Destino: Dependencia <?=$depe_destino?> <b> No.Folios: <?=$folios?> </b> </b></span></p> -->
	   </td>
            <td  width="90%" align=left>

                <!--<span><center><img src="barcode_img.php?num=<?php /*echo($nurad) */?>&type=Code39&imgtype=png" width="200px"><center><span>
                <p><span><b>Destino: <?/*=substr($dependenciaDestino,0,20)*/?><p><span><b> -->
             <!--	<p><span><b> <?=$noRadBarras?> </b></span></p> -->
	        <p><span> <?=$noRadBarras?> </span></p>
                <p><span><b> Rad: <?=$nurad?> </b>  - Fecha : <?=substr($radi_fech_radi,0,17)?> </span></p>
                <p><span> <b>Us: <?=$usua_login?></b> <b> Dest: Dep <?=$depSigla?> </b> <b> No.Folios: <?=$folios?> </b>   </span></p>
                <p><span><b> Rem: <?=substr($remite,0,20); ?> </b></span></p><!--
                <p><span><b> Desc.Anex: <?=substr($anexDesc,0,20); ?></b> <b><?php ?>    N.Anexos: <?=substr($anexos,0,5);?></b> </span></p>-->

                <!--<p><span  align="left"><b>
                    Folios: <?=$radi_nume_folio?> &nbsp;&nbsp; Anexos: <?=$radi_nume_anexo?> &nbsp;&nbsp; Copias: <?=$copias?>   </b>
                </span></p>

                <span  align="left"><b>
                    <?=substr($radi_fech_radi,0,16). " "?>  &nbsp;&nbsp; C&oacute;d veri: <?=$sgd_rad_codigoverificacion?> </b>
                </span>
                <p><span><b>Consulte su tr&aacute;mite en http://www.correlibre.org</b></span></p>
                <p><span><b><?/*=$entidad_largo*/?></b></span></p>-->
            </td>
        </tr>
        <tr><td align="center">
                <p><span><b> COMISION NACIONAL DEL SERVICIO CIVIL <!--<br> www.cnsc.gov.co--> </b></span></p>    
          </td>
        </tr>
    </table>
</body>
</html>

