package pk;
import java.util.Vector;
import javax.ejb.SessionBean;
import javax.ejb.SessionContext;

public class FirmanteEJB1Bean implements SessionBean 
{
  public void ejbCreate()
  {
  }

  public void ejbActivate()
  {
  }

  public void ejbPassivate()
  {
  }

  public void ejbRemove()
  {
  }

  public void setSessionContext(SessionContext ctx)
  {
  }

  public Radicado getRadiCodi(String radicado, String servDB)
  {
    RadicadoDAO radDao = new RadicadoDAO(servDB);
    return (radDao.getRadi(radicado));
  }

  public String guardarFirma(String rad, String userDoc, byte[] firma, byte[] pk, String servDB) 
  {
     RadicadoDAO radDao = new RadicadoDAO(servDB);
     return radDao.guardarFirma( rad,  userDoc,  firma, pk);
     
  }

  public Vector getFirmasRad(String rad, String servDB) throws Exception {
   // System.out.println("Entra a la funcion de obtener firmas "  + rad + "---" + servDB);
    RadicadoDAO radDao = new RadicadoDAO(servDB);
    return radDao.getFirmasRad(rad);
    
  }






}