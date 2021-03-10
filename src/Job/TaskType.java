package Job;

public class TaskType {

    private String taskDescription; //A description of the task
    private int taskTypeID; //The unique ID of the taskType
    private String location; //The location where the task will be performed
    private float price; //How much the task is.

    public TaskType(String taskDescription, int taskTypeID, String location, float price) {
        this.taskDescription = taskDescription;
        this.taskTypeID = taskTypeID;
        this.location = location;
        this.price = price;
        //[!] Every time a new TaskType is created, it HAS to be added to the list of tasks.
    }

    public String getTaskDescription() {
        return taskDescription;
    }

    public void setTaskDescription(String taskDescription) {
        this.taskDescription = taskDescription;
    }

    public int getTaskTypeID() {
        return taskTypeID;
    }

    //[?] Is this needed?
    public void setTaskTypeID(int taskTypeID) {
        this.taskTypeID = taskTypeID;
    }


    public String getLocation() {
        return location;
    }

    public void setLocation(String location) {
        this.location = location;
    }

    public float getPrice() {
        return price;
    }

    public void setPrice(float price) {
        this.price = price;
    }
}
