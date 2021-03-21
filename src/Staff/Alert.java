package Staff;

public class Alert { //(Extends JOptionPane probably)

    private int alertID; //The unique ID of the alert
    private boolean acknowledged; //Indicates whether the alert has been acknowledged or not
    private String alertType; //The type of alert, either about a job potentially missing its deadline or a late payment

    public Alert(int alertID, String alertType) {
        this.alertID = alertID; //[?] How are ID's generated again?
        acknowledged = false;
        this.alertType = alertType;
        //[?] Are alerts linked to jobs through the program or the database?
    }

    public int getAlertID() {
        return alertID;
    }

    //[?] Is this needed?
    public void setAlertID(int alertID) {
        this.alertID = alertID;
    }

    public boolean isAcknowledged() {
        return acknowledged;
    }

    public void setAcknowledged(boolean acknowledged) {
        this.acknowledged = acknowledged;
    }

    public String getAlertType() {
        return alertType;
    }

    public void setAlertType(String alertType) {
        this.alertType = alertType;
    }
}
