package Job;

import Customer.Customer;

import java.util.Vector;

public class Jobs {

    public static Vector<Job> listOfJobs = new Vector<Job>();

    public static void addJob(Job j){
        listOfJobs.add(j);
    }

    public static void printJobs(){
        for(int i = 0; i < listOfJobs.size(); i++) {
            System.out.println("Job ID: " + listOfJobs.elementAt(i).getJobID() + ", Customer: " + listOfJobs.elementAt(i).getCustomer() + ", Price: £" + listOfJobs.elementAt(i).getPrice() + ", Status: " + listOfJobs.elementAt(i).getStatus() + ", Deadline: " + listOfJobs.elementAt(i).getDeadline() + ", Start time: " + listOfJobs.elementAt(i).getStartTime() + ", Finish time: " + listOfJobs.elementAt(i).getFinishTime() + ", Time taken: "  + listOfJobs.elementAt(i).getTimeTaken() + " minutes.");
        }
    }

    public static Job getJob(int id){
        for(int i = 0; i < listOfJobs.size(); i++) {
            if (listOfJobs.elementAt(i).getJobID() == id) {
                return listOfJobs.elementAt(i);
            }
        }
        return null;
    }

    public static boolean searchJob (int id){
        for(int i = 0; i < listOfJobs.size(); i++) {
            if (listOfJobs.elementAt(i).getJobID() == id) {
                return true;
            }
        }
        return false;
    }

}
