# Feature: Student_statement

## Overview
**Controller**: `mvc/controllers/Student_statement.php`  
**Primary Purpose**: Generate and display student financial statements showing invoices, payments, and credits  
**User Roles**: Admin (1), Systemadmin (5), Student (3), Parent (4)  
**Status**: ✅ Active

## Functionality
### Core Features
- View complete financial statement for students
- Balance calculation: Invoices - Payments - Credits
- Balance brought forward (BBF) from previous years
- Filter by school year, term, date range
- Print/download statements as PDF
- Group by class/section/student/parent
- School term-based transaction grouping

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | student_statement | View/filter statements | student_statement |
| `print_preview(id)` | student_statement/print_preview/[id] | Print statement PDF | student_statement |
| `paymentSend()` | student_statement/paymentSend | AJAX: Record payment from parent | N/A |

## Data Layer
### Models Used
- student_m, parents_m, classes_m, section_m, schoolterm_m
- studentgroup_m, feetypes_m, invoice_m, creditmemo_m, payment_m
- globalpayment_m, weaverandfine_m, maininvoice_m, studentrelation_m
- mainmpesa_m, mpesa_m, quickbookssettings_m, paymenttypes_m, quickbookslog_m

### Database Tables
- `invoice` - Student invoices (deleted_at=1 for active)
- `creditmemo` - Student credit memos
- `payment` - All payments
- `globalpayment` - Payment groupings
- `studentrelation` - Student enrollment history

## Validation Rules
- `classesID`: optional, numeric, 11 chars max
- `sectionID`: optional, numeric, 11 chars max
- `studentID` or `parentID`: at least one required
- `schoolYearID`: required for filtering
- `dateFrom`, `dateTo`: optional, dd-mm-yyyy format

## Dependencies & Interconnections
### Depends On
- **Invoice**: Debit transactions
- **Creditmemo**: Credit transactions
- **Payment**: Payment transactions
- **Schoolterm**: Term-based grouping

### Used By
- **Parents/Students**: View their financial status
- **Admin**: Generate statements for collection

## User Flows
### View Statement (Parent)
1. Parent logs in (usertypeID=4)
2. Navigates to student_statement
3. Selects student
4. Optionally filters by:
   - School year
   - School term
   - Date range (from/to)
5. System calculates:
   - Balance brought forward (previous years)
   - Current period invoices
   - Current period credits
   - Current period payments
   - Running balance
6. Displays statement with:
   - Opening balance
   - Transactions by date
   - Running balance column
   - Closing balance

### Balance Calculation Logic
```
BBF (Balance Brought Forward) = SUM of all transactions before dateFrom
For each transaction after dateFrom (ordered by date):
  If Invoice: Balance += Amount
  If Credit Memo: Balance -= Amount
  If Payment: Balance -= Amount
Display running balance after each transaction
```

### Statement Structure
```
Term Header (e.g., "Term 1 - Class 5A")
  Balance Brought Forward: KES 5,000
  Invoice #123 - Tuition Fee: +KES 10,000 | Balance: KES 15,000
  Payment Ref 456 - M-Pesa: -KES 5,000 | Balance: KES 10,000
  Credit Memo #78 - Scholarship: -KES 2,000 | Balance: KES 8,000
  Closing Balance: KES 8,000
```

## Edge Cases & Limitations
### Known Issues
1. **No Pagination**: Large statements may timeout
2. **No Caching**: Recalculates every time
3. **Performance**: Slow with many transactions
4. **Date Filtering**: Inclusive dates (BBF calculation affected)

### Constraints
- Requires active school year
- Student must be enrolled in selected year
- Deleted transactions (deleted_at=0) excluded

## Configuration
### Required Settings
- Active school year
- At least one school term defined
- Payment gateway settings for M-Pesa payments

## Notes for AI Agents
### Key Patterns
1. **BBF Calculation**: Separate query for transactions before dateFrom
2. **Statement Array**: Grouped by school term subheading
3. **Balance Formula**: `Balance = Invoices - Payments - Credits`
4. **Date Sorting**: All transactions sorted by date ASC before processing

### Performance Optimization
- Add statement caching
- Implement pagination
- Pre-calculate balances (materialized view)
- Add database indexes on date columns

### Common Modifications
- Add statement email automation
- Generate monthly statements automatically
- Add payment plan tracking
- SMS reminders for overdue balances
- Export to Excel
- Add charts/visualizations

### Debugging Tips
- Check `deleted_at = 1` for active records
- Verify date format: Y-m-d in database, d-m-Y in display
- Check schoolterm dates for proper grouping
- Verify invoice/credit/payment amounts are positive numbers
- Check BBF calculation separately from current period
