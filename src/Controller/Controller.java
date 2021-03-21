package Controller;

import Customer.*;
import Job.*;
import Staff.*;

import java.sql.Timestamp;
import java.util.*;

public class Controller {

    private static int counter;

    public Controller(){
        counter = 0;
        //CreateTaskType();
        //CreateCustomer();
        //CreateStaff();
        CreateDummyData();
        //CreateTask();
        CreateJob();
        //TimeTest();
    }

    public static void main(String[] args) {
        new Controller();
    }

    public String[] GetInp(){ //Method for getting user input, splitting it up by spaces and assigning it to an array created due to multiple usage throughout other methods.
        Scanner sc = new Scanner(System.in); //Creates a scanner object
        String str = sc.nextLine(); //Read user input, assign it to a string object
        String[] arr = str.split(" "); //Split the string by spaces and put words into an array
        return arr;
    }

    public void CreateTaskType(){ //Create a new TaskType to add to the list of tasks which can then be added to jobs.

        //Get user input
        System.out.println("Enter new task details (description, location, price, duration): "); //Prompt the user to enter task details (description, location and price)
        String[] arr = GetInp();

        //Create tasks with user input and add to list of tasks
        TaskType tt = new TaskType(arr[0], counter++,arr[1],  Float.parseFloat(arr[2]), Integer.parseInt(arr[3]));
        Tasks.addTask(tt);

        //Create task with test input and add to list of tasks
        TaskType tt2 = new TaskType("Test String", counter++,"Lorem Ipsum",  new Random().nextInt(100 + 1), new Random().nextInt((30+1) + 300)); //Create a new task with test inputs
        Tasks.addTask(tt2);

        //Print them out to check if they have been added to the list.
        Tasks.printTasks();
    }

    public void CreateTask(){ //(A messy test class, but it works regardless)
        //Show list of tasktypes
        Tasks.printTasks();

        System.out.println("Select a task to create: ");
        Scanner sc = new Scanner(System.in);
        int tid = sc.nextInt(); //Get user's input as an int to search list of tasktypes with it
        Task t = new Task(Tasks.getTask(tid), new Random().nextInt(100 + 1));

        System.out.println("TTID: " + t.getTaskTypeID() + "TID: " + t.getTaskID());
    }

    public void CreateCustomer(){

        //Get user input
        System.out.println("Enter new customer details (name, contactname, address, phone number): "); //[!] contactName is optional, so either a constructor that can be overridden is needed or this code here has to deal with the possibility.
        String[] arr = GetInp();

        //Create customer with user input and add to list of customers
        Customer c = new Customer(new Random().nextInt(100 + 1), arr[1],arr[2],arr[3],arr[4]);
        Customers.addCustomer(c);

        //Print them out to check if they have been added to the list.
        Customers.printCustomers();
    }

    public void CreateStaff(){
        //Get user input
        System.out.println("Enter new staff details (name, role): "); //[!] contactName is optional, so either a constructor that can be overridden is needed or this code here has to deal with the possibility.
        String[] arr = GetInp();

        //Create staff member with user input and add to list of staff
        Staff s = new Staff(new Random().nextInt(100 + 1), arr[0],arr[1]);
        Users.addStaff(s);

        //Print them out to check if they have been added to the list.
        Users.printStaff();
    }

    public void CreateJob(){
        //[1] Create a new Job
        Job j = new Job(new Random().nextInt(100 + 1));

        //[2] Get all the customers and prompt user to select ONE
        Customers.printCustomers(); //Show the list of available customers

        boolean found = false; //To indicate when a valid customer has been selected.
        int cid = 0; //The customer ID the user will enter

        while(!found) {
            System.out.println("Please Select a customer: "); //Prompt to select a customer
            Scanner sc = new Scanner(System.in);
            cid = sc.nextInt(); //Get user's input as an int to search list of customers with it
            if (Customers.searchCustomer(cid)) { //If a customer is found...
                found = true; //The loop can exit.
            }
        }
        j.setCustomer(cid);
        System.out.println("VALID CUSTOMER FOUND WITH ID: " + j.getCustomer()); //Testing point 1: Finding a valid customer, assigned to the variable 'cid'.

        //[3] Get all the tasktypes and prompt the user to select MULTIPLE
        Tasks.printTasks(); //Show the list of available tasks
        System.out.println("Please select tasks to add: ");

        String[] arr = GetInp(); //As the user will select multiple tasks, each input value will be seperated by a space and put into an array.

        //[4] For each tasktype selected (held in the array of user input), create a new task of that tasktype and add it to the list of tasks within the job that is being created.
        for(int i=0;i<arr.length;i++){
            Task t = new Task(Tasks.getTask(Integer.valueOf(arr[i])), new Random().nextInt(100 + 1));
            j.addTask(t);
        }

        //Testing point 2: Using the input from the user to select specific tasktypes, create tasks of the corresponding type and add them to the job.
        System.out.println("TASKS IN THIS JOB:");
        j.printTasks();

        //[5] Adding special instructions
        System.out.println("Please add any special instructions: ");
        Scanner sc = new Scanner(System.in);
        String si = sc.nextLine();
        j.setSpecialInstructions(si);
        System.out.println("Your specified instructions: "  + j.getSpecialInstructions()); //Testing point 3

        //[6] Setting Urgency and Calculating minimum deadline
        System.out.println("How urgent is the job? 0 = Normal (24h), 1 = Urgent (6h), 2 = Super Urgent (3h), 3 = Express (<3h)");
        Timestamp deadline = deadline = calculateDeadline(1440); //Default deadline is 24h.
        int urgency = sc.nextInt();
        switch(urgency){
            case 0:
                deadline = calculateDeadline(1440); //A bit dumb to do but it's here for the sake of the program flow.
                break;
            case 1:
                deadline = calculateDeadline(360);
                break;
            case 2:
                deadline = calculateDeadline(180);
                break;
            case 3:
                System.out.println("Enter the express time (minutes): ");
                int et = sc.nextInt();
                deadline = calculateDeadline(et);
        }
        System.out.println("The deadline will be: " + deadline); //Testing point 4

        //Calculating the minimum deadline
        Timestamp min = calculateDeadline(j.calculateDuration());
        System.out.println("Minimum deadline: " + min);

        //Testing point 5
        if(deadline.before(min)){
            System.out.println("ALERT!!! The deadline specified is earlier than the minimum time it would take for all tasks to complete!");
            //[!] This code will be needed for alert generation.
            //[!] I think either the system needs to be checking every so often if the duration of the uncompleted tasks will exceed the deadline (could be expensive on the system)
            //    OR the check should be performed after each task is marked as complete. (less expensive but potential for alerts to be generated late!)
        }
        j.setUrgency(urgency);
        j.setDeadline(deadline);

        //[7] Calculating price
        System.out.println("The current total price is £" + j.calculatePrice());

        //[8] Calculating price for a fixed discount customer
        //makeFixed(j,20);
        makeFlexible(j, 20);
        if(Customers.getCustomer(j.getCustomer()).isValuedCustomer()){ //Check if the customer is valued
            switch(Customers.getCustomer(j.getCustomer()).getDiscountType()){ //Check the discount type they have if they ARE valued
                case 1:
                    System.out.println("The discounted price will be £" + (j.calculatePrice() - Customers.getCustomer(j.getCustomer()).getDiscountAmount()));
                    break;
                case 2:
                    System.out.println("VARIABLE");
                    break;
                case 3:
                    System.out.println("FLEXIBLE");
                    break;
            }
        }
    }

