<?
 if(!$ruta_raiz) $ruta_raiz = "..";

  include "$ruta_raiz/dbconfig.php";
  include "$ruta_raiz/processConfig.php";
 //include "$ruta_raiz/config.php";
 
 if(!$debugPHPMailer) $debugPHPMailer=1;
 if(!$SMTPSecure)  $SMTPSecure='tls';
 $smtpAuth=$smtpAuth;
 $admPHPMailer = $correoSaliente;
 $userPHPMailer = $correoSaliente;
 $passwdPHPMailer = $passwordCorreoSaliente;
 //$hostPHPMailer        = "ssl://smtp.gmail.com";      // Para fuera Gmail es "ssl://smtp.gmail.com"
 $hostPHPMailer        = $servidorSmtp;                 // Para fuera Gmail es "ssl://smtp.gmail.com"
 $portPHPMailer        = $puertoSmtp;                     // Para Gmail el Puerto es "465"      
 $debugPHPMailer       = $debugPHPMailer;  // Si esta en 2 mostrara una depuracion al enviar correo.  En 1 los evita.
 $asuntoMailReasignado = "Llegada de Radicado $radicadosSelText";
 $asuntoMailRadicado   = "Llegada de Radicado nuevo $radicadosSelText";
 $asuntoMailInformado  = "Se ha informado de un radicado $radicadosSelText";
 $servidorOrfeoBodega  = "http://localhost/sogamoso/bodega/";

  
 // Datos para correo de respuesta emailRespuestaRapida

 $correoSalienteRR          = $correoSaliente;
 $passwordCorreoSalienteRR  = $passwordCorreoSaliente;

// Datos para correo de respuesta Pqrs
 $usuarioEmailPQRS     = "noresponder@upme.gov.co";
 $emailPQRS            = "noresponder@upme.gov.co";
 $passwordEmailPQRS    = "";
 
 //echo "$correoSaliente $passwordCorreoSaliente $servidorSmtp $puertoSmtp";


?>
