<?php
/**
* @module index.php
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
session_start();
foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

$x='0';
if(!$ruta_raiz) $ruta_raiz= "..";

include("$ruta_raiz/dbconfig.php");
include_once "$ruta_raiz/processConfig.php";
include('./phpqrcode/qrlib.php');
$db   = new ConnectionHandler("$ruta_raiz");

$rad = $_GET['rad'];
$nextval = $db->conn->nextId("sec_sgd_certificado");
$idCertificado = $nextval;
// consulta peticionario

if(!empty($dato6))
{
	$sql1="insert into sgd_certificado (id_certificado, radi_nume_radi, sgd_tipo_certificado, dato6, dato7, dato8, dato9, dato10, dato11, sgd_concepto_certificado) values ('$idCertificado','$rad','1','$dato6', '$dato7', '$dato8', '$dato9', '$dato10', '$dato11', '$concepto' )";
	$Rsql1=$db->conn->Execute($sql1);

	if($Rsql1)
	{
	    $x=1;
	    $mensajeA1='Correcto';
	    $mensajeA2= "'<p style='margin:0cm;font-size:16px;font-family:'Aptos',sans-serif;'><strong><span style='color:black;'>Datos guardados correctamente</span></strong></p>'";
	    $icon='success';
	}
}

if ($concepto=='3')
{
    //Insert tabla anexo
    $tpRad='1';
    $tpDepeRad='170';
    $noDigitosDep='3';
    $noDigitosRad='6';
    $fecha=$db->conn->OffsetDate(0,$db->conn->sysTimeStamp);
    $year=date("Y");
    
    $SecName = "SECR_TP".$tpRad."_";
    $secNew=$db->conn->nextId($SecName);
    
    if($secNew==0){
        $db->conn->RollbackTrans();
        $secNew=$db->conn->nextId($SecName);
        if($secNew==0) die("<hr><b><font color=red><center>Error no genero un Numero de Secuencia<br>SQL: $secNew</center></font></b><hr>");
    }
    $nurad = date("Y") . str_pad($tpDepeRad,$noDigitosDep,"0",STR_PAD_LEFT) . str_pad($secNew,$noDigitosRad,"0", STR_PAD_LEFT) . $tpRad;
    $codValida = "'" . substr(sha1(microtime()), 0 , 10) . "'";
    //$radi_path="/".date("Y")."/$dependencia"."/$newRadicado.pdf";
    
    $sql2="insert into radicado (radi_nume_radi,radi_fech_radi,tdoc_codi,trte_codi,mrec_codi,radi_pais,muni_codi,carp_codi,
        radi_usua_actu,radi_depe_actu,ra_asun,radi_depe_radi,radi_usua_radi,codi_nivel,carp_per,radi_leido,radi_tipo_deri,sgd_fld_codigo,sgd_trad_codigo,id_cont,id_pais,sgd_rad_codigoverificacion
        ) VALUES($nurad,$fecha,'0','0','1','170','759','0','1','999','Certificado digital zona segura','120','1','1','0','0','1','0','2','1','170',$codValida)";
    

    $rad1=$db->query($sql2);
    
    // Insert tabla sgd_dir_drecciones
    
    $sgd_dir_nomremdes=$_SESSION['Nombre'];
    $sgd_dir_direccion=$_SESSION['Direccion'];
    $sgd_dir_telefono=$_SESSION['Telefono'];
    $mailDestino=$_SESSION['Email'];
    $Cedula=$_SESSION['Cedula'];
    
    $nextvaldreccion=$db->nextId("sec_dir_drecciones");
    $nextvalciu=$db->nextId("sec_ciu_ciudadano");
    
    //Insert tabla sgd_ciu_ciudadano
    
    
    $sql1= "insert into sgd_ciu_ciudadano (tdid_codi,sgd_ciu_codigo,sgd_ciu_nombre,sgd_ciu_direccion,sgd_ciu_apell1,sgd_ciu_telefono,sgd_ciu_email,muni_codi,dpto_codi,sgd_ciu_cedula,id_cont,id_pais,noti_email)
	VALUES('2','$nextvalciu','$sgd_ciu_nombre','$dato9','$sgd_ciu_apelll','$sgd_ciu_telefono','$sgd_ciu_email','759','15','$sgd_ciu_cedula','1','170','1')	";
    
    $ciu=$db->query($sql1);
    
    //Insert tabla sgd_dir_drecciones
    
    $sql3="insert into sgd_dir_drecciones (sgd_dir_codigo,sgd_dir_tipo,sgd_ciu_codigo,radi_nume_radi,muni_codi,dpto_codi,sgd_dir_direccion,sgd_dir_telefono
        ,sgd_dir_mail,sgd_sec_codigo,sgd_dir_nombre,sgd_dir_nomremdes,sgd_trd_codigo,sgd_dir_doc,id_pais,id_cont) VALUES($nextvaldreccion,'1',$nextvalciu,
        $nurad,'759','15','$sgd_dir_direccion',$sgd_dir_telefono,'$sgd_dir_mail','0','$sgd_dir_nomremdes','$sgd_dir_nomremdes','1','$Cedula','170','1')";
        
        $dir=$db->query($sql3);
        //echo $newRadicadoS;
        
        // insert tabla anexos
        //$Usua="select from radicado r, usuario u, dependencia d where";
        $anex_codigo="$rad"."00001";
        $newRadicadoS=$nurad;
        $anexNomb="$newRadicadoS.pdf";
        $ext='pdf';
        $sql4="insert into anexos (anex_radi_nume, anex_codigo, anex_tipo, anex_solo_lect, anex_creador, anex_desc, anex_nomb_archivo, anex_borrado, radi_nume_salida
	, anex_radi_fech,sgd_dir_tipo,sgd_tpr_codigo) VALUES ($rad,$anex_codigo,'7','S','JOHNSANCHEZ','Se entrego documento via internet','".$anexNomb."','N',$nurad, $fecha,'1','0' )";
        
        $salida=$db->query($sql4);
        
        //echo $sql4;
        
// termina si tipo de radicado es ==1

    //fin radicado salida
    
    require_once ('./tcpdf/examples/tcpdf_include.php');
    
    class MYPDF extends TCPDF {
        //Page header
        public function Header() {
            // get the current page break margin
            $bMargin = $this->getBreakMargin();
            // get current auto-page-break mode
            $auto_page_break = $this->AutoPageBreak;
            // disable auto-page-break
            $this->SetAutoPageBreak(false, 0);
            // set bacground image
            $img_file = K_PATH_IMAGES.'bg1.jpg';
            $this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
            // restore auto-page-break status
            $this->SetAutoPageBreak($auto_page_break, $bMargin);
            // set the starting point for the page content
            $this->setPageMark();
        }
    }
    
    // create new PDF document
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    /*propiedades del pdf*/
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Oficina Tics Comunicaciones');
    $pdf->SetTitle('Certificación Zonas Seguras');
    $pdf->SetSubject('Emision Certificacion Zona Segura');
    $pdf->SetKeywords('palabra clave');
    
    /* agrega una pagina */
    $pdf->AddPage();
    
    // -- set new background ---
    
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $img_file = K_PATH_IMAGES.'bg1.jpg';
    $pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
   //codigo de barras
   $VerQr="http://ciudadinteligente.sogamoso.org/certificaciones/ValidadCert.php?&rad='$nurad'&CodValida='$codValida'";
   
   QRcode::png($VerQr, "qr/". $nurad.".png", QR_ECLEVEL_L, 2, 2);
   
    $html= file_get_contents('./formato.html');
    $html='