    public void makeFixed(Job j, int d){ //To make the chosen customer have a fixed discount plan of a chosen amount
        Customers.getCustomer(j.getCustomer()).setValuedCustomer(true);
        Customers.getCustomer(j.getCustomer()).setDiscountType(1);
        Customers.getCustomer(j.getCustomer()).setDiscountAmount(d);

        //TEST OUTPUTS (they are working, don't worry)
        //System.out.println("Is Customer " + j.getCustomer() + " valued? " + Customers.getCustomer(j.getCustomer()).isValuedCustomer());
        //System.out.println("Customer " + Customers.getCustomer(j.getCustomer()).getCustomerID() + " has discount type " + Customers.getCustomer(j.getCustomer()).getDiscountType());
        //System.out.println("Customer " + Customers.getCustomer(j.getCustomer()).getCustomerID() + " has a discount amount of " + Customers.getCustomer(j.getCustomer()).getDiscountAmount());
    }

    public void makeFlexible(Job j, int d){ //To make the chosen customer have a flexible discount plan of a chosen amount
        Customers.getCustomer(j.getCustomer()).setValuedCustomer(true);
        Customers.getCustomer(j.getCustomer()).setDiscountType(3);
        Customers.getCustomer(j.getCustomer()).setWallet(d);

        //Testing BandCheck (it works)
        //System.out.println("Band check? " + Customers.getCustomer(j.getCustomer()).checkBands(new String[] {"1" ,"3", "2", "4"}));

        String[] b = {"10" ,"20", "30", "40"}; //Making a random band of prices
        String[] p = {"10" ,"20", "30", "40"}; //Making a random band of corresponding discount rates

        //TBC make a map instead of vector lol

        if(Customers.getCustomer(j.getCustomer()).checkBands(b)){
            Customers.getCustomer(j.getCustomer()).setBands(b);
        }
    }

    public Timestamp calculateDeadline(int mins){
        Timestamp now = new Timestamp(new Date().getTime());
        Calendar cal = Calendar.getInstance();
        cal.setTimeInMillis(now.getTime());
        cal.add(Calendar.MINUTE, mins);
        Timestamp dl = new Timestamp(cal.getTime().getTime());
        return dl;
    }

    public void CreateDummyData(){
        //Create some test tasks and customers
        for(int i=0; i<5; i++) {
            TaskType t = new TaskType("Task " + Integer.toString(++counter), counter, "Lorem Ipsum", new Random().nextInt(100 + 1), new Random().nextInt((30+1) + 300)); //Create a new task with test inputs
            Tasks.addTask(t);

            Customer c = new Customer(new Random().nextInt(100 + 1),"Customer " + Integer.toString(counter), "Contact " + Integer.toString(new Random().nextInt(100 + 1)), "Address " + Integer.toString(new Random().nextInt(100 + 1)), "07" + Integer.toString(new Random().nextInt((100000000 + 1) + 999999999)));
            Customers.addCustomer(c);

            Staff s = new Staff(new Random().nextInt(100 + 1), "Staff " + Integer.toString(counter), "Role " + Integer.toString(new Random().nextInt(5 + 1)));
            Users.addStaff(s);
        }
        //Tasks.printTasks();
        //Customers.printCustomers();
        //Users.printStaff();
    }

    public void TimeTest(){
        //Get the current time as a timestamp
        Timestamp timestamp = new Timestamp(new Date().getTime());
        System.out.println(timestamp);

        //Convert the current time as a timestamp to an instance in the calendar class, as it has the methods for adding/subtracting time
        Calendar cal = Calendar.getInstance();
        cal.setTimeInMillis(timestamp.getTime());

        // add 5 hours using the calendar class
        cal.add(Calendar.MINUTE, 60);

        //Convert the calander instance back into a timestamp
        timestamp = new Timestamp(cal.getTime().getTime());
        System.out.println(timestamp);
    }
}
