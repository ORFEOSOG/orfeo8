package pk;
import java.util.Vector;
import javax.ejb.EJBObject;
import java.rmi.RemoteException;

public interface FirmanteEJB1 extends EJBObject 
{
  Radicado getRadiCodi(String radicado, String servDB) throws RemoteException;

  
  String guardarFirma(String rad, String userDoc, byte[] firma, byte[] pk, String servDB) throws RemoteException;

  Vector getFirmasRad(String rad, String servDB) throws RemoteException, Exception;




}