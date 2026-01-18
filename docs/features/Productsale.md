# Feature: Productsale

## Overview
**Controller**: `mvc/controllers/Productsale.php`  
**Primary Purpose**: Product sales transactions to all user types (non-students) with M-Pesa payment integration  
**User Roles**: Admin (1), Systemadmin (5)  
**Status**: ✅ Active

## Functionality
### Core Features
- Sell products to all user types EXCEPT students (use Inventoryinvoice for students)
- Customers: systemadmin, teacher, parent, other users, schools
- Track sale status: due, partially paid, fully paid
- M-Pesa STK Push payment integration
- Stock validation (cannot oversell)
- Multiple products per sale
- PDF generation and email
- Payment recording with multiple methods

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | productsale | List sales (customertype != 3) | productsale |
| `add()` | productsale/add | Create new sale | productsale_add |
| `edit(id)` | productsale/edit/[id] | Edit sale (no payments) | productsale_edit |
| `view(id)` | productsale/view/[id] | View sale details | productsale_view |
| `delete(id)` | productsale/delete/[id] | Delete sale (no payments) | productsale_delete |
| `saveproductsalepayment()` | productsale/saveproductsalepayment | Add payment | productsale_add |
| `print_preview(id)` | productsale/print_preview/[id] | Generate PDF | productsale_view |
| `getuser()` | productsale/getuser | AJAX: Load users by type | productsale_add |

## Data Layer
### Models Used
- productsale_m, productsaleitem_m, productsalepaid_m
- product_m, productcategory_m, productwarehouse_m, productpurchaseitem_m
- systemadmin_m, teacher_m, parents_m, user_m, classes_m
- mainmpesa_m, mpesa_m, payment_gateway (M-Pesa integration)
- paymenttypes_m

### Database Tables
- `productsale` - Sale header (customertypeID != 3)
- `productsaleitem` - Line items
- `productsalepaid` - Payments
- `mainmpesa`, `mpesa` - M-Pesa payment records
- `paymenttypes` - Payment methods including M-Pesa

## Validation Rules
- `productsalecustomertypeID`: required, 1-2-4+ or "school"
- `productsalecustomerID`: required, valid user of selected type
- `productsaledate`: required, dd-mm-yyyy format
- `productsalepaymentstatusID`: required (1=due, 2/3=partial/full payment)
- `productitem`: JSON array, at least one product
- Stock validation: quantity <= available
- M-Pesa phone: 254... format (12 digits)

## Dependencies & Interconnections
### Depends On
- **Product/Productwarehouse**: Product definitions and stock
- **Productpurchase**: Stock availability
- **Users**: All user types as customers
- **Payment_gateway**: M-Pesa STK Push
- **Safaricom**: IPN callback for M-Pesa

### Used By
- **Safaricom IPN**: Completes M-Pesa payments
- **Inventory Reports**: Sales analysis

## User Flows
### Create Sale with M-Pesa Payment
1. Admin selects customer type (teacher/parent/etc.)
2. Select customer from list
3. Adds products (validates stock)
4. Sets payment status:
   - 1 = Due (no payment now)
   - 2 = Partial/M-Pesa (prompts for phone)
   - 3 = Fully paid
5. If M-Pesa selected:
   - Enter phone number (254...)
   - Enter amount
6. Save → Creates:
   - `productsale` record (status=1 pending M-Pesa)
   - `productsaleitem` records
   - `productsalepaid` record
   - `mainmpesa` record (paidstatus=0)
   - `mpesa` record
   - STK Push sent to customer phone
7. Customer enters M-Pesa PIN
8. Safaricom sends IPN callback
9. `productsale.productsalestatus` updated to 3 (paid)

### Record Manual Payment
1. View sale → "Add Payment"
2. Select payment method:
   - 1 = Cash
   - 2 = Cheque
   - 3 = Credit Card
   - 4 = Other
   - M-Pesa (if available)
3. Enter amount and reference
4. Save → Updates sale status based on amount vs total

## Edge Cases & Limitations
### Known Issues
1. **Student Sales**: Must use Inventoryinvoice controller for students
2. **M-Pesa Timeout**: No retry if customer cancels STK Push
3. **Stock Race Condition**: No locking during stock validation
4. **Edit Restrictions**: Cannot edit if any payment exists

### Constraints
- Cannot sell to students (customertype=3) - use Inventoryinvoice
- Cannot exceed available stock
- M-Pesa requires valid phone number and payment gateway config
- Cannot delete sale with payments

## Configuration
### M-Pesa Settings
Located in `payment_gateway_option` table:
- `mpesa_shortcode`, `mpesa_consumer_key`, `mpesa_consumer_secret`, `mpesa_passkey`

### Payment Types
- Create "M-Pesa" or "mpesa" payment type in paymenttypes table
- System matches by lowercased name

## Notes for AI Agents
### Key Patterns
1. **Non-Student Sales**: `customertypeID != 3` (students use Inventoryinvoice)
2. **M-Pesa Integration**: Two-phase (STK Push → IPN callback)
3. **Stock Validation**: Same as Inventoryinvoice (purchase - sales)
4. **Multi-Customer**: Supports all user types plus schools

### M-Pesa Flow
```
productsale → mainmpesa (paidstatus=0) → STK Push → Safaricom
→ Customer PIN → Callback to safaricom/ipn → mainmpesa updated
→ productsale.productsalestatus updated
```

### Common Modifications
- Add bulk sales
- Implement quotations (before sales)
- Add delivery tracking
- Customer credit limits
- Sales returns/refunds
- Discount support
- Sales commission tracking

### Debugging Tips
- Check `customertypeID != 3` in queries
- Verify stock: SUM(purchase) - SUM(sales) per product
- M-Pesa: Check `mainmpesa.paidstatus` (0=pending, 1=paid)
- Check `safaricom_messages.log` for IPN callbacks
- Verify payment type name matching (case-insensitive)
- Check `productsalestatus`: 1=due, 2=partial, 3=paid
