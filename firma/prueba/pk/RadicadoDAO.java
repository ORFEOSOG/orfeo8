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
import java.util.Vector;
import javax.naming.*;
import javax.rmi.*;
import javax.ejb.SessionContext;
import javax.sql.*;
//import oracle.*;


/**
 * RadicadoDAO es la clase encargada de gestionar operaciones con la base de datos, 
 * que tienen que ver con radicados y sus firmas
 * @author      Sixto Angel Pinz�n
 * @version     1.0
 */
public class RadicadoDAO {
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
  * @param	servDB  Corresponde al nombre del servicio de base de datos donde se 
  * fectuar�a la transacci�n
  * @return Retorna el string 'OK' en caso de coexion exitosa, o el error en 
  * caso contrario
  */
  public RadicadoDAO(String servDB){
    servBase = servDB;
    status="OK" ;
    
    //En capsua posibles errores de conexi�n
    try {
      Context ctx = new InitialContext();
      DataSource ds = (DataSource)ctx.lookup(servBase);
      conn = ds.getConnection();
     // System.out.println(conn+"<-creando-->"+servDB);
    } catch (Exception e)   {
      e.printStackTrace();
      status = e.toString();
   }
   
  }
 
 
 /** 
  * Retorna un objeto tipo Radicado con datos parciales del numero de radicado 
  * cuyo documento ingresa como parametro
  * @param  rad Corresponde al numero del radicado que se consulta
	* @return Radicado
  */ 
  public Radicado getRadi(String rad) {
    
    Radicado radAux;
    String path=null;
    //Encapsula problemas de ejecuci�n del query
    try  {
      String sql = "select radi_path from radicado where radi_nume_radi="+rad;
      Statement s = conn.createStatement();
      ResultSet rs = s.executeQuery(sql);
      
      if (rs.next()) {
        path = rs.getString("radi_path");
      }
      rs.close();
      cerrarConexion();
    } catch (SQLException e) {
      e.printStackTrace();
      radAux = new Radicado(rad,e.toString());
      
    }
    catch (Exception e) {
      e.printStackTrace();
      radAux = new Radicado(rad,e.toString());
      
    }
   
    radAux = new Radicado(rad,path);
    return radAux;  
  }
  
  
   /** 
  * Guarda la firma y la llave primaria empleada para un documento relacionado
  * con un radicado, realizada por un usuario
  * @param  rad Corresponde al numero del radicado cuyo documento relacionado se firma
  * @param  userDoc Documento de quien firma
	* @return "OK" en caso de transacción exitosa o el error presentado en caso contrario
  */ 
  public String guardarFirma(String rad,String userDoc, byte[]  firma, byte[] pk )  {
    String sql=null;
    String max=null;
     
      try {
        //Comienza buscando si ya se ha firmado el documento
        sql = "select * from SGD_FIRRAD_FIRMARADS where RADI_NUME_RADI="+rad +" and USUA_DOC='"+userDoc+"'";
        Statement s = conn.createStatement();
        ResultSet rs = s.executeQuery(sql);
        //Si ya existe firma
        if (rs.next()) {
          sql="update SGD_FIRRAD_FIRMARADS set SGD_FIRRAD_FIRMA = ? ,  SGD_FIRRAD_PK = ? , SGD_FIRRAD_FECHA = sysdate where  ";
          sql = sql + "RADI_NUME_RADI= ? and USUA_DOC= ? ";
          PreparedStatement ps = conn.prepareStatement(sql);
          ps.setBytes(1,firma);
          ps.setBytes(2,pk);
          ps.setLong(3,Long.parseLong(rad));
          ps.setString(4,userDoc);
          ps.execute();
          ps.close();
            
          /*sql="update SGD_FIRRAD_FIRMARADS set SGD_FIRRAD_FIRMA='"+firma + "', SGD_FIRRAD_FECHA = sysdate where  ";
          sql = sql + "RADI_NUME_RADI="+rad +" and USUA_DOC='"+userDoc+"'";
          s.executeUpdate(sql);*/
        }else {
          sql = "select max(SGD_FIRRAD_ID) + 1 as MAXIMO from SGD_FIRRAD_FIRMARADS ";
          rs = s.executeQuery(sql);
          if (rs.next()) 
            max=rs.getString("MAXIMO");
          else
            max="1";
            if ( max!=null){
              if (max.equals("null") || max.equals("NULL"))
               max="1";
            }
            else
              max="1";
             sql= "insert into SGD_FIRRAD_FIRMARADS (SGD_FIRRAD_ID,RADI_NUME_RADI,USUA_DOC,SGD_FIRRAD_FIRMA,SGD_FIRRAD_PK,SGD_FIRRAD_FECHA) values ( ? , ? , ? , ? , ? , sysdate ) ";              
             PreparedStatement ps = conn.prepareStatement(sql);
             ps.setLong(1,Long.parseLong(max));
             ps.setLong(2,Long.parseLong(rad));
             ps.setString(3,userDoc);
             ps.setBytes(4,firma);
             ps.setBytes(5,pk);
             ps.execute();
            /*  
            sql = "insert into SGD_FIRRAD_FIRMARADS (SGD_FIRRAD_ID,RADI_NUME_RADI,USUA_DOC,SGD_FIRRAD_FIRMA,SGD_FIRRAD_FECHA) values ";
            sql = sql + "("+ max+","+rad+",'"+userDoc+"','"+firma+"',sysdate) ";        */
            //s.executeUpdate(sql);
          }
          rs.close();
          cerrarConexion();
          HistoricoDAO hist = new HistoricoDAO(servBase); 
          UsuarioDAO usr = new UsuarioDAO(servBase);
          Usuario usrAux = usr.getUsuario(userDoc);
          usr.cerrarConexion();
          String resultHist = hist.guardarHistUnico(rad,usrAux,"40","Se firma digitalmente el documento");
          if (!resultHist.equals("OK")){
             System.out.println( resultHist);
             throw new Exception(resultHist);
          }
          
      }catch (Exception e){
           return (e.toString() + "-->" + sql + "-->" + max);
      }
        
     return "OK";
    
  }
  
  
   /** 
  * Retorna un vector de objetos tipo FirmaDocto con las firmas realizadas sobre un radicado
  * @param  rad Corresponde al numero del radicado para el que se consultan las firmas
  * @return Vector
  */ 
  public Vector getFirmasRad(String rad)  throws Exception {
    String sql=null;
    Vector vecAaux=null;
    FirmaDocto auxFirDoc;
    byte [] fimAux;
    byte [] pkAux;
    String usrDocAux;
   
    Usuario usrAux;
    vecAaux = new Vector();
    sql = "select * from SGD_FIRRAD_FIRMARADS where RADI_NUME_RADI=" + rad;
   // System.out.println(conn+"<--->");
    Statement s = conn.createStatement();
    ResultSet rs = s.executeQuery(sql);
    UsuarioDAO usrDAO = new UsuarioDAO(servBase);   
    while (rs.next()) {
      
      fimAux = rs.getBytes("SGD_FIRRAD_FIRMA");
      pkAux = rs.getBytes("SGD_FIRRAD_PK");
      usrDocAux = rs.getString("USUA_DOC");
      usrAux = usrDAO.getUsuario(usrDocAux);
      auxFirDoc = new FirmaDocto (usrAux,fimAux,pkAux);
      vecAaux.add(auxFirDoc);
    }
    rs.close();
    cerrarConexion();
    usrDAO.cerrarConexion();
      
    return (vecAaux);
    
  }
 
 
  /** 
  * Cierra la conexion con la base de datos obtenida
  */  
  public void cerrarConexion() throws Exception {
    conn.close();
  }
  

} 