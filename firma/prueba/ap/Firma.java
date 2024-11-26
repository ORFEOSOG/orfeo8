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
/*  Nombre Desarrollador   Correo                     Fecha   Modificacion           */
/*  Johnny Gonzalez     johnnygonzalezl@gmail.com 03-11-2006  Revisión y se ponen    */
/*                      mensajes en consola que indiquen el progreso de cada paso    */
/*************************************************************************************/
package ap;
import java.applet.AppletContext;
import java.io.FileOutputStream;
import java.io.PrintStream;
import java.net.URL;
import java.rmi.RemoteException;
import java.security.GeneralSecurityException;
import java.security.InvalidKeyException;
import java.security.KeyStore;
import java.security.NoSuchAlgorithmException;
import java.security.NoSuchProviderException;
import java.security.SignatureException;
import java.security.cert.X509Certificate;
import java.util.Date;
import java.util.Enumeration;
import java.util.Properties;
import java.util.StringTokenizer;
import java.util.Vector;
import javax.ejb.CreateException;
import javax.naming.Context;
import javax.naming.NamingException;
import javax.swing.JApplet;
import java.awt.Dimension;
import java.awt.Toolkit;
import java.awt.BorderLayout;
import javax.swing.JFrame;
import javax.swing.JOptionPane;
import javax.swing.UIManager;
import javax.swing.JEditorPane;
import java.awt.Rectangle;
import javax.swing.JButton;
import javax.swing.JPanel;
import javax.swing.BorderFactory;
import java.awt.Color;
import javax.swing.JLabel;
import javax.swing.JProgressBar;
import javax.swing.JList;
import javax.swing.ListModel;
import javax.swing.ListSelectionModel;
import java.awt.event.MouseListener;
import java.awt.event.MouseEvent;
import javax.swing.JTextArea;
import javax.swing.ImageIcon;
import javax.swing.filechooser.*;
import java.awt.Font;
import javax.rmi.*;
import pk.*;
import java.net.*;
import java.io.*;
import javax.swing.border.EtchedBorder;

import java.math.BigInteger;
import java.security.KeyFactory;
import java.security.KeyPair;
import java.security.KeyPairGenerator;
import java.security.PrivateKey;
import java.security.PublicKey;
import java.security.SecureRandom;
import java.security.Security;
import java.security.Signature;
import java.security.spec.RSAPrivateKeySpec;
import java.security.spec.RSAPublicKeySpec;
import java.security.cert.CertPath; 
import java.security.cert.Certificate; 
import java.security.cert.CertificateException; 
import java.security.cert.CertificateFactory;
import java.security.cert.*;



import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import javax.swing.JTextField;
import javax.swing.JPasswordField;
import javax.swing.JFileChooser;
import java.awt.event.ActionListener;
import java.awt.event.ActionEvent;





public class Firma extends JApplet 
{

  private JPanel Jfirma = new JPanel();
  private JProgressBar progreso;
  private ListModel listModel1 = new javax.swing.DefaultListModel();
  private JTextArea actividad = new JTextArea();
  private ImageIcon iconDescarga;
  private ImageIcon iconFirma;
  private int i;
  private String radicadosStr=null;
  private String usua_doc = null;
  private String pathsStr = null;
  private String servFirma = null;
  private String servBase = null;
  private InputStream certInputStr;
  
  private javax.naming.InitialContext ctx;
  private Vector vecRads = null;
  private Vector vecPaths = null;
  FirmanteEJB1 ejbObject;
  String localUrlHost = "/orfeofirma/";
  String remoteUrlHost = null;
  private JLabel jLabel1 = new JLabel();
  private JLabel jLabel2 = new JLabel();
  PrivateKey  signingKey;
  PublicKey   verifyKey;
  //Llave plublica de la entidad CA
  PublicKey   caKey;
  
  String keyStoreFile;
  String keyStorePass;
  KeyStore kstore;
  KeyPairGenerator    fact;
  KeyPair keyPair;
  private JPanel jvalidacion = new JPanel();
  private JLabel jLabel3 = new JLabel();
  private JPasswordField jPasswordField1 = new JPasswordField();
  private JButton jAceptar = new JButton();
  String newline = "\n";
  Rubrica rub;
  private JButton jButtonAbrirArchivo = new JButton();
  private JTextField jTextKeystore = new JTextField();
  private JFileChooser fc = new JFileChooser();
  /**
   * Define el formato del kystore
   */
  private static final String PKCS12_KEYSTORE_TYPE = "PKCS12"; 
  
  
  public Firma(){
  }


  
  
