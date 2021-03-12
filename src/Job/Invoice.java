package Job;

import java.util.Date;

public class Invoice {

    private int invoiceID; //The unique ID of the invoice.
    private Date date; //The date of the invoice. [?] Is this the right datatype or should it be a timestamp?

    public Invoice(int invoiceID, Date date) {
        this.invoiceID = invoiceID; //[?] How are ID's generated again?
        this.date = date;
    }

    public int getInvoiceID() {
        return invoiceID;
    }

    //[?] Is this needed?
    public void setInvoiceID(int invoiceID) {
        this.invoiceID = invoiceID;
    }

    public Date getDate() {
        return date;
    }

    public void setDate(Date date) {
        this.date = date;
    }
}

