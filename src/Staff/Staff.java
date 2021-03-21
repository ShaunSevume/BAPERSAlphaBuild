package Staff;

public class Staff {

    private int staffID; //The unique ID of the staff member
    private String name; //The name of the staff member
    //[?] Password?
    private String role; //The role of the staff member

    public Staff(int staffID, String name, String role) {
        this.staffID = staffID; //[?] How are ID's generated again?
        this.name = name;
        this.role = role;
        //[?] Add staff member to database?
    }

    public int getStaffID() {
        return staffID;
    }

    //[?] Is this needed?
    public void setStaffID(int staffID) {
        this.staffID = staffID;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getRole() {
        return role;
    }

    public void setRole(String role) {
        this.role = role;
    }
}
