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
import java.io.ByteArrayOutputStream;
import java.io.ObjectInputStream;
import java.io.PrintWriter;
import java.io.IOException;
import java.io.*;
import java.rmi.RemoteException;
import java.security.PublicKey;
import java.security.cert.CRLException;
import java.security.cert.CertificateException;
import java.security.cert.CertificateFactory;
import java.security.cert.X509CRL;
import java.text.*;
import java.util.*;
import javax.ejb.CreateException;
import javax.naming.Context;
import javax.naming.InitialContext;
import javax.naming.NamingException;
import javax.rmi.PortableRemoteObject;
import javax.servlet.*;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;



public class servletFirma extends HttpServlet 
{
  private static final String CONTENT_TYPE = "text/html; charset=ISO-8859-1";

  public void init(ServletConfig config) throws ServletException
  {
    super.init(config);
  }

 /** 
 * Recibe solicitudes de comprobación de las firmas de un radicado y escribe tags
 * php indicando la validez de estas, cambiando
 *  el valor de $swValido segun el resultado obtenido
 */
  
  public void doGet(HttpServletRequest request, HttpServletResponse response) 
  throws ServletException, IOException
  {
    PrintWriter out = response.getWriter();
    String radicado = (String)request.getParameter("radicado");
    String servbase = (String)request.getParameter("servbase");
    String servBodega = (String)request.getParameter("servbodega");
    servBodega = servBodega.replace('|','/');
      
    Vector vecRads;
    FirmaDocto auxFirDoc;
    Rubrica rubAux = new Rubrica(null,null);
   
    
    if (radicado!=null){
      out.println("<? $swValido = true;?>");
      
    
    
         try
      {
        //Se obtiene el radicado a analizar
       
        //Declara los objetos de conexi�n con los EJB encargados de registrar la transacci�n
        FirmanteEJB1 ejbObject = null;
        FirmanteEJB1Home ejbHome;
        //Obtiene el contexto de la conexion el el EJB
        Context ctx = new InitialContext();
        Object obj = ctx.lookup("FirmanteEJB1");
        ejbHome = (FirmanteEJB1Home)
        PortableRemoteObject.narrow(obj,FirmanteEJB1Home.class);
        ejbObject = ejbHome.create();
        //Invoca la funci�n de actualizaci�n de la firma
        //System.out.println(radicado+"<--->"+servbase);
        vecRads = ejbObject.getFirmasRad(radicado,servbase);
        Radicado rad = ejbObject.getRadiCodi(radicado,servbase);
        for( int iVectorCounter = 0; iVectorCounter < vecRads.size(); iVectorCounter++ ) {
          auxFirDoc =  (FirmaDocto)vecRads.get(iVectorCounter);
          rubAux.setPkEncoded(auxFirDoc.pkEncoded);
          if (!(rubAux.verifySignUrl(servBodega + rad.getRadiPath(), auxFirDoc.firma)))
             out.println("<? $swValido = false; $docNoValida[]="+ auxFirDoc.usr.getUsuaDoc() + 
                  ";$nombNoValida[]='"+auxFirDoc.usr.getUsuaNomb() +"'   ?>");
                  
          
        }
        
        
    
      }
      catch (RemoteException e){
         e.printStackTrace();
         out.println("<? $swValido = false; echo('Problemas De manejo remoto del EJB " 
         + e.toString() +"'); ?>" );
      }
      catch (NamingException e){
        e.printStackTrace();
        out.println("<? $swValido = false; echo('Problemas resolviendo en nombre del servicio del EJB " 
        + e.toString() +"'); ?>" );
      }
      catch (CreateException e){
        e.printStackTrace();
        out.println("<? $swValido = false; echo('Problemas obteniendo la instancia del EJB " 
        + e.toString() +"'); ?>" );
      }
      catch (ClassNotFoundException e){
        e.printStackTrace();
        out.println("<? $swValido = false; echo('Problemas obteniendo la instancia del la clase Radicado " 
        + e.toString() +"'); ?>" );
        
      }
      catch (IOException e){
        e.printStackTrace();
        out.println("<? $swValido = false; echo('Problemas accediendo al servidor de comprobaci�n de firma " 
        + e.toString() +"'); ?>" );
        
      }
      catch (Exception e){
        e.printStackTrace();
        out.println("<? $swValido = false; echo('Problemas comprobando la firma " 
        + e.toString()  +"'); ?>" );
        
      }
    }
    else
      out.println("<? echo ('<BR> No llego radicado para confirmacion de firma'); $swValido = false;?>");
    
    
  }
  
  
  /** 
 * Debuelve a la conexi�n un String con el estado de la transacci�n
 * @param	response 	HttpServletRequest  Corresponde al contexto de respuesta del Servlet
 * @return   void	
 */ 
  private void responder(HttpServletResponse response, String Mensaje) throws Exception {
       //Crea  los objetos gestionadores de la respuesta
      OutputStream os = response.getOutputStream(); 
      ObjectOutputStream outputToApplet = new ObjectOutputStream(os);
      //Escribe la respuesta
      outputToApplet.writeObject(Mensaje);
      outputToApplet.flush();
      //Cierra  los objetos gestionadores de la respuesta
      outputToApplet.close();
      os.close();
    
  }
 
 
 /** 
 * Obtiene de conexi�n el objeto de tipo Radicado con el que se efectuar� la transacci�n
 * @param	request 	HttpServletRequest  Corresponde al par�metro de solicitud de requerimiento
 * @return   void	
 */  
  private Radicado obtenerRad(HttpServletRequest request)  throws Exception {
      //Crea el objeto que habr� de obtener el stram de entrada
      ObjectInputStream in = null;
      ByteArrayOutputStream bos = new ByteArrayOutputStream();
      InputStream hIn = request.getInputStream();
      //Almacena los bytes del Stream
      byte[] buf = new byte[1024];
      int len;
      
      while ((len = hIn.read(buf)) > 0) {
        bos.write(buf, 0, len);
      }
                
      // Inicializa al objeto representante del stream de datos que ingresa
      in = new ObjectInputStream(new ByteArrayInputStream(bos.toByteArray() ));
      // Obtiene el Objeto Radicado
      Radicado r = (Radicado) in.readObject();
      //Cierra los objetos gestionadores del Stream de entrada
      in.close();
      bos.reset();
      return r;   
    
  }
  

