package Job;

import java.util.Vector;

public class Tasks {

    public static Vector<TaskType> listOfTasks; //[?] The list of tasks has to be in sync with the database, somehow.

    public Tasks(){
        //[?]
        //Connect to the database and populate the local list of tasks
        //This should probably run each time the program is restarted
    }

    public void addTask(TaskType t){
        listOfTasks.add(t);
        //[?] Update list of tasks on the database as well.
    }

    public void removeTask(int id){
        //Search the list of tasks for a task with that specific ID and remove it.
    }

    public TaskType getTask(int id){
        //[?]
        //Check if element exists first, and THEN...

        /*
        TaskType t;
        if(search(id) != null){
            t = search(id); //Searching twice, inefficient..
            return t;
        }else{
            return null;
        }
        */

        return listOfTasks.elementAt(id); //[!] Only a temporary solution.

    }

    //[!] This could be useful as a generic function usable across all classes, but not sure if it will work as it has to be a vector of something specific.
    public  TaskType search(int id){
        for(int i = 0; i < listOfTasks.size(); i++) {
            if (listOfTasks.elementAt(i).getTaskTypeID() == id) {
                return listOfTasks.elementAt(i);
            }
        }
        return null; //[!] Hmm... How will the caller handle this if expecting an object of tasktype back? Maybe a try-catch statement?
    }
}