  javax.naming.InitialContext obtenerCtx2() throws Exception { 
    Properties p = new Properties();
    //jrun.naming.JRunContextFactory nam = new jrun.naming.JRunContextFactory();
    p.setProperty(Context.INITIAL_CONTEXT_FACTORY, "jrun.naming.JRunContextFactory");
    p.setProperty(Context.PROVIDER_URL, "localhost:2909");
    javax.naming.InitialContext contexto = new javax.naming.InitialContext(p);
    return contexto;
  }
  
  
  /** 
  * Funcion que genera un vector de objetos tipo radicado con la información de 
  * los numero de radicado que son enviados como parametro, el path hacia 
  * el documento y lo lamacena en el vector 'vecRads'
  **/
  private void cargarRadicados()  {
    //FirmanteEJB1Home ejbHome;
    String delimitador = ",";
    StringTokenizer st = new StringTokenizer(radicadosStr, delimitador);
    StringTokenizer st2 = new StringTokenizer(pathsStr, delimitador);
    
    Radicado radAux=null;
    String token = null;
    String tokenPath = null;
    vecRads = new Vector();
    vecPaths = new Vector(); 
    
    try{
      //Object obj = ctx.lookup("FirmanteEJB1");
      //ejbHome = (FirmanteEJB1Home)
      //PortableRemoteObject.narrow(obj,FirmanteEJB1Home.class);
      //ejbObject = ejbHome.create();
      
      while (st.hasMoreTokens()){
        token = st.nextToken();
        token = token.trim();
        tokenPath = st2.nextToken();
        tokenPath = tokenPath.trim();
        radAux= new Radicado(token,tokenPath);
        vecRads.add(radAux);
        
        
        
      }
    } catch (Exception e){
       JOptionPane.showMessageDialog(this,
                    "Problemas obteniedo al informacion de los radicados desde "
                    + "el applet  "  ,
                     "Error Message",
                    JOptionPane.ERROR_MESSAGE);
       e.printStackTrace();  
    }
    
    
    
   
    progreso.setMaximum( vecRads.size());
    
  }
  

  /** 
  * Funcion que realiza la firma del documento invocando el servlet que realiza 
  * esta actividad, llamando la url del servidor que fue enviada como parametro 
  * para esto, utilizando un onjeto de tipo radicado que es enviado como 
  * parameto al servlet
  * @return Retorna un string con el resusultado de la operacion caso de exito 
  * 'OK' en caso contrario genera una excepcion
  **/  
  private String  firmarDocto(Radicado raxAux) throws Exception {
     byte[] firma;
    String result=null;
    firma = rub.getSignFromUrl(remoteUrlHost + raxAux.getRadiPath());
    raxAux.setFirma(firma);
    raxAux.setDocFirmante(usua_doc);
    raxAux.setAccion("FIRMAR");
    raxAux.setServBase(servBase);
    raxAux.setpkEncoded(rub.getPkEncoded());
    result = sendObject(raxAux);
     
    if (result.indexOf("OK") == -1) {
        throw new Exception(result);
    }
    
    //termina proceso de firma digital
    //Acá debería limpiar objetos para que no dejar en memoria información privada
    
    return (result);
     
  }
     
 
  /** 
  * Funcion que realiza el proceso de firma sobre los documentos
  **/
  private void procesarDoctos()  {
   Radicado raxAux=null;
   String statusFirma=null;
    try{
      int i=1;
      //Recorre el vector de radicados enviado como parametro
      for( int iVectorCounter = 0; iVectorCounter < vecRads.size(); iVectorCounter++ ) {
        
        raxAux =  (Radicado)vecRads.get(iVectorCounter);
          System.out.println("Firma.procesarDoctos: Procesa Radicado: " + 
            raxAux.getRadiNume());
        //descargando.setText("Descargando " + raxAux.getRadiNume() );
        //descargando.setVisible(false);
        //Invoca a la fucnón que realiza la firma y despliega el status obtenido
        statusFirma=firmarDocto(raxAux);
        actividad.append(raxAux.getRadiNume() + newline );
        progreso.setValue(i);
        System.out.println("Firma.procesarDoctos: Procesa Satisfactoriamente el" + 
                            " Radicado: "  +  raxAux.getRadiNume());
        i++;
      }
    }catch (Exception e)  {
      actividad.append("Error firmando " + raxAux.getRadiNume()+ "-->" +e + newline);
      System.out.println("Error firmando " + raxAux.getRadiNume() +e); 
      if (statusFirma.indexOf("PROBLEMA_URL")!=-1 || 
          statusFirma.indexOf("PROBLEMA_CONEXION")!=-1 || 
              statusFirma.indexOf("PROBLEMA_CLASE")!=-1) {
        return;
      }
     
      
    }
    
    
  }
  
