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
 * FirmaDocto es la clase encargada de almacenar la informaci�n referente a una firma de un documento, para ser procesada o enviada para este fin al servidor encargado de este proceso
 * @author      Sixto Angel Pinz�n
 * @version     1.0
 */
public class FirmaDocto implements java.io.Serializable {
  /**
   * Objeto con la informaci�n referente al usuario que firma
   */
  public Usuario usr;
  /**
   * Hash de la firma
   */
  public byte [] firma;
  /**
   * Bytes de la pk de quien firma
   */
   public  byte[] pkEncoded;
  
  
  
 /** 
  * Constructor que carga los datos de la instacia 
  * @param	usrAux  Usuario Uobjeto con los datos de quien firma
	* @return   void
  */
  public FirmaDocto(Usuario usrAux, byte [] firmAux, byte [] pkaux ) {
    usr = usrAux;
    firma = firmAux;
    pkEncoded = pkaux;
  }
}