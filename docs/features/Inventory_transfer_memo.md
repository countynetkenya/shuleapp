# Feature: Inventory_transfer_memo

## Overview
**Controller**: `mvc/controllers/Inventory_transfer_memo.php`  
**Primary Purpose**: Dedicated interface for recording inventory transfers/movements between warehouses  
**User Roles**: Admin, Inventory Manager  
**Status**: ✅ Active

## Functionality
### Core Features
- Stock transfer memo creation (dedicated interface)
- Inter-warehouse stock movement tracking
- Batch product transfer entry
- Transfer memo viewing and history
- Approval workflow for transfers
- Transaction audit trail with user tracking
- Quantity totaling across transfer items
- Source and destination warehouse tracking

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | `inventory_transfer_memo/index` | List all transfer memos | `inventory_transfer_memo` |
| `add()` | `inventory_transfer_memo/add` | Form to create transfer memo | `inventory_transfer_memo_add` |
| `savemovement()` | `inventory_transfer_memo/savemovement` | Process transfer (AJAX) | `inventory_transfer_memo_add` |
| `view()` | `inventory_transfer_memo/view/{id}` | View transfer memo details | `inventory_transfer_memo_view` |
| `approve()` | `inventory_transfer_memo/approve/{id}` | Approve transfer memo | `inventory_transfer_memo_approve` |

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
  - `stockfromwarehouseID` - Source warehouse
  - `stocktowarehouseID` - Destination warehouse
  - `type` - 'movement'
  - `memo` - Transfer reason/notes
  - `mainstockuserID` - User who created
  - `mainstockusertypeID` - User type
  - `mainstockuname` - User name
  - `mainstockcreate_date` - Creation date
  - `schoolID` - School identifier
- `stock` - Stock transfer items:
  - `stockID` (PK)
  - `productID` - Product reference
  - `quantity` - Transfer quantity (always positive)
  - `mainstockID` - Parent transfer memo
  - `approved` - Approval status (0/1)
  - `create_date` - Creation timestamp
  - `create_userID` - Creator user ID
  - `create_usertypeID` - Creator user type
  - `schoolID` - School identifier

## Validation Rules
1. **fromproductwarehouseID**: Required, numeric, max 11 chars, XSS clean
2. **toproductwarehouseID**: Required, numeric, max 11 chars, must differ from source
3. **productitems**: Required JSON array, each item must have productID and non-empty amount
4. **memo**: Optional text for transfer notes

## Dependencies & Interconnections
### Depends On (Upstream)
- `Product` - Products being transferred
- `Productwarehouse` - Source and destination warehouses
- `Productpurchaseitem` - For current stock display
- `Productsaleitem` - For current stock display

### Used By (Downstream)
- Inventory reports
- Stock level calculations per warehouse
- Audit trails

### Related Features
- **Stock**: Parent controller with similar functionality
- **Product**: Products being transferred
- **Productwarehouse**: Transfer locations

## User Flows
### Primary Flow: Create Transfer Memo
1. Admin navigates to `inventory_transfer_memo/add`
2. Selects source warehouse (fromproductwarehouseID)
3. Selects destination warehouse (toproductwarehouseID) - must be different
4. System loads available stock levels for source warehouse
5. Admin adds products with transfer quantities (positive numbers only)
6. Enters memo explaining transfer reason
7. Clicks submit (AJAX)
8. System validates warehouses are different
9. System validates product items have quantities
10. System creates mainstock record (type='movement')
11. System creates stock items for each product
12. Success response, redirect to index

### Secondary Flow: View Transfer Memo
1. Admin clicks view from index
2. System loads transfer memo details
3. Displays:
   - Source warehouse name
   - Destination warehouse name
   - Creation date and user
   - Memo notes
   - List of products with quantities
   - Total quantity across all items
4. Option to approve if not already approved

### Approval Flow
1. Admin views transfer memo
2. Clicks approve
3. System validates user is not the creator (no self-approval)
4. System sets approved=1 for stock item
5. Success message, redirect to index

## Edge Cases & Limitations
1. **Warehouse Validation**: Source and destination must be different
2. **Positive Quantities Only**: Transfers use positive quantities (direction indicated by from/to)
3. **Self-Approval**: Users cannot approve their own transfers
4. **No Validation**: System doesn't check if source has sufficient stock
5. **JSON Submission**: Product items must be submitted as JSON array
6. **Duplicate Functionality**: Very similar to Stock controller's move() method
7. **No Edit**: Once created, transfer memos cannot be edited, only approved
8. **No Delete**: No delete functionality provided
9. **No Partial Transfers**: Cannot mark items as partially received

## Configuration
- Language file: `mvc/language/{lang}/inventory_transfer_memo_lang.php`
- Select2 UI for product and warehouse selection
- AJAX JSON submission for save

## Notes for AI Agents
- **Purpose of Separate Controller**: Provides dedicated UI for transfers, separate from main Stock controller
- **Memo Importance**: Memo field is critical for audit trail - document reason for all transfers
- **Quantity Always Positive**: Transfer direction indicated by from/to warehouses, not negative quantities
- **No Stock Validation**: System allows transferring more than available stock (could go negative)
- **Approval Not Required**: Transfers take effect immediately, approval is optional audit step
- **Batch Processing**: Can transfer multiple products in single memo
- **Index Filtering**: Shows only movement type memos (type='movement')
- **Available Stock Display**: Add form shows current stock levels at source warehouse
- **Warehouse Selection Order**: Must select source warehouse first to enable stock level lookup
- **Physical Movement**: Represents physical movement of goods, should match actual warehouse operations
- **Inventory Impact**: 
  - Decreases stock at source warehouse
  - Increases stock at destination warehouse
  - Net system inventory remains the same
