package Customer;

import Job.Job;
import java.util.Vector;

public class Customer {

    private int customerID; //The unique ID of the customer.
    private String name; //The name of the customer.
    private String contactName; //The contact name of the customer if someone is ordering on behalf of an organisation.
    private String address; //The customer's address.
    private String phoneNo; //The customer's phone number.
    private Vector<Job> outstandingPayment; //A list of jobs which the customer has yet to pay for.
    private boolean valuedCustomer; //Indicates whether the customer is a 'valued customer' or not, and therefore their eligibility for a discount.
    private int discountType; //0 = none, 1 = fixed, 2 = variable, 3 = flexible
    private float discountAmount; //The amount of discount to apply to a customer's job, if they are on a FIXED discount plan.
    private float wallet; //The accumulated amount of ALL the customer's payments, used to determine the discount 'band' for the customer to be in, if they are on a FLEXIBLE discount plan.

    public Customer(int customerID, String name, String contactName, String address, String phoneNo) {
        this.customerID = customerID; //[?] How are ID's generated again?
        this.name = name;
        this.contactName = contactName;
        this.address = address;
        this.phoneNo = phoneNo;

        //[!] These would be defaults but how they are actually set must be considered.
        this.valuedCustomer = false;
        this.discountType = 0;
        this.discountAmount = 0;
        this.wallet = 0;
    }

    public int getCustomerID() {
        return customerID;
    }

    //[?] Is this needed?
    public void setCustomerID(int customerID) {
        this.customerID = customerID;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getContactName() {
        return contactName;
    }

    public void setContactName(String contactName) {
        this.contactName = contactName;
    }

    public String getAddress() {
        return address;
    }

    public void setAddress(String address) {
        this.address = address;
    }

    public String getPhoneNo() {
        return phoneNo;
    }

    public void setPhoneNo(String phoneNo) {
        this.phoneNo = phoneNo;
    }

    public Vector<Job> getOutstandingPayment() {
        //[?] Will this return the list itself or iterate through and print each element one by one?
        return outstandingPayment;
    }

    public void addOutstandingPayment(Job job) {
        outstandingPayment.add(job);
    }

    public void removeOutstandingPayment(int id){
        //Search the list of jobs for a job with that specific ID and remove it.
    }

    public boolean isValuedCustomer() {
        return valuedCustomer;
    }

    public void setValuedCustomer(boolean valuedCustomer) {
        this.valuedCustomer = valuedCustomer;
    }

    public int getDiscountType() {
        return discountType;
    }

    public void setDiscountType(int discountType) {
        this.discountType = discountType;
    }

    public float getDiscountAmount() {
        return discountAmount;
    }

    public void setDiscountAmount(float discountAmount) {
        this.discountAmount = discountAmount;
    }

    public float getWallet() {
        return wallet;
    }

    public void setWallet(float wallet) {
        this.wallet = wallet;
    }
}
