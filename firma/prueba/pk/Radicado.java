/*************************************************************************************/
/* ORFEO GPL:Sistema de Gestion Documental		http://www.orfeogpl.org	               */
/*	Idea Original de la SUPERINTENDENCIA DE SERVICIOS PUBLICOS DOMICILIARIOS         */
/*				COLOMBIA TEL. (57) (1) 6913005  orfeogpl@gmail.com                         */
/* ===========================                                                       */
/*                                                                                   */
/* Este programa es software libre. usted puede redistribuirlo y/o modificarlo       */
/* bajo los terminos de la licencia GNU General Public publicada por                 */
/* la "Free Software Foundation"; Licencia version 2. 			                         */
/*                                                                                   */
/* Copyright (c) 2005 por :	              	  	                                     */
/*   Sixto Angel Pinzón López --- angel.pinzon@gmail.com   Desarrollador             */
/*                                                                                   */
/* Colocar desde esta lInea las Modificaciones Realizadas Luego de la Version 3.5    */
/*  Nombre Desarrollador   Correo     Fecha   Modificacion                           */
/*************************************************************************************/
package pk;

/**
 * Radicado es la clase encargada de almacenar la informaci�n referente a un radicado, 
 * puede ser enviada para ser procesada al servlet servletFirma
 * @author      Sixto Angel Pinz�n
 * @version     1.0
 */
public class Radicado implements java.io.Serializable { 
   /**
   * Atributo que almacena el n�mero del Radicado
   */
  private String radi_nume_radi=null;
   /**
   * Atributo que almacena el path del documento del Radicado
   */
  private String radi_path=null;
  /**
   * Atributo que almacena el stream de bytes la firma obtenida del Radicado
   */
  private  byte[] firma;
  /**
   * Atributo que almacena el stream de bytes de la llave publica de quien firma
   */
  private  byte[] pkEncoded;
   /**
   * Atributo que almacena el n�mero del documento de quien firma el Radicado
   */
  private String docFirmante = null;
   /**
   * Atributo que almacena la acci�n a efectuar sobre el radicado que ha sido 
   * enviado Radicado
   */
  private String accion;
   /**
   * Atributo que almacena el nomber del servicio de base de datos a utilizar 
   * para la firma
   */
  private String servBase;
   /**
   * Atributo que almacena el certificado de quien firma
   */
  private java.security.cert.Certificate certificado;
  

  
  
  
  /** 
  * Constructor encargado de inicializar los atributos n�ero de radicado y path
  * @param	radicado	String es n�mero del radicado
  * @param	path	String es el path del documento del del radicado
  * @return   void
  */ 
  public Radicado(String radicado, String path ){
    radi_nume_radi = radicado;
    radi_path = path;
  }
  
 
 /** 
 * Retorna un String  con el dato correspondiente al n�mero del radicado
 * @return   string
 */ 
  public String getRadiNume(){
    return radi_nume_radi;
  }
  
  
 /** 
  * Retorna un String  con el dato correspondiente al path del documento del radicado
  * @return   string
 */
  public String getRadiPath(){
    return radi_path;
  }
 
 
  /** 
 * Retorna un String  con el dato correspondiente al n�mero del docuemento de quien firma
 * @return   string
 */ 
  public String getDocFirmante(){
    return docFirmante;
  }
  
 
  /** 
 * Retorna un byte[], strem de bytes  stream de bytes la firma obtenida del Radicado
 * @return   byte[]
 */
  public  byte[] getFirma(){
    return firma;
  }
  
  
 /** 
 * Retorna un byte[], strem de bytes  stream de bytes la pk de quien firma
 * @return   byte[]
 */
  public  byte[] getPkEncoded(){
    return pkEncoded;
  }
  
  
 /** 
 * Retorna un String correspondiente a la acci�n a efectuar sobre  el radicado
 * @return   String
 */
  public  String getAccion(){
    return accion;
  }
  
  /** 
 * Retorna el atributo certificado de la clase
 * @return   java.security.cert.Certificate 
 */
  public  java.security.cert.Certificate getCertificado(){
    return certificado;
  }
  
  
  /** 
  * Retorna el valor del nombre sel servicio de base de datos donde se habr� de efectuar la firma
  * @return   String
  */
  public String getServBase() {
    return(servBase);
  }
  
  
  /** 
  * Setea el valor del atributo firma
  * @param	f	byte[]  Stream de bytes de la firma
  * @return   void
  */
  public void setFirma ( byte[] f) {
    firma = f;
  }
 
 
  /** 
  * Setea el valor del documento de quien firma
  * @param	doc String  Documento de quien firma
  * @return   void
  */
  public void setDocFirmante(String doc) {
    docFirmante = doc;
  }
  
  
   /** 
  * Setea el valor de la acci�n
  * @param	acc String  Acci�n a efectuar
  * @return   void
  */
  public void setAccion(String acc) {
    accion = acc;
  }
  
   /** 
  * Setea el valor del nombre sel servicio de base de datos
  * @param	serv String  Nombre sel servicio de base de datos
  * @return   void
  */
  public void setServBase(String serv) {
    servBase = serv;
  }
  
  
   
  /** 
  * Setea el valor del strema de bytes de la llave publica de quien firma
  * @param	enc	byte[]  Stream de bytes de la pk de quien firma 
  * @return   void
  */
  public void setpkEncoded( byte[] enc) {
    pkEncoded = enc;
  }
  
  
  /** 
  * Setea el valor del certificado de quien firma
  * @param	cer	java.security.cert.Certificate  Certificado de quien firma
  * @return   void
  */
  public void setCertificado (java.security.cert.Certificate cer) {
    certificado = cer;
  }
}