//TEMPOARAY CLASS FOR TESTING PURPOSES

package Customer;

import Job.TaskType;

import java.util.Vector;

public class Customers {

    public static Vector<Customer> listOfCustomers = new Vector<Customer>();

    public static void addCustomer(Customer c){
        listOfCustomers.add(c);
    }

    public static void printCustomers(){
        for(int i = 0; i < listOfCustomers.size(); i++) {
            System.out.println("Customer ID: " + listOfCustomers.elementAt(i).getCustomerID() + ", Name: " + listOfCustomers.elementAt(i).getName() + ", Contact Name: " + listOfCustomers.elementAt(i).getContactName() + ", Address: " + listOfCustomers.elementAt(i).getAddress() + ", Phone Number: " + listOfCustomers.elementAt(i).getPhoneNo());
        }
    }

    public static Customer getCustomer(int id){
        for(int i = 0; i < listOfCustomers.size(); i++) {
            if (listOfCustomers.elementAt(i).getCustomerID() == id) {
                return listOfCustomers.elementAt(i);
            }
        }
        return null;
    }

    public static boolean searchCustomer(int id){
        for(int i = 0; i < listOfCustomers.size(); i++) {
            if (listOfCustomers.elementAt(i).getCustomerID() == id) {
                return true;
            }
        }
        return false;
    }
}