  /** 
 * Actualiza la base de datos con la firma del radicado que llega como par�metro en el request. 
 * Debuelve a la conexi�n un String con el estado de la transacci�n
 * @param	request 	HttpServletRequest  Corresponde al la solicitud realizada al servlet
 * @param	request 	HttpServletRequest  Corresponde al contexto de respuesta del Servlet
 * @return   void	
 */ 
  public void firmar(Radicado rad, HttpServletResponse response){
    try
    {
      try
      {
        //Se obtiene el radicado a analizar
       
        //Declara los objetos de conexi�n con los EJB encargados de registrar la transacci�n
        FirmanteEJB1 ejbObject = null;
        pk.FirmanteEJB1Home  ejbHome;
           
        Object obj=null;
        //Obtiene el contexto de la conexion el el EJB
        Context ctx = new InitialContext();
        //ejbHome = (FirmanteEJB1Home)ctx.lookup("FirmanteEJB1");
        obj = ctx.lookup("FirmanteEJB1");
        //obj = ctx.lookup("local/FirmanteEJB1");
        
        //System.out.println("Contextosssfsf..."+ obj);
        //ejbHome = (pk.FirmanteEJB1Home)obj;
         //System.out.println("Contextosssfsf222..."+ obj);
        ejbHome = (pk.FirmanteEJB1Home) PortableRemoteObject.narrow(obj,pk.FirmanteEJB1Home.class);
        // System.out.println("ejbhome..." );
        ejbObject = ejbHome.create();
        // System.out.println("objeto...");
        //Invoca la funci�n de actualizaci�n de la firma
       // System.out.println("+++" + rad.getRadiNume()+ "+++" + rad.getDocFirmante() + "+++" +  rad.getFirma() + "+++" +  rad.getPkEncoded() + "+++" +  rad.getServBase());
        String result = ejbObject.guardarFirma(rad.getRadiNume(), rad.getDocFirmante(),  
                  rad.getFirma(), rad.getPkEncoded(), rad.getServBase());
        //System.out.println("result..." + result);
         
        responder(response, result);
    
      }
      catch (RemoteException e){
         e.printStackTrace();
         responder(response, "Problemas De manejo remoto del EJB " + e.toString());
      }
      catch (NamingException e){
      e.printStackTrace(); 
        responder(response, "Problemas resolviendo en nombre del servicio del EJB " 
        + e.toString());
      }
      catch (CreateException e){
      e.printStackTrace();
        responder(response, "Problemas obteniendo la instancia del EJB "
        + e.toString());
      }
      catch (ClassNotFoundException e){
        e.printStackTrace();
        responder(response, "Problemas obteniendo la instancia del la clase Radicado " 
        + e.toString());
      }
      catch (IOException e){
        e.printStackTrace();
      }
      catch (Exception e){
        e.printStackTrace();
        
      }
    }
    catch (Exception e)
    {
      
    }
 }
 
 
 /** 
 * Examina si un certificado es v�lido de acuerdo a la lista de revocaci�n suminstrada en formatop X509
 * @param	request 	HttpServletRequest  Corresponde al la solicitud realizada al servlet
 * @param	request 	HttpServletRequest  Corresponde al contexto de respuesta del Servlet
 * @return   void	
 */ 
  
