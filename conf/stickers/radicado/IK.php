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
$dirLogo = "../../img/logoik.png";
?>
<body topmargin="5" leftmargin="0"  onload="window.print()">
    <table  cellpadding="0" cellspacing="0">
	<tr>
<!--             <p><span><b> <?=$noRadBarras?> </b></span></p> -->
	</tr>
	<tr>
	    <td  width="20%" style="padding-right:10px;padding-top:5px" >
		<img src="<?=$dirLogo?>" alt="<?=$entidad_corto?>"  height="20" width="100">
                <p><span><b> Carrera 7 No.16 56</b></span></p>
                <p><span><b> Of. 804  Bogotá D.C</b></span></p>
                <p><span><b> Tel (571) 3415001 - 2819445 </b></span></p>
                <p><span><b> info@infometrika.com </b></span></p>
<!--                <p><span><b> Destino: Dependencia <?=$depe_destino?> <b> No.Folios: <?=$folios?> </b> </b></span></p> -->
	   </td>
            <td  width="80%" align=left>

                <!--<span><center><img src="barcode_img.php?num=<?php /*echo($nurad) */?>&type=Code39&imgtype=png" width="200px"><center><span>
                <p><span><b>Destino: <?/*=substr($dependenciaDestino,0,20)*/?><p><span><b> -->
             <!--	<p><span><b> <?=$noRadBarras?> </b></span></p> -->
	        <p><span> <?=$noRadBarras?> </span></p>
                <p><span><b> Rad: <?=$nurad?> </b>  <b> Fecha: <?=substr($radi_fech_radi,0,17)?> </b></span></p>
                <p><span> <b>Us: <?=$usua_login?></b> <b> Dest: Dep <?=$depe_destino?> </b> <b> No.Folios: <?=$folios?> </b>   </span></p>
                <p><span><b> Rem: <?=substr($remite,0,20); ?> </b></span></p>
                <p><span><b> Desc.Anex: <?=substr($anexDesc,0,20); ?></b> <b><?php echo "&nbsp;&nbsp;";?>    N.Anexos: <?=substr($anexos,0,5);?></b> </span></p>

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
    </table>
</body>
</html>

