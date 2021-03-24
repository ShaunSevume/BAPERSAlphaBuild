//TEMPOARAY CLASS FOR TESTING PURPOSES

package Staff;

import Job.TaskType;
import Staff.Staff;

import java.util.Vector;

public class Users {

    public static Vector<Staff> listOfStaff = new Vector<Staff>();

    public static void addStaff(Staff s) {
        listOfStaff.add(s);
    }

    public static void printStaff(){
        for(int i = 0; i < listOfStaff.size(); i++) {
            System.out.println("Staff ID: " + listOfStaff.elementAt(i).getStaffID() + ", Name: " + listOfStaff.elementAt(i).getName() + ", Role: " + listOfStaff.elementAt(i).getRole());
        }
    }

    public static Staff getStaff(int id){
        for(int i = 0; i < listOfStaff.size(); i++) {
            if (listOfStaff.elementAt(i).getStaffID() == id) {
                return listOfStaff.elementAt(i);
            }
        }
        return null;
    }

    public static boolean searchStaff(int id){
        for(int i = 0; i < listOfStaff.size(); i++) {
            if (listOfStaff.elementAt(i).getStaffID() == id) {
                return true;
            }
        }
        return false;
    }
}