# Feature: Inventory_adjustment_memo

## Overview
**Controller**: `mvc/controllers/Inventory_adjustment_memo.php`  
**Primary Purpose**: Dedicated interface for recording inventory adjustments (stock increases/decreases) at a single warehouse  
**User Roles**: Admin, Inventory Manager  
**Status**: ✅ Active

## Functionality
### Core Features
- Stock adjustment memo creation (dedicated interface)
- Single warehouse adjustment processing
- Batch product adjustment entry
- Adjustment memo viewing and history
- Approval workflow for adjustments
- Transaction audit trail with user tracking
- Quantity totaling across adjustment items

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | `inventory_adjustment_memo/index` | List all adjustment memos | `inventory_adjustment_memo` |
| `add()` | `inventory_adjustment_memo/add` | Form to create adjustment memo | `inventory_adjustment_memo_add` |
| `saveadjustment()` | `inventory_adjustment_memo/saveadjustment` | Process adjustment (AJAX) | `inventory_adjustment_memo` |
| `view()` | `inventory_adjustment_memo/view/{id}` | View adjustment memo details | `inventory_adjustment_memo_view` |
| `approve()` | `inventory_adjustment_memo/approve/{id}` | Approve adjustment memo | `inventory_adjustment_memo_approve` |

## Data Layer
### Models Used
- `mainstock_m` - Main stock transaction records
- `stock_m` - Stock item details
- `product_m` - Product catalog
- `productwarehouse_m` - Warehouse information
- `productpurchaseitem_m` - Purchase quantities (for available stock display)
- `productsaleitem_m` - Sale quantities (for available stock display)

### Database Tables
- `mainstock` - Stock transaction headers:
  - `mainstockID` (PK)
  - `stockfromwarehouseID` - Warehouse being adjusted
  - `stocktowarehouseID` - NULL for adjustments
  - `type` - 'adjustment'
  - `memo` - Adjustment reason/notes
  - `mainstockuserID` - User who created
  - `mainstockusertypeID` - User type
  - `mainstockuname` - User name
  - `mainstockcreate_date` - Creation date
  - `schoolID` - School identifier
- `stock` - Stock adjustment items:
  - `stockID` (PK)
  - `productID` - Product reference
  - `quantity` - Adjustment quantity (positive=increase, negative=decrease)
  - `mainstockID` - Parent adjustment memo
  - `approved` - Approval status (0/1)
  - `create_date` - Creation timestamp
  - `create_userID` - Creator user ID
  - `create_usertypeID` - Creator user type
  - `schoolID` - School identifier

## Validation Rules
1. **fromproductwarehouseID**: Required, numeric, max 11 chars, XSS clean
2. **toproductwarehouseID**: Not used for adjustments, but validated if present
3. **productitems**: Required JSON array, each item must have productID and non-empty amount
4. **memo**: Optional text for adjustment notes

## Dependencies & Interconnections
### Depends On (Upstream)
- `Product` - Products being adjusted
- `Productwarehouse` - Warehouse location
- `Productpurchaseitem` - For current stock display
- `Productsaleitem` - For current stock display

### Used By (Downstream)
- Inventory reports
- Stock level calculations
- Audit trails

### Related Features
- **Stock**: Parent controller with similar functionality
- **Product**: Products being adjusted
- **Productwarehouse**: Adjustment location

## User Flows
### Primary Flow: Create Adjustment Memo
1. Admin navigates to `inventory_adjustment_memo/add`
2. Selects warehouse (fromproductwarehouseID)
3. System loads available stock levels for warehouse
4. Admin adds products with adjustment quantities:
   - Positive numbers = stock increase (found items, corrections)
   - Negative numbers = stock decrease (loss, damage, theft)
5. Enters memo explaining adjustment reason
6. Clicks submit (AJAX)
7. System validates product items have quantities
8. System creates mainstock record (type='adjustment')
9. System creates stock items for each product
10. Success response, redirect to index

### Secondary Flow: View Adjustment Memo
1. Admin clicks view from index
2. System loads adjustment memo details
3. Displays:
   - Warehouse name
   - Creation date and user
   - Memo notes
   - List of products with quantities
   - Total quantity across all items
4. Option to approve if not already approved

### Approval Flow
1. Admin views adjustment memo
2. Clicks approve
3. System validates user is not the creator (no self-approval)
4. System sets approved=1 for stock item
5. Success message, redirect to index

## Edge Cases & Limitations
1. **Positive/Negative Quantities**: Both allowed - no restriction on decreasing stock to negative
2. **Self-Approval**: Users cannot approve their own adjustments
3. **No Validation**: System doesn't check if decrease would result in negative inventory
4. **JSON Submission**: Product items must be submitted as JSON array
5. **Duplicate Functionality**: Very similar to Stock controller's adjust() method
6. **No Edit**: Once created, adjustment memos cannot be edited, only approved
7. **No Delete**: No delete functionality provided

## Configuration
- Language file: `mvc/language/{lang}/inventory_adjustment_memo_lang.php`
- Select2 UI for product and warehouse selection
- AJAX JSON submission for save

## Notes for AI Agents
- **Purpose of Separate Controller**: Provides dedicated UI for adjustments, separate from main Stock controller
- **Memo Importance**: Memo field is critical for audit trail - document reason for all adjustments
- **Quantity Sign Convention**: 
  - Positive = increase (found, corrected count up)
  - Negative = decrease (lost, damaged, stolen, corrected count down)
- **No Stock Validation**: System allows decreasing stock below zero
- **Approval Not Required**: Adjustments take effect immediately, approval is optional audit step
- **Batch Processing**: Can adjust multiple products in single memo
- **Index Filtering**: Shows only adjustment type memos (type='adjustment')
- **Available Stock Display**: Add form shows current stock levels to help with adjustment decisions
- **Warehouse Selection**: Must select warehouse before adding products (enables stock level lookup)
