package Job;

public class Payment {

    private int paymentID; //The unique ID of the payment.
    private String paymentType; //[?] Perhaps it could be a boolean instead, indicating whether the payment is card or not?
    private float paymentAmount; //The amount paid by the customer.


    public Payment(int paymentID, String paymentType, float paymentAmount) {
        this.paymentID = paymentID; //[?] How are ID's generated again?
        this.paymentType = paymentType;
        this.paymentAmount = paymentAmount;

        //[!] I'm guessing if paymentType = card, a new card will be created and added to the payment? I forgot how we were supposed to implement card payment lol
    }

    public int getPaymentID() {
        return paymentID;
    }

    //[?] Is this needed?
    public void setPaymentID(int paymentID) {
        this.paymentID = paymentID;
    }

    public String getPaymentType() {
        return paymentType;
    }

    public void setPaymentType(String paymentType) {
        this.paymentType = paymentType;
    }

    public float getPaymentAmount() {
        return paymentAmount;
    }

    public void setPaymentAmount(float paymentAmount) {
        this.paymentAmount = paymentAmount;
    }
}