<p></p>
<p></p>
<p></p>
<p></p>
<p style="text-align: right;"><strong>Certificado No '."$nurad".'</strong></p>
<p>&nbsp;</p>
<p style="text-align: center;"><strong><font size="15">La Oficina TIC y Comunicaciones</font></strong></p>
<p>&nbsp;</p>
<p style="text-align: justify;">Seg&uacute;n el Decreto 210 de junio de 2024 &ldquo;por medio del cual se establece requisitos para garantizar la implementaci&oacute;n de zonas seguras en el desarrollo de actividades comerciales abiertas al p&uacute;blico del sector nocturno y de entretenimiento, en donde se efectu&eacute;&rdquo; en el art&iacute;culo 4 &iacute;tem 22.</p>
<p>&nbsp;</p>
<p style="text-align: center;"><strong><font size="15">Certifica Que:</font></strong></p>
<p style="text-align: justify;">El establecimiento comercial '."$dato6".', con NIT '."$dato7".' ubicado en la direcci&oacute;n '."$dato8".', en la ciudad de Sogamoso. Cuyo propietario '."$sgd_dir_nomremdes".', con cedula de ciudadania '."$Cedula".' Cumple a satisfacci&oacute;n los requerimientos t&eacute;cnicos en cuanto a:</p>
<p>&nbsp;</p>
<ul>
<li>Cubrimiento 100% del area locativa.</li>
<li>Almacenamiento en DVR y/o NUBE de los videos con vigencia m&iacute;nimo de 30 dias.</li>
<li>Contrato de soporte y mantenimiento de las camaras.</li>
</ul>
<p>Por lo tanto se da concepto <strong><font size="15">POSITIVO</font></strong></p>

