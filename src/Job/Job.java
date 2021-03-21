package Job;

import java.sql.Timestamp;
import java.util.Vector;

public class Job {

    private int jobID; //The unique ID assigned to each job.
    private String jobDetails; //[?]
    private int Urgency; //The urgency of the job which will stipulate the completion time. 0 = Normal (24h), 1 = Urgent (6h), 2 = Super Urgent (3h), 3 = Express (<3h)
    private String specialInstructions; //Any special instructions detailed by the customer for this particular job.
    private int status; //0 = Pending, 1 = Active, 2 = Completed
    private Timestamp deadline; //The deadline by which the job should be completed by.
    private boolean paid; //Indicates whether the current job has been paid for or not.
    private Timestamp completionTime; //Indicates the time it took for the job to complete, once the last task has been done.
    private float price; //The total price of the job (calculated from the sum of the task prices, [?] - but can be manually dictated if needs be).
    private Vector<Task> taskList; //A list of all the tasks this job consists of.
    //[?] Does each job require an attribute linking it to a customer, or is the linkage entirely handled through the database?
    private int customer; //The ID of the customer this job is linked to
    //[?] The same question applies to the payment (ID).

    public Job(int jobID) {
        this.jobID = jobID; //[?] How are ID's generated again?
        this.jobDetails = " ";
        this.Urgency = 0;
        this.specialInstructions = " ";
        this.status = 0;
        this.deadline = null;
        this.paid = false;
        this.completionTime = null; //[?] How's this figured out? From the total time of tasks in the task vector?
        this.price = 0f;
        taskList = new Vector<Task>();
        //[?] Update on the database as well?
    }

    public int getJobID() {
        return jobID;
    }

    //[?] Is this needed?
    public void setJobID(int jobID) {
        this.jobID = jobID;
    }

    public String getJobDetails() {
        return jobDetails;
    }

    public void setJobDetails(String jobDetails) {
        this.jobDetails = jobDetails;
    }

    public int getUrgency() {
        return Urgency;
    }

    public void setUrgency(int urgency) {
        this.Urgency = urgency;
    }

    public String getSpecialInstructions() {
        return specialInstructions;
    }

    public void setSpecialInstructions(String specialInstructions) {
        this.specialInstructions = specialInstructions;
    }

    public int getStatus() {
        return status;
    }

    public void setStatus(int status) {
        this.status = status;
    }

    public Timestamp getDeadline() {
        return deadline;
    }

    public void setDeadline(Timestamp deadline) {
        this.deadline = deadline;
    }

    public boolean isPaid() {
        return paid;
    }

    public void setPaid(boolean paid) {
        this.paid = paid;
    }

    public Timestamp getCompletionTime() {
        return completionTime;
    }

    public void setCompletionTime(Timestamp completionTime) {
        this.completionTime = completionTime;
    }

    public float getPrice() {
        return price;
    }

    public void setPrice(float price) {
        this.price = price;
    }

    public int getCustomer() {
        return customer;
    }

    public void setCustomer(int customer) {
        this.customer = customer;
    }

    public void addTask(Task task){
        taskList.add(task);
        //[?] Precaution to make sure all tasks in the vector are unique? Or maybe this can be handled in the controller as the user would only be able to select one of each task anyway...
    }

    public void removeTask(int id){
        //Search the list of tasks for a task with that specific ID and remove it.
    }

    public  void printTasks(){
        for(int i = 0; i < taskList.size(); i++) {
            System.out.println("Task ID: " + taskList.elementAt(i).getTaskTypeID() + ", Description: " + taskList.elementAt(i).getTaskDescription() +  ", Location: " + taskList.elementAt(i).getLocation() + ", Price: " + taskList.elementAt(i).getPrice());
        }
    }

    public int calculateDuration(){
        int duration = 0; //Total time of all tasks in this job
        for(int i=0;i<taskList.size();i++){
            duration += taskList.elementAt(i).getDuration();
        }
        return duration;
    }

    public float calculatePrice(){
        float p = 0;
        for(int i=0;i<taskList.size();i++){
            p += taskList.elementAt(i).getPrice();
        }
        return p;
    }
}
