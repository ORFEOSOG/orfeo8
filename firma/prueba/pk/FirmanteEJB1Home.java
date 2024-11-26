package pk;
import javax.ejb.EJBHome;
import java.rmi.RemoteException;
import javax.ejb.CreateException;

public interface FirmanteEJB1Home extends EJBHome 
{
  FirmanteEJB1 create() throws RemoteException, CreateException;
}