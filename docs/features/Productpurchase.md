# Feature: Productpurchase

## Overview
**Controller**: `mvc/controllers/Productpurchase.php`  
**Primary Purpose**: Product inventory purchase order management from vendors  
**User Roles**: Admin (1), Systemadmin (5)  
**Status**: ✅ Active

## Functionality
### Core Features
- Create/edit/delete purchase orders to vendors
- Track purchase status: unpaid, partially paid, fully paid
- Record payments against purchases
- Multiple products per purchase order
- File attachment support (invoices, receipts)
- PDF generation and email
- Integration with product warehouses

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | productpurchase | List all purchase orders | productpurchase |
| `add()` | productpurchase/add | Create new purchase | productpurchase_add |
| `edit(id)` | productpurchase/edit/[id] | Edit purchase (no payments) | productpurchase_edit |
| `view(id)` | productpurchase/view/[id] | View purchase details | productpurchase_view |
| `delete(id)` | productpurchase/delete/[id] | Delete purchase (no payments) | productpurchase_delete |
| `saveproductpurchasepayment()` | productpurchase/saveproductpurchasepayment | Add payment | productpurchase_add |
| `print_preview(id)` | productpurchase/print_preview/[id] | Generate PDF | productpurchase_view |

## Data Layer
### Models Used
- productpurchase_m, productpurchaseitem_m, productpurchasepaid_m
- product_m, productcategory_m, productsupplier_m, productwarehouse_m

### Database Tables
- `productpurchase` - Purchase order header
- `productpurchaseitem` - Line items with product, quantity, unit price
- `productpurchasepaid` - Payments against purchases
- `productsupplier` - Vendor information (legacy name)
- `productwarehouse` - Warehouse assignment

## Validation Rules
- `productsupplierID`: required, must exist
- `productwarehouseID`: required, must exist
- `productpurchasedate`: required, dd-mm-yyyy format
- `productitem`: JSON array, at least one product required
- Amounts: numeric, max 15 chars

## Dependencies & Interconnections
### Depends On
- **Vendor/Productsupplier**: Supplier selection
- **Product/Productcategory**: Product definitions
- **Productwarehouse**: Warehouse assignment

### Used By
- **Productsale/Inventoryinvoice**: Stock availability calculations
- **Inventory Reports**: Purchase analysis

## User Flows
### Create Purchase Order
1. Admin navigates to productpurchase/add
2. Selects:
   - Supplier/vendor
   - Product warehouse
   - Purchase date
3. Adds products:
   - Select category → products load
   - Select product
   - Enter unit price and quantity
4. Optional: Upload supporting document (invoice/receipt)
5. Save → Creates:
   - `productpurchase` record (status=0 unpaid)
   - `productpurchaseitem` records for each product
6. Increases available stock for product sales

### Record Payment
1. View purchase → "Add Payment"
2. Enter:
   - Payment date
   - Reference number
   - Amount (validates <= due amount)
   - Payment method (1=Cash, 2=Cheque, 3=Card, 4=Other)
   - Optional file
3. Save → Creates `productpurchasepaid` and updates status:
   - 0 = unpaid
   - 1 = partially paid
   - 2 = fully paid

## Edge Cases & Limitations
### Known Issues
1. **No Purchase Returns**: Cannot record returns to vendor
2. **Edit Restrictions**: Cannot edit if any payment exists
3. **No Backorders**: All purchases assumed received immediately

### Constraints
- Cannot delete purchase with payments
- Cannot edit purchase with payments
- Supplier and warehouse must exist

## Configuration
### Required Settings
- At least one vendor/supplier
- At least one product warehouse

## Notes for AI Agents
### Key Patterns
1. **Stock Increases**: Purchase increases available stock immediately
2. **Payment Status**: Calculated from total amount vs payments
3. **Multi-Item Support**: One purchase order can have many products

### Common Modifications
- Add purchase approval workflow
- Implement partial deliveries
- Add expected delivery dates
- Backorder management
- Purchase order numbers
- Vendor performance tracking

### Debugging Tips
- Check `productpurchasestatus`: 0=unpaid, 1=partial, 2=paid
- Verify supplier exists in productsupplier table
- Check file upload path: uploads/images/
- Verify total calculation: SUM(unit_price * quantity)
