# Feature: Paymenthistory

## Overview
**Controller**: `mvc/controllers/Paymenthistory.php`  
**Primary Purpose**: View and manage payment history/records for student fees  
**User Roles**: Admin (1), Systemadmin (5), Student (3), Parent (4)  
**Status**: ✅ Active

## Functionality
### Core Features
- View all payment history (admin)
- View own payment history (student/parent)
- Edit payment records (change amount, transaction ID, payment method)
- Delete payment records (cascade deletes globalpayment)
- View payment details with balance calculation
- Cannot edit/delete gateway payments (PayPal, Stripe, etc.)

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | paymenthistory | List payments filtered by user role | paymenthistory |
| `edit(id)` | paymenthistory/edit/[id] | Edit payment record | paymenthistory_edit |
| `view(id)` | paymenthistory/view/[id] | View payment details | paymenthistory |
| `delete(id)` | paymenthistory/delete/[id] | Delete payment | paymenthistory_delete |

## Data Layer
### Models Used
- feetypes_m, invoice_m, creditmemo_m, payment_m, student_m, parents_m
- maininvoice_m, weaverandfine_m, studentrelation_m, globalpayment_m, paymenttypes_m

### Database Tables
- `payment` - Payment records
- `globalpayment` - Global payment tracking (cascade deleted)
- `invoice` - Related invoices
- `creditmemo` - Credit memos for balance calculation
- `paymenttypes` - Payment methods

## Validation Rules
### Payment Edit
- `amount`: required, numeric, 11 chars max, >= 0
- `transactionID`: required, text
- `paymentmethod`: required, numeric, validates payment type exists
- `studentID`: required, numeric, validates student exists

### M-Pesa Payment Edit (no amount/transaction)
- `paymentmethod`: required
- `studentID`: required

## Dependencies & Interconnections
### Depends On (Upstream)
- **Payment**: Displays payment records
- **Invoice/Creditmemo**: For balance calculation
- **Student/Parents**: Payment ownership

### Used By (Downstream)
- **Student_statement**: Includes payment history
- **Accountledgerreport**: Aggregates payments

### Related Features
- Payment, Make_payment, Student_statement

## User Flows
### Primary Flow: View Payment History
1. **Student (usertype=3)**: See only their own payments
2. **Parent (usertype=4)**: See payments for all their children
3. **Admin (usertype=1,5)**: See all payments for the school

### Edit Payment Flow
1. Navigate to payment/edit/[id]
2. Can only edit manual payments (not PayPal/Stripe/Voguepay/Payumoney)
3. Update amount, transaction ID, or payment method
4. Updates payment and globalpayment records

### Delete Payment Flow
1. Verify payment is not from gateway
2. Delete all payments with same globalpaymentID
3. Delete globalpayment record
4. Redirect to index

## Edge Cases & Limitations
- **Gateway Protection**: Cannot edit/delete payments from PayPal, Stripe, PayUmoney, Voguepay
- **School Year Lock**: Only edit/delete in active school year (unless superadmin)
- **Cascade Delete**: Deleting one payment deletes ALL payments in same globalpayment group
- **Balance Display**: Shows balance at time of payment (historical)

## Notes for AI Agents
### Payment Protection Logic
```php
if (in_array($payment->paymenttype, ['Paypal', 'Stripe', 'Payumoney', 'Voguepay'])) {
    // Cannot edit/delete - redirect to error
}
```

### Cascade Delete Pattern
When deleting payment:
1. Get `globalpaymentID` from payment
2. Find all payments with same `globalpaymentID`
3. Delete all those payments (batch delete)
4. Delete the globalpayment record

### Historical Balance Calculation
```php
// Get all invoices/creditmemos/payments BEFORE current payment date
$invoices = invoice_m->get('date <' => payment->paymentdate)
$creditmemos = creditmemo_m->get('date <' => payment->paymentdate)
$payments = payment_m->get('paymentdate <' => payment->paymentdate)
$balance = sum(invoices) - sum(creditmemos) - sum(payments)
```
Shows "balance before this payment" context

