package Job;

import java.sql.Timestamp;

public class Task extends TaskType{

    private TaskType type; //This object will hold the details of the actual task, such as its location, price, etc. This must be a seperate object rather than a reference to the TaskType in the list of tasks, in case the list is modified or the task is deleted in the future.
    private int taskID; //The unique ID of this specific task.
    private int status; //0 = Pending, 1 = Active, 2 = Completed.
    private Timestamp startTime; //Indicates the time at which the task finished.
    private Timestamp finishTime; //Indicates the time at which the task finished.
    private int timeTaken; //Time taken for the task to complete
    private float taskDiscount; //The discount amount to be applied (only used for customers on a 'Variable discount' plan).

    private String completedBy; //The member of staff who completed the task.

    public Task(TaskType type, int taskID) {
        super(type.getTaskDescription(), type.getTaskTypeID(), type.getLocation(), type.getPrice(), type.getDuration());
        this.type = type;
        this.taskID = taskID; //[?] How are ID's generated again?
        status = 0;

        //These values are unknown at the time of the creation of a task to be added to a job, so they are either initialised to 0 or null to show this.
        startTime = null;
        finishTime = null;
        timeTaken = 0;
        taskDiscount = 0;
        completedBy = null;
        //[!] These tasks don't really exist without jobs, so make sure that each instance is placed in any job's list of tasks.
    }

    public int getTaskID() {
        return taskID;
    }

    //[?] Is this needed?
    public void setTaskID(int taskID) {
        this.taskID = taskID;
    }

    public int getStatus() {
        return status;
    }

    public void setStatus(int status) {
        this.status = status;
    }

    public void updateTask(int status) {
        this.status = status;
    }

    public Timestamp getStartTime() {
        return startTime;
    }

    public void setStartTime(Timestamp startTime) {
        this.startTime = startTime;
    }

    public Timestamp getFinishTime() {
        return finishTime;
    }

    public void setFinishTime(Timestamp finishTime) {
        this.finishTime = finishTime;
    }

    public int getTimeTaken() {
        return timeTaken;
    }

    public void setTimeTaken(int timeTaken) {
        this.timeTaken = timeTaken;
    }

    public float getTaskDiscount() {
        return taskDiscount;
    }

    public void setTaskDiscount(float taskDiscount) {
        this.taskDiscount = taskDiscount;
    }

    public String getCompletedBy() {
        return completedBy;
    }

    public void setCompletedBy(String completedBy) {
        this.completedBy = completedBy;
    }

}