   /** 
  * Carga los parametros que son enviados al applet, esto son los radicado a firmar,
  * el path de sus documentos, do documento de identidad de quien firma, 
  * el servidor donde se procesa la firma y el login de quien firma.
  */ 
    public void init(){
    try{
      jbInit();
      radicadosStr = getParameter("radicados");
      usua_doc = getParameter("usua_doc"); 
      pathsStr = getParameter("paths"); 
      servFirma = getParameter("servidor"); 
      servBase = getParameter("usuario"); 
      remoteUrlHost =  getParameter("servweb"); 
      cargarRadicados();
      
      System.out.println("*****************************************************");
      System.out.println("Firma.init: Inicio del proceso de firma para el usuario: " 
          + usua_doc);
      //procesarDoctos();
      
      //downloadDocs();
      
    } catch(Exception e){
        e.printStackTrace();
    }
  }

  private void jbInit() throws Exception
  {
    i=0;
    
     // = this.class.getResourceAsStream("/imagenes/descarga2.gif");
     certInputStr = Firma.class.getResourceAsStream("/certificados/ca.cer");
     if (certInputStr==null){
         JOptionPane.showMessageDialog(this,
                    "Problemas obteniendo el achivo fisico del certificado del CA" ,
                     "Error Message",
                    JOptionPane.ERROR_MESSAGE);
              
                return;
          
      }
      System.out.println("Firma.jbInit: Carga certificado de la CA.");
      
     /*
       try
      {
        //CertificateFactory fact = CertificateFactory.getInstance("X.509");
       // FileInputStream fis = new FileInputStream ("/imagenes/descarga2.gif");
        InputStream s = this.class.getResourceAsStream("/imagenes/descarga2.gif");
        
        //X509Certificate cert = (X509Certificate)fact.generateCertificate(fis);
        //caKey = cert.getPublicKey();
      }
      catch (FileNotFoundException excer)
      {
         JOptionPane.showMessageDialog(this,
                    "Prrrrroblemas el achivo fisico del certificado del CA" ,
                     "Error Message",
                    JOptionPane.ERROR_MESSAGE);
              
                return;
          
      }
    */
    iconDescarga = createImageIcon("/imagenes/descarga2.gif","Descargando");
    iconFirma = createImageIcon("/imagenes/pen.gif","Firmando");
    progreso = new JProgressBar(0,0);
    this.getContentPane().setLayout(null);
    Jfirma.setBounds(new Rectangle(40, 30, 265, 325));
    Jfirma.setBorder(BorderFactory.createLineBorder(Color.black, 2));
    Jfirma.setLayout(null);
    progreso.setBounds(new Rectangle(15, 295, 235, 15));
    progreso.setStringPainted(true);
    actividad.setBounds(new Rectangle(15, 95, 235, 190));
    actividad.setEditable(false);
    actividad.setBorder(BorderFactory.createEtchedBorder(EtchedBorder.LOWERED));
    jLabel1.setText("Procesados:");
    jLabel1.setBounds(new Rectangle(30, 75, 85, 15));
    jLabel2.setText("Proceso de Firma de Documentos");
    jLabel2.setBounds(new Rectangle(30, 20, 220, 20));
    jvalidacion.setBounds(new Rectangle(40, 70, 260, 220));
    jvalidacion.setBorder(BorderFactory.createLineBorder(Color.black, 2));
    jvalidacion.setLayout(null);
    jLabel3.setText("Clave");
    jLabel3.setBounds(new Rectangle(25, 105, 65, 15));
    jPasswordField1.setBounds(new Rectangle(25, 135, 120, 25));
    jAceptar.setText("Aceptar");
    jAceptar.setBounds(new Rectangle(25, 175, 90, 20));
    jAceptar.addMouseListener(new java.awt.event.MouseAdapter()
      {
        public void mouseClicked(MouseEvent e)
        {
          jAceptar_mouseClicked(e);
        }
      });
    jButtonAbrirArchivo.setText("Seleccionar Keystore......");
    jButtonAbrirArchivo.setBounds(new Rectangle(25, 25, 180, 30));
    jButtonAbrirArchivo.addActionListener(new ActionListener()
      {
        public void actionPerformed(ActionEvent e)
        {
          jButtonAbrirArchivo_actionPerformed(e);
        }
      });
    jTextKeystore.setBounds(new Rectangle(25, 70, 205, 20));
    Jfirma.add(jLabel2, null);
    Jfirma.add(jLabel1, null);
    Jfirma.add(actividad, null);
    Jfirma.add(progreso, null);
    jvalidacion.add(jTextKeystore, null);
    jvalidacion.add(jButtonAbrirArchivo, null);
    jvalidacion.add(jAceptar, null);
    jvalidacion.add(jPasswordField1, null);
    jvalidacion.add(jLabel3, null);
    this.getContentPane().add(jvalidacion, null);
    this.getContentPane().add(Jfirma, null);
    Jfirma.setVisible(false);
     
    
    
  }




  
  /** Returns an ImageIcon, or null if the path was invalid. */
    protected static ImageIcon createImageIcon(String path,
                                               String description) {
        java.net.URL imgURL = Firma.class.getResource(path);
        
        if (imgURL != null) {
            return new ImageIcon(imgURL, description);
        } else {
            System.err.println("Couldn't find file: " + path);
            return null;
        }
    }

private URLConnection getConnectionToServlet() throws IOException, MalformedURLException, Exception{
		URL servletURL = new URL("http://"+servFirma);
		URLConnection servletConnection = servletURL.openConnection();
		servletConnection.setUseCaches(false);
		servletConnection.setDefaultUseCaches (false);
		servletConnection.setDoInput(true);
		servletConnection.setDoOutput(true);
		((HttpURLConnection)servletConnection).setRequestMethod("POST");
		servletConnection.setRequestProperty("Content-Type", "application/octet-stream");
		servletConnection.setAllowUserInteraction(false);
		return servletConnection;
}
private String sendObject(Radicado rad){
		OutputStream out;
		ObjectOutputStream objectStream;
    String  msg = "";
 
		
      try
      {
        URLConnection connection = getConnectionToServlet();
        out = connection.getOutputStream();
   
        //now send the job object to the Servlet
        objectStream = new ObjectOutputStream(out);
        objectStream.writeObject(rad); // don't read POST in doget or you'll get responsecode 405
        objectStream.flush();
        objectStream.close();
        out.close();
        /*
        InputStream is = connection.getInputStream();
        ObjectInputStream inputFromServlet = new ObjectInputStream(is);
        msg = (String)inputFromServlet.readObject();
        
        */
        
        
        
        
       BufferedReader in = new BufferedReader(new InputStreamReader(connection.getInputStream()));
       System.out.println("Waiting for response..." + "\n"); 
       String line = "";
       while((line = in.readLine()) !=  null)
       {
         msg = msg + line;
       }
       
       
        
        
        
        
        
        
        
        
        
        
      }
      catch (MalformedURLException e)
      {
         JOptionPane.showMessageDialog(this,
                    "URL mal especificada",
                    "Error Message",
                    JOptionPane.ERROR_MESSAGE);
       e.printStackTrace();
       msg="PROBLEMA_URL";
       
        
      }
      catch (IOException e)
      {
         JOptionPane.showMessageDialog(this,
                    "Problemas estableciendo la comunicaci�n con el servlet",
                    "Error Message",
                    JOptionPane.ERROR_MESSAGE);
         e.printStackTrace();
         msg="PROBLEMA_CONEXION";
        
      }
      catch (ClassNotFoundException e)
      {
        JOptionPane.showMessageDialog(this,
                    "Problemas hallando una clase al establecer comunicaci�n con el servlet",
                    "Error Message",
                    JOptionPane.ERROR_MESSAGE);
         e.printStackTrace();
      }
      catch (Exception e)
      {
        JOptionPane.showMessageDialog(this,
                    "Problemas no determinado estableciendo la comunicaci�n con el servlet",
                    "Error Message",
                    JOptionPane.ERROR_MESSAGE);
         e.printStackTrace();
      msg="PROBLEMA_CLASE";
        
      }
 
			// get the inputstream (server response)
			//System.out.println(readTextInputStream(connection.getInputStream(),connection));
     
 
		
     return(msg);
	}
  
