package Job;

import java.sql.Timestamp;
import java.util.Vector;

public class Job {

    private int jobID; //The unique ID assigned to each job.
    private String jobDetails; //[?]
    private int priority; //The priority of the job. A higher value will mean a higher priority, and the system will favour its completion over other jobs with a lower priority.
    private String specialInstructions; //Any special instructions detailed by the customer for this particular job.
    private int status; //0 = Pending, 1 = Active, 2 = Completed
    private Timestamp deadline; //The deadline by which the job should be completed by.
    private boolean paid; //Indicates whether the current job has been paid for or not.
    private Timestamp completionTime; //Indicates the time it took for the job to complete, once the last task has been done.
    private float price; //The total price of the job (calculated from the sum of the task prices, [?] - but can be manually dictated if needs be).
    private Vector<Task> taskList; //A list of all the tasks this job consists of.

    public Job(int jobID, String jobDetails, int priority, String specialInstructions, Timestamp deadline, Timestamp completionTime) {
        this.jobID = jobID; //[?] How are ID's generated again?
        this.jobDetails = jobDetails;
        this.priority = priority;
        this.specialInstructions = specialInstructions;
        this.status = 0;
        this.deadline = deadline;
        this.paid = false;
        this.completionTime = completionTime; //[?] How's this figured out? From the total time of tasks in the task vector?
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

    public int getPriority() {
        return priority;
    }

    public void setPriority(int priority) {
        this.priority = priority;
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

    public void addTask(Task task){
        taskList.add(task);
        //[?] Precaution to make sure all tasks in the vector are unique? Or maybe this can be handled in the controller as the user would only be able to select one of each task anyway...
    }

    public void removeTask(int id){
        //Search the list of tasks for a task with that specific ID and remove it.
    }
}
