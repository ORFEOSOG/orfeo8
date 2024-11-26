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
import javax.servlet.*;
import javax.servlet.http.*;
import java.io.PrintWriter;
import java.io.IOException;

public class FirmaCtl extends HttpServlet 
{
  private static final String CONTENT_TYPE = "text/html; charset=ISO-8859-1";

  public void init(ServletConfig config) throws ServletException
  {
    super.init(config);
  }

  public void doGet(HttpServletRequest request, HttpServletResponse response) 
    throws ServletException, IOException
  {
    response.setContentType(CONTENT_TYPE);
    PrintWriter out = response.getWriter();
    out.println("<html>");
    out.println("<head><title>FirmaCtl</title></head>");
    out.println("<body>");
    out.println("<p>The servlet has received a GET. This is the replyy.</p>");
    out.println("<APPLET CODE=ap.Firma.class archive=prueba.jar,jrun.jar   width=500 height=400> ");
    out.println("<param 	name='radicados' value='20059050000505,20059050000525' /> ");
    out.println("</APPLET>");
    out.println("</body></html>");
    out.close();
  }
}