  private String readTextInputStream(InputStream is,URLConnection urlc){
		byte[] buf = new byte[1024];
		ByteArrayOutputStream bos = new ByteArrayOutputStream();
		try {
			int len = 0;
			while ((len = is.read(buf)) > 0) {
 
				bos.write(buf, 0, len);
			}
			// close the inputstream
			is.close();
		} catch (IOException e) {
			try {
				// now failing to read the inputstream does not mean the server did not send
				// any data, here is how you can read that data, this is needed for the same
				// reason mentioned above.
				((HttpURLConnection) urlc).getResponseCode();
				InputStream es = ((HttpURLConnection) urlc).getErrorStream();
				int ret = 0;
				// read the response body
				while ((ret = es.read(buf)) > 0) {
				}
				// close the errorstream
				es.close();
			} catch (IOException ex) {
          ex.printStackTrace();
			}
		}
		// TODO: check if there was an enc in the response
		//		if so use that to convert the byte[] to string
		//		instead of the default
		return new String(bos.toByteArray());
	}
  
  
 
  
  private boolean valEntradCmplt()  {
      if (jPasswordField1.getPassword().length==0)  {
        JOptionPane.showMessageDialog(this,
                    "Debe suministrar la contrase�a del keystore",
                    "Error Message",
                    JOptionPane.ERROR_MESSAGE);
      return false;
      
    }
    
    return true;
      
    
  }
  
  
  /** 
  * Funcion que valida que se pueda acceder al keystore especificado utilizando 
  * la clave proporcionada y se pueda obtener la llave privada
  * como tambien la cadena de certificacion
  * @return true en caso de ser exitoso el acceso, falso de lo contrario
  **/
  private boolean valAccesKeyst() {
    System.out.println("Firma.valAccesKeyst: Empieza validación de password p12.");
    keyStorePass = "";
    for (int i = 0; i < jPasswordField1.getPassword().length; i++) {
                keyStorePass = keyStorePass + (jPasswordField1.getPassword())[i];
                    
    }
    
        try
    {
      kstore = loadKeyStoreFromPFXFile(keyStoreFile,keyStorePass);
        
      
    }
    
    catch (IOException ex)
    {
       JOptionPane.showMessageDialog(this,
                    "No es posible acceder al dispositivo, puede que la contrase�a " 
                    + "no sea correcta o que el archivo no sea correcto.",
                    "Error Message",
                    JOptionPane.ERROR_MESSAGE);
                    
       ex.printStackTrace();
       return false;
    }
    catch (GeneralSecurityException ex)
    {
       JOptionPane.showMessageDialog(this,
                    "No es posible acceder al dispositivo, puede que la contrase�a "
                    +"no sea correcta o que el archivo no sea correcto.",
                    "Error Message",
                    JOptionPane.ERROR_MESSAGE);
       ex.printStackTrace();
       return false;
    }
    System.out.println("Firma.valAccesKeyst: Pasa validación de password p12.");
     try { 
                rub  =   getPrivateKeyAndCertChain(kstore, keyStorePass); 
              System.out.println("Firma.valAccesKeyst: Extrae llave privada y "
                  + "cadena de certificación de p12.");
                    
                    
            } catch (GeneralSecurityException gsex) { 
                JOptionPane.showMessageDialog(this,
                    "No es posible extraer la llave privada y la cadena de " 
                    + "certificaci�n. Probablemente la clave sea incorrecta",
                     "Error Message",
                    JOptionPane.ERROR_MESSAGE);
                gsex.printStackTrace();
                return false;
            }
  return true;
    
  }
  