<p style="text-align: center;"><img src="./img/firmaTic.png"  width="100" height="100" /><br /><strong>JAIME EDUARDO OSTOS GUEVARA </strong><br />JEFE OFICINA TIC COMUNICACIONES<br /><img src="./qr/'."$nurad".'.png" width="70" height="70"/></p>
<p>&nbsp;</p>';
    
    
    
    /*convertir a pdf*/
    
    $pdf->writeHTML($html,true,false,true,false,'');
    
    /*salida y cargue del archivo*/
    
    $filelocation= '/var/www/html/sogamoso/bodega/2024/170/docs/';
    $file = $filelocation.$anexNomb;
 
    $pdf->Output($file,'FI');
    $pathRadicado=$file;

    $codTx=11;
    require("$ruta_raiz/include/mail/$entidad.mailInformar.php");
}

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<link rel="stylesheet" href="css/style.css">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<title> .:Datos Establecimiento:. </title>
</head>

<body>
 <div class="demo form-bg">
        <div class="container-fluid">
            <div class="row text-center">
                <div class="col-lg-12">
                 
                </div>
            </div>
	    <p>
	    <p>	

            <div class="row">
                <div class="col-md-offset-3 col-md-6">
                    <form action="./dat_establecimiento1.php?&rad=<?php echo $rad;?>" method="post">
			<h1 class="heading"> Datos del Establecimiento</h1>
                        <div class="form-group">
                        <table class="form-table">
                        <tr>
                        <td class="form-td">
                            <label class="control-label">Nombre Establecimiento</label>
                            <input name="dato6" type="text" class="form-control" />
                        </td>
                        <td class="form-td">
                            <label class="control-label">Nit.</label>
                            <input name="dato7" type="numeric" class="form-control" />
                        </td>
                        </tr>
                        <tr>
                        <td class="form-td">
                            <label class="control-label">Dirección Establecimiento</label>
                            <input name="dato8" type="text" class="form-control" />
                        </td>
                        </tr>
                        
                        
                        <tr>
                        <td class="form-td">
                            <label class="control-label">* Datos Contrato Mantenimiento de Camaras</label>
                        </td>
                        </tr>
                        <tr>
                        <td class="form-td">
                            <label class="control-label">Número Contrato</label>
                            <input name="dato9" type="text" class="form-control" />
                        </td>
                        <td class="form-td">
                            <label class="control-label">Nombre Establecimiento</label>
                            <input name="dato10" type="text" class="form-control" />
                        </td>
                        </tr>
                        <tr>
                        <td class="form-td">
                            <label class="control-label">Contrato vigente</label>
            			    <select name="dato11" class="form-control">
            			    <option value="1">Si</option>
            			    <option value="0" selected>No</option>
            			    </select>
                        </td>
                        <td class="form-td">
                            <label class="control-label">Concepto</label>
            			    <select name="concepto" class="form-control">
            			    <option value="1">Negativo</option>
            			    <option value="2">Condicional</option>
            			    <option value="3">Positivo</option>
            			    <option value="0" selected>No</option>
            			    </select>
                        </td>
                        </tr>
                        </table>

                            

                            <button type="submit" class="btn btn-default">Guardar <i class="fa fa-arrow-circle-right fa-2x"></i></button>
                        </div>
                        
                    </form>
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
                </div>
            </div>
        </div>
    </div>
    

</body>
</html>
