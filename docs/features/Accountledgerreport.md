# Feature: Accountledgerreport

## Overview
**Controller**: `mvc/controllers/Accountledgerreport.php`  
**Primary Purpose**: School-wide financial ledger reporting showing all income and expenses  
**User Roles**: Admin (1), Systemadmin (5)  
**Status**: ✅ Active

## Functionality
### Core Features
- View total income by school year or date range
- View total expenses by school year or date range
- View total salary payments
- View total fee collections (with fines)
- Generate PDF reports
- Email reports to administrators
- Filter by school year and custom date ranges

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | accountledgerreport | Display report form | accountledgerreport |
| `getaccountledgerreport()` | accountledgerreport/getaccountledgerreport | AJAX: Generate report | accountledgerreport |
| `pdf()` | accountledgerreport/pdf/[year]/[from]/[to] | Generate PDF report | accountledgerreport |
| `send_pdf_to_mail()` | accountledgerreport/send_pdf_to_mail | Email PDF report | accountledgerreport |

## Data Layer
### Models Used
- income_m, expense_m, make_payment_m, payment_m, schoolyear_m

### Database Tables
- `income` - Income transactions
- `expense` - Expense transactions
- `make_payment` - Salary payments
- `payment` - Fee collections
- `weaverandfine` - Waivers and fines

## Validation Rules
- `schoolyearID`: required, numeric
- `fromdate`: optional, dd-mm-yyyy format
- `todate`: optional, dd-mm-yyyy format (must be >= fromdate)

## Dependencies & Interconnections
### Depends On
- **Income**: Revenue tracking
- **Expense**: Expenditure tracking
- **Make_payment**: Salary payments
- **Payment**: Fee collections

### Related Features
- Income, Expense, Make_payment, Payment

## User Flows
### Generate Ledger Report
1. Admin navigates to accountledgerreport
2. Selects school year or "All" years
3. Optionally sets date range (from/to)
4. Clicks "Generate Report"
5. AJAX call fetches:
   - Total income (from income table)
   - Total expenses (from expense table)
   - Total salary payments (from make_payment table)
   - Total fee collections (from payment table)
   - Total fines collected
6. Displays summary:
   - Total Revenue = Income + Fee Collections + Fines
   - Total Expenditure = Expenses + Salaries
   - Net = Revenue - Expenditure

### Export to PDF
1. Same filters as above
2. Click PDF button
3. Redirects to pdf/[year]/[fromdate]/[todate]
4. Generates PDF with school header and ledger summary

## Edge Cases & Limitations
### Known Issues
1. **No Transaction Details**: Only shows totals, not individual transactions
2. **No Category Breakdown**: Income/expenses not grouped by category
3. **Date Range Validation**: Can select invalid date ranges

### Constraints
- Date range must be within same school year
- Requires at least one school year defined

## Configuration
No special configuration required

## Notes for AI Agents
### Key Patterns
- **Summary Report**: Aggregates only, no detail listing
- **Date Filtering**: Optional, defaults to entire school year
- **PDF Generation**: Uses reportPDF() helper method

### Common Modifications
- Add category-wise breakdown (income types, expense categories)
- Add monthly/quarterly trends
- Add charts/graphs
- Add transaction details drill-down
- Add budget vs. actual comparison
- Export to Excel

### Debugging Tips
- Check date format: dd-mm-yyyy in form, Y-m-d in database
- Verify schoolyearID exists in schoolyear table
- Check SUM queries for NULL handling
- Verify weaverandfine join for fine calculations
