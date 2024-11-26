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
 * Radicado es la clase encargada de gestionar operaciones con la base de datos, 
 * que tienen que ver con historicos
 * @author      Sixto Angel Pinz�n
 * @version     1.0
 */
public class HistoricoDAO {

  /**
   * Objeto de conexion
   */
  Connection conn;
   /**
   * Almacena el estado del resultado de tratar de conectarse, en caso de no 
   * presentarse problema su valor es OK
   */
  String status;
   /**
   * Almacena el nombre del servicio de base de datos donde se ha de efectuar 
   * la transacci�n
   */
  String servBase;
    
  
  /**
  * Constructor encargado de gestionar la conexi�n
  * @param	servDB 	String  Corresponde al nombre del servicio de base de datos 
  * donde se fectuar�a la transacci�n
  */
  public HistoricoDAO(String servDB){
    servBase = servDB;
    status="OK" ;
    
    //En capsua posibles errores de conexi�n
    try {
      Context ctx = new InitialContext();
      DataSource ds = (DataSource)ctx.lookup(servBase);
      conn = ds.getConnection();
     // System.out.println(conn+"<-creando hist-->"+servDB);
    } catch (Exception e)   {
      e.printStackTrace();
      status = e.toString();
   }
   
  }
  
   /**
  * Almacena información del histórico para una transacción que efectúa el mismo 
  * usuario de acuerdo a los parámetros recibidos
  * @param	servDB 	String  Corresponde al nombre del servicio de base de datos 
  * donde se fectuar�a la transacci�n
  */
  public String guardarHistUnico(String rad,Usuario user, String tx , String observaciones)  {
     String sql=null;
    try
    {
      sql= "insert into HIST_EVENTOS (RADI_NUME_RADI,DEPE_CODI,USUA_CODI,USUA_CODI_DEST,DEPE_CODI_DEST,USUA_DOC,HIST_DOC_DEST,SGD_TTR_CODIGO,HIST_OBSE,HIST_FECH) values ( ? , ? , ? , ? , ? , ? , ? , ? , ? , sysdate ) ";              
               PreparedStatement ps = conn.prepareStatement(sql);
               ps.setLong(1,Long.parseLong(rad));
               ps.setLong(2,Long.parseLong( user.getDepeCodi()));
               ps.setLong(3,Long.parseLong( user.getUsuaCodi()));
               ps.setLong(4,Long.parseLong( user.getUsuaCodi()));
               ps.setLong(5,Long.parseLong( user.getDepeCodi()));
               ps.setString(6,user.getUsuaDoc());
               ps.setString(7,user.getUsuaDoc());
               ps.setLong(8,Long.parseLong( tx));
               ps.setString(9,observaciones);
               ps.execute();
               conn.close();
        System.out.println("HistoricoDAO.guardarHistUnico: Guarda el histórico " +
        "de la transacción");
    }
    catch (SQLException e)
    {
       return (e.toString() + "-->" + sql );
    }
    
    return "OK";
    
  }
  
}