 public void valValidezCert( Radicado rad, HttpServletResponse response) {
   java.security.cert.Certificate cert;
   String  resultado="OK"; 
   cert=rad.getCertificado();
    try
    {
     try
     {
       CertificateFactory certificatefactory =  CertificateFactory.getInstance("X.509");
       FileInputStream fileinputstream = new FileInputStream("/usr/local/firma/crl.pem");
       X509CRL x509crl = (X509CRL)certificatefactory.generateCRL(fileinputstream);
       if (x509crl.isRevoked(cert))
        throw new CertificateException("Certificado Revocado");
      responder(response, resultado);
      }
    
     catch (CRLException e) {
        resultado ="Problemas accediendo a la lista de revocacion   ";
        responder(response, resultado);
     }
     catch (FileNotFoundException e)  {
        resultado = "Problemas hallando la lista de revocacion";
        responder(response, resultado);
     }
     catch (CertificateException e) {
          resultado = "Problemas comprobando la valicez del certificado, respecto de la lista de revocacion"; 
          responder(response, resultado);      
     }
     catch (Exception e){
      e.printStackTrace();
    }
    }catch (Exception e){
      e.printStackTrace();
    }
    
     
     
  }
  
  /** 
 * Recibe solicitudes de almacenar la firma de un documento asociado con un radicado o de comprobar la validez de un certificado respecto de la lista de revocación
 * la operación a realizar se decide de acuerdo al parámetro de entrada de tipo Radicado, de donde invoca el método getaccion que puede ser
 * valer 'FIRMAR' o 'COMPROBAR_CERT'
 */ 
   public void doPost(HttpServletRequest request,
                      HttpServletResponse response)
        throws IOException, ServletException   {   
            //System.out.println("Entra post...");
         try
         {
           Radicado rad = obtenerRad (request);
           String accion = rad.getAccion();
          // System.out.println(accion);
           if (accion.equals("FIRMAR")){
             firmar( rad,  response);
           }
           
           if (accion.equals("COMPROBAR_CERT")){
             
             valValidezCert( rad,  response);
           }
           
           
         }
         catch (Exception e)
         {
         e.printStackTrace();
           
         }
       //System.out.println("Termina post...");
        
         
    }
  
}