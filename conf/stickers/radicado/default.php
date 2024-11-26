<?$ruta_raiz="../.."?>
<html>
<head>
<title>Sticker web</title>
<link rel="stylesheet" href="estilo_imprimir.css" TYPE="text/css" MEDIA="print">
<style type="text/css">

body {
    margin-bottom:0;
    margin-left:0;
    margin-right:0;
    margin-top:0;
    padding-bottom:0;
    padding-left:0;
    padding-right:0;
    padding-top:0
    font-family: Arial, Helvetica, sans-serif;
}

span{
    font-size:   15px;
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

<body marginheight="10" marginwidth="10" width="40">
<table align="left" height="80" border="0">
<tr>
<td colspan="2">

</td>
</tr>
<tr>
<td valign="center"><img width="80" src="<?=$dirLogo?>"></td>
<td align="center" >
<?=$noRadBarras?>
<br>
<FONT FACE="Arial" size="3">No. <?=$noRad?><br></font>
<FONT FACE="Arial" size="2">Fecha Radicado: <?=substr($radi_fech_radi,0,16)?></font></br>
<FONT FACE="Arial" size="2">Destino: <?=$depeNomb?></br>
<FONT FACE="Arial" size="2">Remitente: <?=$remitente?></br>
Anexos: <?=$anexos?>. Folios: <?=$folios?>.</font>
</td>

</tr>
</table>
</body>
</html>

