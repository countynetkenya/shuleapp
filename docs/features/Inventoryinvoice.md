# Feature: Inventoryinvoice

## Overview
**Controller**: `mvc/controllers/Inventoryinvoice.php`  
**Primary Purpose**: Student inventory/product invoice management separate from fee invoices  
**User Roles**: Admin (1), Systemadmin (5), Student (3)  
**Status**: ✅ Active

## Functionality
### Core Features
- Create/edit/delete product sales to students only (customertype=3)
- Track invoice status: due, partially paid, fully paid
- Record payments against invoices with M-Pesa integration
- Stock validation (cannot oversell available inventory)
- Generate invoice PDFs and email
- Integration with school terms and product warehouses
- Creates invoice records for student statements

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | inventoryinvoice | List student product invoices | inventoryinvoice |
| `add()` | inventoryinvoice/add | Create new invoice | inventoryinvoice_add |
| `edit(id)` | inventoryinvoice/edit/[id] | Edit invoice (no payments) | inventoryinvoice_edit |
| `view(id)` | inventoryinvoice/view/[id] | View invoice details | inventoryinvoice_view |
| `delete(id)` | inventoryinvoice/delete/[id] | Delete invoice (no payments) | inventoryinvoice_delete |
| `saveproductsalepayment()` | inventoryinvoice/saveproductsalepayment | Add payment to invoice | inventoryinvoice_add |
| `print_preview(id)` | inventoryinvoice/print_preview/[id] | Generate PDF | inventoryinvoice_view |

## Data Layer
### Models Used
- productsale_m, productsaleitem_m, productsalepaid_m
- product_m, productcategory_m, productwarehouse_m, productpurchaseitem_m
- student_m, studentrelation_m, classes_m, schoolterm_m
- maininvoice_m, invoice_m (creates invoice records for statements)

### Database Tables
- `productsale` - Product sale header (customertype=3 for students)
- `productsaleitem` - Line items with product, quantity, unit price
- `productsalepaid` - Payments against product sales
- `productpurchaseitem` - For stock quantity validation
- `invoice`, `maininvoice` - Student statement integration

## Validation Rules
- `studentID`: required, numeric, valid student in school year
- `productsaledate`: required, dd-mm-yyyy format
- `schooltermID`: required, must exist for invoice date
- `productitem`: JSON array, at least one product required
- Stock validation: quantity <= available (purchase - sales)

## Dependencies & Interconnections
### Depends On
- **Product/Productcategory**: Product definitions
- **Productpurchase**: Stock availability
- **Student/Classes**: Customer selection
- **Schoolterm**: Term assignment

### Used By
- **Student_statement**: Displays product invoices in statements
- **Productsale**: Non-student product sales use separate controller

## User Flows
### Create Product Invoice for Student
1. Admin selects class → students load
2. Selects student and product warehouse
3. Adds products:
   - Select product category → products load
   - Select product → unit price auto-fills
   - Enter quantity (validates against stock)
4. Assigns to school term (auto-detects from date)
5. Saves → Creates:
   - `productsale` record (status=1 due)
   - `productsaleitem` records for each product
   - `maininvoice` and `invoice` records for student statement
6. Stock validated: purchase quantity - sale quantity >= new sale quantity

### Record Payment
1. View invoice → Click "Add Payment"
2. Enter:
   - Payment date
   - Reference number
   - Amount (validates <= due amount)
   - Payment method
   - Optional file attachment
3. Save → Updates:
   - Creates `productsalepaid` record
   - Updates `productsale.productsalestatus`:
     - 1 = due (no payments)
     - 2 = partially paid
     - 3 = fully paid

## Edge Cases & Limitations
### Known Issues
1. **Stock Calculation**: No transaction locking, race conditions possible
2. **No Credit Notes**: Cannot issue credits for product returns
3. **Edit Restrictions**: Cannot edit if any payment exists

### Constraints
- School term must exist for invoice date
- Cannot exceed available stock
- Cannot delete invoice with payments
- Student must be enrolled in current school year

## Configuration
### Required Settings
- At least one product warehouse
- At least one school term
- Products with purchase records for stock

## Notes for AI Agents
### Key Patterns
1. **Student-Only**: Fixed to `customertypeID=3`, cannot sell to other user types
2. **Stock Validation**: Compares purchase quantities vs sale quantities
3. **Statement Integration**: Creates invoice records for student financial statements
4. **Term Validation**: Requires school term for invoice date

### Common Modifications
- Add product return/refund
- Implement backorders
- Add discount support
- Bulk invoice creation
- Add delivery tracking
- Product bundles/packages

### Debugging Tips
- Check stock calculation: SUM(productpurchaseitem) - SUM(productsaleitem)
- Verify schoolterm dates cover invoice date
- Check `productsalestatus`:  1=due, 2=partial, 3=paid
- Verify student enrollment in school year
- Check productwarehouse assignment
