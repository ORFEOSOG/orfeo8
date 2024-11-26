package pk;
import java.util.Vector;
import javax.ejb.EJBLocalObject;

public interface FirmanteEJB1Local extends EJBLocalObject 
{
  

  String guardarFirma(String rad, String userDoc, byte[] firma, byte[] pk, String servDB)  ;

  Vector getFirmasRad(String rad, String servDB) throws Exception;

}