  /** 
  * Funcion que valida que se pueda acceder al certificado almacenado en el 
  * keystore de quien trasta de firmar, cargar la cadena de certificacion,
  * y que haya sido correctamente firmado por la ca.
  * @return true en caso de ser exitoso el acceso, falso de lo contrario
  **/
  private boolean valAccesCertCA ()
  {
   System.out.println("Firma.valAccesKeyst: Inicia validación de acceso al "
                        +"certificado de la CA.");
      try
      {
        CertificateFactory fact = CertificateFactory.getInstance("X.509");
        X509Certificate cert = (X509Certificate)fact.generateCertificate(certInputStr);
        caKey = cert.getPublicKey();
        
      }
      
      catch (CertificateException excer)
      {
      JOptionPane.showMessageDialog(this,
                    "Problemas abriendo el certificado del CA" ,
                     "Error Message",
                    JOptionPane.ERROR_MESSAGE);
              
                return false;
        
      }
     // caKey = rub.certificationChain[rub.certificationChain.length-1].getPublicKey();
     try
     {
       rub.certificationChain[rub.certificationChain.length-1].verify(caKey);
       
     }
     catch (InvalidKeyException excer)
     {
         JOptionPane.showMessageDialog(this,
                    "Problemas comrobando la valicez del certificado InvalidKeyException  " ,
                     "Error Message",
                    JOptionPane.ERROR_MESSAGE);
              
                return false;
       
     }
     catch (SignatureException excer)
     {
        JOptionPane.showMessageDialog(this,
                    "Problemas comrobando la valicez del certificado SignatureException  " ,
                     "Error Message",
                    JOptionPane.ERROR_MESSAGE);
              
                return false; 
     }
     catch (NoSuchAlgorithmException excer)
     {
       JOptionPane.showMessageDialog(this,
                    "Problemas comrobando la valicez del certificado NoSuchAlgorithmException  " ,
                     "Error Message",
                    JOptionPane.ERROR_MESSAGE);
              
                return false; 
     }
     catch (NoSuchProviderException excer)
     {
        JOptionPane.showMessageDialog(this,
                    "Problemas comrobando la valicez del certificado NoSuchProviderException  " ,
                     "Error Message",
                    JOptionPane.ERROR_MESSAGE);
              
                return false; 
       
     }
     catch (CertificateException excer)
     {  
        JOptionPane.showMessageDialog(this,
                    "Problemas comrobando la valicez del certificado CertificateException  " ,
                     "Error Message",
                    JOptionPane.ERROR_MESSAGE);
              
                return false; 
       
     }
     catch (Exception excer ) 
     {
       JOptionPane.showMessageDialog(this,
                    "Problemas comrobando la valicez del certificado, formato de certificado desconocido  " ,
                     "Error Message",
                    JOptionPane.ERROR_MESSAGE);
              
                return false; 
       
     }
    
   
   
        
   
   
    
              
    return true;
   
    
  }
  
 
  /** 
  * Funcion que valida que el certificado de quien firma ho haya sido revocado o haya expirado por tiempo
  * @return true en caso de ser exitoso el acceso, falso de lo contrario
  **/  
  public boolean valValidezCert() {
    Radicado rad = new Radicado(null,null);
    rad.setCertificado(rub.certificationChain[rub.certificationChain.length-1]);
    rad.setAccion("COMPROBAR_CERT");
    String result = sendObject(rad);
     
    if (result.indexOf("OK")==-1) {
        JOptionPane.showMessageDialog(this,
                    result ,
                     "Error Message",
                    JOptionPane.ERROR_MESSAGE);
              
       return false; 
    }
    
    //Valida que no haya expirado por tiempo
    X509Certificate X509cert = ( X509Certificate)rub.certificationChain[rub.certificationChain.length-1];
    Date fechaHoy = new Date();
    Date fechaExpira =  X509cert.getNotAfter();
    
    if (fechaHoy.after(fechaExpira) ) {
      JOptionPane.showMessageDialog(this,
                    "El certificado expir� en" + fechaExpira ,
                     "Error Message",
                    JOptionPane.ERROR_MESSAGE);
      return false; 
    }
    
    
    return true;
  }
  
  
  /** 
  * Evento de aceptar luego de haber seleccionado el keystore desde el applet
  */
  private void jAceptar_mouseClicked(MouseEvent e)
  {
        //valida poder acceder al keystore y extraer la valle privada
       if (! valAccesKeyst())  
          return;
      //Valida la validez del keystore de quien firma respecto del certificado de la ca    
       if (!valAccesCertCA ())
          return;
      //Valida que el certificado no haya caducado
       if (!valValidezCert())
          return;
        
              
  
    
    
    jvalidacion.setVisible(false);
    Jfirma.setVisible(true);
   //Comienza el proceso de firma sobre los documentos                  
   procesarDoctos();
   
    
    
    //conFirmaDocs();
    
     
  }

