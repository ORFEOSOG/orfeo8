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

/**
 * AnexoDAO es la clase encargada de gestionar las operaciones y los datos b�sicos referentes a un anexo que haya sido adicionado a un radicado
 * @author      Sixto Angel Pinz�n
 * @version     1.0
 */

package pk;
import java.sql.*;
import javax.naming.*;
import javax.rmi.*;
import javax.ejb.SessionContext;
import javax.sql.*;



public class AnexoDAO 
{
  /**
   * Objeto de conexion
   */
  Connection conn;
  String status;
  
  
  /**
   * Constructor encargado de gestionar la conexi�n
   */
  public AnexoDAO(){
    status="OK" ;
    try {
      Context ctx = new InitialContext();
      DataSource ds = (DataSource)ctx.lookup("fldoc");
      conn = ds.getConnection();
     
      //
      
    } catch (Exception e)   {
      status = e.toString();
   }
   
   
  }
  
  public String conecta(String login) {
    String nombre="aun no definido...";
    try  {
      String sql = "select usua_nomb from usuario where usua_login = '"+login+"'";
      Statement s = conn.createStatement();
      ResultSet rs = s.executeQuery(sql);
      /*
      String sql = "select usua_nomb from usuario where usua_login = '?'";
      PreparedStatement ps = conn.prepareStatement(sql);
      ps.setString(1,login);
      ResultSet rs = ps.executeQuery();*/
      
      if (rs.next()) {
        nombre = rs.getString("usua_nomb");
      }
    } catch (SQLException e) {
      return e.toString();
      
    }
    return nombre;  
  }
  

}