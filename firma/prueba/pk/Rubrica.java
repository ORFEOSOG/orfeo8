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
/*  Nombre Desarrollador   Correo                   Fecha     Modificacion           */
/*  Johnny Gonzalez     johnnygonzalezl@gmail.com 03-11-2006  Revisión y se ponen    */
/*                      mensajes en consola que indiquen el progreso de cada paso    */
/*************************************************************************************/
package pk;
import java.io.FileOutputStream;
import java.io.PrintStream;
import java.net.URL;
import java.net.URLConnection;
import java.security.Certificate;
import java.security.GeneralSecurityException;
import java.security.KeyFactory;
import java.security.KeyStore;
import java.security.NoSuchAlgorithmException;
import java.security.PrivateKey;
import java.security.PublicKey;
import java.security.Signature;
import java.security.spec.InvalidKeySpecException;
import java.security.spec.X509EncodedKeySpec;
import java.util.Properties;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;


/**
 * Rubrica es la clase encargada de encapsular las operaciones relacionadas con 
 * la firma digital a aplicar a un documento
 * @author      Sixto Angel Pinz�n
 * @version     1.0
 */
public class Rubrica {
  /**
   * Objeto llave privada
   */
  PrivateKey privKey;
   /**
   * Objeto llave publica
   */
  PublicKey  publKey;
   /**
   * Objeto cadena de certificaci�n
   */
  public java.security.cert.Certificate[] certificationChain;
  
  
  /** 
  * Constuctor que iniclializa los componentes de llave privada y cadena de 
  * certificaci�n de la clase
  * @param  priv  Objeto llave privada
  * @param  pub  Objeto llave publica
  * @param  cadenaCert  Objeto arreglo de la cadena de certificaci�n
  * 
	* @return   void
  */ 
  public Rubrica(java.security.PrivateKey priv, java.security.PublicKey pub ,  
                  java.security.cert.Certificate[] cadenaCert ){
    privKey = priv;
    publKey =  pub;
    certificationChain = cadenaCert;
  }
  
   /** 
  * Constuctor que iniclializa los componentes de llave p�blica, privada y 
  * cadena de certificaci�n  de la firma
  * @param  priv  Objeto llave privada
  * @param  cadenaCert  Objeto arreglo de la cadena de certificaci�n
	* @return   void
  */ 
  public Rubrica(java.security.PrivateKey priv, java.security.cert.Certificate[] cadenaCert ){
    privKey = priv;
    certificationChain = cadenaCert;
  }
  
  
  /** 
  * Obtiene la firma digital de un documento situdo en una Url utilizando el 
  * componente de llave privada de la clase
  * @param  urlFile  Url del documwnto
  * @return   Retorna un stream de bytes con la firma
  */
  public byte[] getSignFromUrl(String urlFile) throws Exception{
   
    PrintStream p; 
    byte[] buf = new byte[1024];
    int len;
    URL documento;
    byte[] buffer;
    InputStream in;
    Signature sig;
    sig = Signature.getInstance("SHA1withRSA");
    sig.initSign(privKey);
    documento = new URL(urlFile );
    URLConnection yc = documento.openConnection();
    in = documento.openStream();
    int bytesCopied = 0;
    buffer = new byte[4096];
   	int bytes;
  
    while ( (bytes = in.read( buffer )) != -1 ){
      sig.update(buffer, 0, bytes);
    }
    in.close();
    
   
    byte[] firma = sig.sign();
    return (firma);
  }
  
  
  /** 
  * Analiza si un documento de una Url fu� firmado con la llave privada da la clase, 
  * utilizando el atributo de llave p�blica. Retorna verdadero si la comprobaci�n 
  * es satisfactoria y falso de lo contrario. 
  * @param  urlFile  Url del documwnto
  * @param  Stream de bytes con la firma del documento
  * @return   Retorna verdadero si la comprobaci�n es satisfactoria y falso de 
  * lo contrario. 
  */
  public boolean verifySignUrl(String urlFile,  byte[] firma) throws Exception  {
    
    int len;
    URL documento;
    byte[] buffer;
    InputStream in;
    Signature sig;
    sig = Signature.getInstance("SHA1withRSA");
    System.out.println("Arch.." + urlFile);
    System.out.println("Firma.." + firma.toString());
    System.out.println("PK.." + publKey.toString());
    documento = new URL(urlFile);
    URLConnection yc = documento.openConnection();
    in = documento.openStream();
    int bytesCopied = 0;
    int bytes;
    sig.initVerify(publKey);
    documento = new URL(urlFile);
    yc = documento.openConnection();
    in = documento.openStream();
    bytesCopied = 0;
    buffer = new byte[4096];
  
    while ( (bytes = in.read( buffer )) != -1 ){
      sig.update(buffer, 0, bytes);
    }
     
    in.close();
    
    if (sig.verify(firma)) 
        return true;
      else
       return false;
  }
  
  /** 
  * Obtiene el hash de bytes correspodiente a la representaci�n de la llave p�blica
  * @return  byte[] Retorna el hash de bytes correspodiente a la representaci�n 
  * de la llave p�blica
  */
  public byte[] getPkEncoded() {
    return this.publKey.getEncoded();
  }
  
  /** 
  * Genera la llave public apartir del hash de bytes correspodiente a la 
  * representaci�n de la llave p�blica
  * @param  byte[]  Stream de bytes que representan la llave p�blica de la firma
  * @return  PublicKey  Retorna la llave p�blica generada
  */
  public PublicKey  setPkEncoded(byte[] pk) 
    throws NoSuchAlgorithmException,InvalidKeySpecException {
    
    X509EncodedKeySpec pubKeySpec = new X509EncodedKeySpec(pk);
    KeyFactory keyFactory = KeyFactory.getInstance("RSA");
    this.publKey = keyFactory.generatePublic(pubKeySpec);
    return this.publKey; 
  }
 
 public void clean()
 {
    privKey = null;
    publKey = null;
    certificationChain = null;
 }
  
 
}