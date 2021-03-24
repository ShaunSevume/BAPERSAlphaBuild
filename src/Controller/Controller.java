package Controller;

import Customer.*;
import Job.*;
import Staff.*;

import java.sql.Timestamp;
import java.text.DecimalFormat;
import java.util.*;

public class Controller {

    private static int counter;
    public static DecimalFormat df = new DecimalFormat("0.00");

    public Controller(){
        counter = 0;
        //CreateTaskType();
        //CreateCustomer();
        //CreateStaff();
        CreateDummyData();
        //CreateTask();
        CreateJob();
        //Calling the same method FOUR times to test its functionality? Disgraceful.
        ProcessJob();
        ProcessJob();
        ProcessJob();
        ProcessJob();
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
        Timestamp deadline = calculateDeadline(1440); //Default deadline is 24h.
        int urgency = sc.nextInt();
        float surcharge = 1; //The additional surcharge if the job is specified as 'Super Urgent' or 'Express'.
        switch(urgency){
            case 0:
                deadline = calculateDeadline(1440); //A bit dumb to do but it's here for the sake of the program flow.
                break;
            case 1:
                deadline = calculateDeadline(360);
                break;
            case 2:
                deadline = calculateDeadline(180);
                surcharge = 2; //A surcharge of 100% means the job will cost twice as much.
                break;
            case 3:
                System.out.println("Enter the express time (minutes): ");
                int et = sc.nextInt();
                deadline = calculateDeadline(et);
                System.out.println("Enter the surcharge rate: ");
                surcharge = sc.nextFloat();
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
        float price = Float.parseFloat((df.format(j.calculatePrice())));
        System.out.println("The current total price is £" + price);

        //[8] Calculating price for a valued customer based on their discount plan
        makeFixed(j,20);
        //makeVariable(j);
        //makeFlexible(j, 253);
        if(Customers.getCustomer(j.getCustomer()).isValuedCustomer()){ //Check if the customer is valued
            switch(Customers.getCustomer(j.getCustomer()).getDiscountType()){ //Check the discount type they have if they ARE valued
                case 1:
                    price = Float.parseFloat(df.format(price * (1-Customers.getCustomer(j.getCustomer()).getDiscountAmount()/100)));
                    System.out.println("The discounted price will be £" + df.format(price) + " at a discount rate of " + Float.parseFloat(df.format(Customers.getCustomer(j.getCustomer()).getDiscountAmount())) + "%.");
                    break;
                case 2:
                    j.printDiscountTasks();
                    System.out.println("Enter a discount value for each task: ");
                    String[] arr2 = GetInp();
                    j.setDiscounts(arr2);
                    j.calculateDiscounts();
                    j.printDiscountTasks();
                    price = Float.parseFloat((df.format(price)));
                    System.out.println("The discounted price will be £" + df.format(price));
                    break;
                case 3:
                    price = Float.parseFloat(df.format(price*Customers.getCustomer(j.getCustomer()).calculateFlex()));
                    System.out.println("The discounted price will be £" + df.format(price) + " at a discount rate of " + Float.parseFloat(df.format(1 - Customers.getCustomer(j.getCustomer()).calculateFlex()))*100 + "%.");
                    break;
            }
        }

        //[9] Calculating price with surcharge for urgency (after discounts are applied)
        price *= surcharge;
        price = Float.parseFloat((df.format(price)));
        System.out.println("The final price will be £" + df.format(price));

        //[10] Confirmation
        j.setPrice(price);
        j.setStatus(0); //Sets the job to have a "pending" status
        Customers.getCustomer(j.getCustomer()).addOutstandingPayment(j); //Adds the job as an outstanding payment to the customer
        Jobs.addJob(j);
    }

    //[;-;]
    public void ProcessJob(){
        //[1] Get all the staff and prompt user to select ONE (staff will be logged in so this code will be made redundant)
        Users.printStaff(); //Show the list of available customers

        boolean found = false; //To indicate when a valid customer has been selected.
        int sid = 0; //The customer ID the user will enter

        while(!found) {
            System.out.println("Please Select a staff: "); //Prompt to select a staff
            Scanner sc = new Scanner(System.in);
            sid = sc.nextInt(); //Get user's input as an int to search list of staff with it
            if (Users.searchStaff(sid)) { //If a staff member is found...
                found = true; //The loop can exit.
            }
        }

        //[2] All the jobs would be displayed in a list
        System.out.println("Select a job: ");
        Jobs.printJobs();
        //Let's assume the user selects the only job there anyway
        Job j = Jobs.listOfJobs.elementAt(0); //VERY DANGEROUS, but oh well.
        System.out.println("Tasks in this job: ");
        j.printTasks();

        //[3] Picking a task in the job to edit..
        boolean found2 = false;
        int tid = 0;
        while(!found2) {
            System.out.println("Please Select a task: "); //Prompt to select a task
            Scanner sc = new Scanner(System.in);
            tid = sc.nextInt(); //Get user's input as an int to search list of tasks with it
            if (j.searchTasks(tid)) { //If a task is found...
                found2 = true; //The loop can exit.
            }
        }

        switch (j.getTask(tid).getStatus()){
            case 0:
                System.out.println("The current status of this task is PENDING.");
                break;
            case 1:
                System.out.println("The current status of this task is ACTIVE");
                break;
            case 2:
                System.out.println("The current status of this task is COMPLETE");

        }

        System.out.println("What will you change the status to? 1 - Active 2 - Complete");
        Scanner sc = new Scanner(System.in);
        int sta = sc.nextInt();

        if(sta == 1 && j.getTask(tid).getStatus() == 0){ //Only pending jobs can be made active.
            j.getTask(tid).setStatus(sta); //Set the status to 1 - active
            j.getTask(tid).setStartTime(new Timestamp(new Date().getTime())); //Set the start time to the current time

            //If the job's status is not active already, make it so and set the time it was started at.
            if(j.getStatus() != 1){
                j.setStatus(1);
                j.setStartTime(new Timestamp(new Date().getTime()));
            }
        }else if(sta == 2 && j.getTask(tid).getStatus() == 1){ //Only active jobs can be made complete.
            j.getTask(tid).setStatus(sta); //Set the status to 2 - complete
            j.getTask(tid).setFinishTime(new Timestamp(new Date().getTime())); //Record the time at which it was completed
            j.getTask(tid).setTimeTaken(timeDiff(j.getTask(tid).getStartTime(),j.getTask(tid).getFinishTime())); //Calculate time taken to complete task.
            j.getTask(tid).setCompletedBy(Users.getStaff(sid).getName() + "(ID: " + sid + ")"); //Record who finished the task.

            //Go through the list of tasks and check if they're all completed
            boolean allDone = true;
            for(int i=0;i<j.getTaskList().size();i++){
                if (j.getTaskList().elementAt(i).getStatus() != 2){ //If one task in the list isn't complete then they're not all done.
                    allDone = false;
                }
            }

            //If they are...
            if(allDone){
                j.setStatus(2); //Mark the job as "complete"
                j.setFinishTime(new Timestamp(new Date().getTime())); //Record the time at which it was completed
                j.setTimeTaken(timeDiff(j.getStartTime(),j.getFinishTime())); //Calculate the time it took to complete the job.
                System.out.println("Job done!");
            }
        }else{
            System.out.println("You can't do that!");
        }

        //Update the job wherever it need updating because java defaults to passing by value and not by reference :) :) :)
        //(some database line goes here)
        //it has to be updated and put back into the customer's list of jobs, as well as

        //TESTING STUFF
        j.printTasks();

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

    public void makeVariable(Job j){
        Customers.getCustomer(j.getCustomer()).setValuedCustomer(true);
        Customers.getCustomer(j.getCustomer()).setDiscountType(2);
    }
    public void makeFlexible(Job j, int d){ //To make the chosen customer have a flexible discount plan of a chosen amount
        Customers.getCustomer(j.getCustomer()).setValuedCustomer(true);
        Customers.getCustomer(j.getCustomer()).setDiscountType(3);
        Customers.getCustomer(j.getCustomer()).setWallet(d);

        //Testing BandCheck (it works)
        //System.out.println("Band check? " + Customers.getCustomer(j.getCustomer()).checkBands(new String[] {"1" ,"3", "2", "4"}));

        //Make a random array of bands and discount rates as strings
        String[] b = {"50" ,"75", "150", "450"};
        String[] p = {"10" ,"20", "30", "40"};

        //Convert array to vector
        Vector<String> bands = new Vector<String>(Arrays.asList(b));
        Vector<String> rates = new Vector<String>(Arrays.asList(p));

        //Make sure both vectors are in ascending order and are of the same length before adding the bands and rates to the customer
        if(Customer.checkBands(bands) && Customer.checkRates(rates) && Customer.isSame(bands,rates)){
            Customers.getCustomer(j.getCustomer()).setBands(bands,rates);
        }
        Customers.getCustomer(j.getCustomer()).printBands();
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
            TaskType tt = new TaskType("Task " + ++counter, counter, "Lorem Ipsum", Float.parseFloat(df.format(new Random().nextFloat() * 100)), new Random().nextInt((30+1) + 300)); //Create a new task with test inputs
            Tasks.addTask(tt);

            Customer c = new Customer(new Random().nextInt(100 + 1),"Customer " + counter, "Contact " + new Random().nextInt(100 + 1), "Address " + new Random().nextInt(100 + 1), "07" + new Random().nextInt((100000000 + 1) + 999999999));
            Customers.addCustomer(c);

            Staff s = new Staff(new Random().nextInt(100 + 1), "Staff " + counter, "Role " + new Random().nextInt(5 + 1));
            Users.addStaff(s);
            /*
            Job j = new Job(new Random().nextInt(100 + 1));
            j.setCustomer(Customers.listOfCustomers.elementAt(i).getCustomerID()); //This is SO dangerous i forgot i was allowed to do this
            int r = new Random().nextInt((5 - 1) + 1) + 1; //PICK A RANDOM NUMBER OF TASKS TO ADD
            for(int x=0;x<r;x++){
                int r2 = new Random().nextInt((4 - 1) + 1) + 1; //((max-min) + 1 ) + min – PICK A RANDOM TASKTYPE (elements 0-4)
                Task t = new Task(Tasks.getTask(r), new Random().nextInt(100 + 1));
                j.addTask(t);
            }
            j.setSpecialInstructions("Random number: " + Integer.toString(new Random().nextInt(100 + 1)));
            //forget urgency lmao
            //Actually forget all of this, too complex.
            */
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

    public int timeDiff(Timestamp ts, Timestamp ts2){

        // get time difference in seconds
        long milliseconds = ts2.getTime() - ts.getTime();
        int minutes = (int) milliseconds/60000;
        return minutes;
    }
}
