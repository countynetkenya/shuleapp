# Feature: Payment

## Overview
**Controller**: `mvc/controllers/Payment.php`  
**Primary Purpose**: Manual payment entry interface (admin creates payments for student fees)  
**User Roles**: Admin (1), Systemadmin (5), Parent (4)  
**Status**: ✅ Active

## Functionality
### Core Features
- Manual payment entry for student invoices/fees
- Student balance calculation (invoice - payment - creditmemo)
- QuickBooks payment synchronization
- Filter students by class and student group
- Payment type selection (Cash, Mpesa, etc.)
- Term-based payment tracking

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | payment | Payment entry form | payment |
| `getstudent()` | payment/getstudent | AJAX: Get students by class/group | payment |

## Data Layer
### Models Used
- payment_m, invoice_m, creditmemo_m, classes_m, student_m, parents_m, section_m
- studentgroup_m, user_m, weaverandfine_m, globalpayment_m, studentrelation_m, schoolterm_m
- quickbookssettings_m, paymenttypes_m

### Database Tables
- `payment` - Payment records
- `invoice` - Student invoices
- `creditmemo` - Credit memos (refunds/adjustments)
- `globalpayment` - Global payment tracking
- `paymenttypes` - Payment method definitions

## Validation Rules
### Payment Entry
- `classesID`: required, numeric, validates class exists
- `studentID`: required, numeric, validates student exists
- `feetypeitems`: required, fee item details
- `date`: required, dd-mm-yyyy format

## Dependencies & Interconnections
### Depends On (Upstream)
- **Invoice**: Payments apply to invoices
- **Student**: Payment recipient
- **Classes/Section**: Student filtering
- **Paymenttypes**: Payment method selection
- **Schoolterm**: Term association

### Used By (Downstream)
- **Student_statement**: Displays payments
- **Accountledgerreport**: Includes in financial reports
- **Quickbooks**: Syncs payments to QBO

### Related Features
- Invoice, Paymenthistory, Make_payment, Student_statement

## User Flows
### Primary Flow: Create Payment
1. Admin selects class → students load
2. Optionally filter by student group
3. Select student → balance calculates
4. Enter payment details (amount, date, method)
5. Submit → creates payment record
6. Optional: Sync to QuickBooks

### Balance Calculation
```php
$balance = $totalInvoiceAmount - ($totalPaymentAmount + $totalCreditmemoAmount)
```

## Edge Cases & Limitations
- Parent (usertypeID=4) only sees their children's payment info
- QuickBooks sync requires valid OAuth token
- Cannot enter payments for students with no invoices
- School year restriction (unless superadmin)

## Configuration
### QuickBooks Integration
- OAuth configuration in `quickbookssettings` table
- Payment sync creates QBO Payment entity
- Links to customer (student) in QuickBooks

## Notes for AI Agents
### Payment Types
- Configured in `paymenttypes` table per school
- Maps to QuickBooks "DepositToAccountRef" for accounting
- Examples: Cash, M-Pesa, Bank Transfer, Cheque

### Balance Calculation Pattern
- Used across multiple financial controllers
- Always: Invoice Amount - (Payments + Creditmemos) = Balance
- Weaver (waiver) reduces balance, Fine increases balance

### QuickBooks Auth Flow
- Stores OAuth2 access token in database (serialized)
- Auto-refreshes token when expired
- Generates authUrl for manual re-authorization if needed

