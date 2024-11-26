package pk;
import javax.ejb.EJBLocalHome;
import javax.ejb.CreateException;

public interface FirmanteEJB1LocalHome extends EJBLocalHome 
{
  FirmanteEJB1Local create() throws CreateException;
}