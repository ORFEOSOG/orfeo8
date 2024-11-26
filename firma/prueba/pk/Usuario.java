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
 * Usuario es la clase encargada de almacenar la informaci�n referente a un usuario, para ser enviada y procesada 
 * @author      Sixto Angel Pinz�n
 * @version     1.0
 */
public class Usuario implements java.io.Serializable { 
 
  /**
   * Almacena el nombre del usuario
  */
  private String usua_nomb =null;
  /**
   * Almacena el documento del usuario
  */
  private String usua_doc = null;
  /**
   * Almacena la dependencia del usuario
  */
  String depe_codi;
  /**
   * Almacena el codigo del usuario
  */
  String usua_codi;
  
  
  /**
  * Constructor encargado de inicializar los datos del usuario
  * @param	usrdoc 	String  Corresponde documento del usuario
  * @param	nomb 	String  Corresponde nombre del usuario
  */   
  public Usuario(String usrdoc, String  nomb ){
    usua_doc = usrdoc;
    usua_nomb = nomb;
  }
   
   
  /**
  * Constructor encargado de inicializar los datos del usuario
  * @param	usrdoc 	String  Corresponde documento del usuario
  * @param	nomb 	String  Corresponde nombre del usuario
  * @param	usuaCodi 	String  Corresponde codigo del usuario
  * @param	usuaDep 	String  Corresponde a la dependencia del usuario
  * 
  */   
  public Usuario(String usrdoc, String  nomb, String usuaCodi, String  usuaDep ){
    usua_doc = usrdoc;
    usua_nomb = nomb;
    depe_codi = usuaDep;
    usua_codi = usuaCodi;
  }
 
 
  /** 
 * Retorna un String con el nombre del usuario
 * @return   String
 */ 
  public String getUsuaNomb(){
    return usua_nomb;
  }
  
    
  /** 
 * Retorna un String con el documento del usuario
 * @return   String
 */  
  public String getUsuaDoc(){
    return usua_doc;
  }
   
  
  /** 
 * Retorna un String con la dependencia del usuario
 * @return   String
 */  
  public String getDepeCodi(){
    return depe_codi;
  }
  
  
  /** 
 * Retorna un String con el código del usuario
 * @return   String
 */  
  public String getUsuaCodi(){
    return usua_codi;
  }
   
   
}