  private void jButtonAbrirArchivo_actionPerformed(ActionEvent e) {
    int returnVal = fc.showOpenDialog(Firma.this);
  
    
    
    if (returnVal == JFileChooser.APPROVE_OPTION) {
                File file = fc.getSelectedFile();
                keyStoreFile = file.getAbsolutePath();
                //jTextKeystore.setText(file.getName());
                jTextKeystore.setText(keyStoreFile);
                jPasswordField1.setVisible(true);
    }
   

    
  }
  
 /** 
  * Carga un keystore desde un archivo .PFX or .P12 (el formato del archivo debe ser PKCS#12) 
  * utilizando la clave suministrada. 
  */ 
    private KeyStore loadKeyStoreFromPFXFile(String aFileName, String aKeyStorePassword) 
    throws GeneralSecurityException, IOException { 
        KeyStore keyStore = KeyStore.getInstance(PKCS12_KEYSTORE_TYPE); 
        FileInputStream keyStoreStream = new FileInputStream(aFileName); 
        char[] password = aKeyStorePassword.toCharArray(); 
        keyStore.load(keyStoreStream, password); 
        return keyStore; 
    }
    
    
    /** 
     * @return  retorna la clase que almacenar� la llave privada y la cadena de 
     * certificaci�n correspondiente a esta, extraida 
     * del keystore usando el password asignado para acceder al kystore que 
     * es el mismo para acceder a la llave privada 
     * que reposa en este. Se asume que el keystore contiene contiene tanto 
     * la cadena de certificaci�n como la llave privada,
     * si el certificado no contiene datos retorna nulo 
     */ 
    private Rubrica getPrivateKeyAndCertChain( 
        KeyStore aKeyStore, String aKeyPassword) 
    throws GeneralSecurityException { 
        char[] password = aKeyPassword.toCharArray(); 
        Enumeration aliasesEnum = aKeyStore.aliases(); 
        Rubrica result=null;
        while (aliasesEnum.hasMoreElements()) { 
            String alias = (String)aliasesEnum.nextElement(); 
            Certificate[] certificationChain = aKeyStore.getCertificateChain(alias); 
            PrivateKey privateKey = (PrivateKey) aKeyStore.getKey(alias, password); 
            result = new Rubrica(privateKey,certificationChain[0].getPublicKey()  ,certificationChain);
            /* JOptionPane.showMessageDialog(this,
                    alias ,
                     "Error Message",
                    JOptionPane.ERROR_MESSAGE);
           // result = new Rubrica(privateKey,certificationChain[certificationChain.length-1].getPublicKey()  ,certificationChain);
             JOptionPane.showMessageDialog(this,
                     certificationChain[0]  ,
                     "Error Message",
                    JOptionPane.ERROR_MESSAGE);
                    
                    JOptionPane.showMessageDialog(this,
                     certificationChain[1]  ,
                     "Error Message",
                    JOptionPane.ERROR_MESSAGE);
                    
                    JOptionPane.showMessageDialog(this,
                     "toma la cero"  ,
                     "Error Message",
                    JOptionPane.ERROR_MESSAGE);
            */
            return result; 
        } 
        return result; 
    } 
  
 private void cleanData()
 {
    signingKey = null;
    verifyKey = null;
    caKey = null;
    
    keyStoreFile = null;
    keyStorePass = null;
    kstore = null;
    fact = null;
    keyPair = null; 
    
    rub.clean();
 }
  
}