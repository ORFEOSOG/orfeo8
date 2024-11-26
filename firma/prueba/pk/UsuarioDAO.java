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
import java.sql.*;
import javax.naming.*;
import javax.rmi.*;
import javax.ejb.SessionContext;
import javax.sql.*;


/**
 * Radicado es la clase encargada de gestionar operaciones con la base de datos, que tienen que ver con Usuarios
 * @author      Sixto Angel Pinz�n
 * @version     1.0
 */
public class UsuarioDAO {
  /**
   * Objeto de conexion
   */
  Connection conn;
   /**
   * Almacena el estado del resultado de tratar de conectarse, en caso de no presentarse problema su valor es OK
   */
  String status;
   /**
   * Almacena el nombre del servicio de base de datos donde se ha de efectuar la transacci�n
   */
  String servBase;
  
  
   /**
  * Constructor encargado de gestionar la conexi�n
  * @param	servDB 	String  Corresponde al nombre del servicio de base de datos donde se fectuar�a la transacci�n
  */
  public UsuarioDAO(String servDB){
   servBase = servDB;
   status="OK" ;
    
   try {
      Context ctx = new InitialContext();
      DataSource ds = (DataSource)ctx.lookup(servBase);
      conn = ds.getConnection();
    } catch (Exception e)   {
      status = e.toString();
    }
  
  }
  
  
   /** 
  * Retorna un objeto tipo Usuario con lo datos del usuario cuyo documento ingresa como par�metro
  * @param  usrDoc  Corresponde al n�mero del documento del usuario que se ingresa como par�metro
	* @return   boolean
  */

  public Usuario getUsuario(String usrDoc) {
    Usuario UsrAux;
    String nomb=null;
     String dep=null;
     String codi=null;
    
    try  {
      String sql = "select * from usuario where usua_doc ='"+usrDoc+"'";
      Statement s = conn.createStatement();
      ResultSet rs = s.executeQuery(sql);
      
      if (rs.next()) {
        nomb = rs.getString("usua_nomb");
        dep = rs.getString("depe_codi");
        codi = rs.getString("usua_codi");
      }
      rs.close();
     
      
    } catch (SQLException e) {
        e.printStackTrace();
        UsrAux = new Usuario(usrDoc,e.toString());
        return UsrAux; 
     }
      
    UsrAux = new Usuario(usrDoc,nomb,codi,dep);
    return UsrAux;  
  }
  
   public void cerrarConexion() throws Exception {
    conn.close();
  }
  
}