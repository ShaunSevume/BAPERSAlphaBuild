package Job;

import java.util.Vector;

public class Tasks {

    public static Vector<TaskType> listOfTasks = new Vector<TaskType>(); //[?] The list of tasks has to be in sync with the database, somehow.

    public Tasks(){
        //[?]
        //Connect to the database and populate the local list of tasks
        //This should probably run each time the program is restarted
    }

    public static void addTask(TaskType t){
        listOfTasks.add(t);
        //[?] Update list of tasks on the database as well.
    }

    public static void removeTask(int id){
        //Search the list of tasks for a task with that specific ID and remove it.
    }

    public static TaskType getTask(int id){
        for(int i = 0; i < listOfTasks.size(); i++) {
            if (listOfTasks.elementAt(i).getTaskTypeID() == id) {
                return listOfTasks.elementAt(i);
            }
        }
        return null;
    }

   public static boolean searchTask(int id){
        for(int i = 0; i < listOfTasks.size(); i++) {
            if (listOfTasks.elementAt(i).getTaskTypeID() == id) {
                return true;
            }
        }
        return false;
    }

    public static void printTasks(){
        for(int i = 0; i < listOfTasks.size(); i++) {
            System.out.println("Task ID: " + listOfTasks.elementAt(i).getTaskTypeID() + ", Description: " + listOfTasks.elementAt(i).getTaskDescription() +  ", Location: " + listOfTasks.elementAt(i).getLocation() + ", Price: " + listOfTasks.elementAt(i).getPrice() + ", Duration: " + Integer.toString(listOfTasks.elementAt(i).getDuration()));
        }
    }
}
