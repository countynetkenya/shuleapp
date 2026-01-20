# Feature: Stock

## Overview
**Controller**: `mvc/controllers/Stock.php`  
**Primary Purpose**: Manages inventory stock adjustments and transfers between warehouses with approval workflow  
**User Roles**: Admin, Inventory Manager  
**Status**: ✅ Active

## Functionality
### Core Features
- Stock overview across all warehouses
- Stock adjustment (increase/decrease inventory at single warehouse)
- Stock transfer/movement between warehouses
- Batch product item processing
- Stock transaction history and audit trail
- Approval workflow for stock changes
- Quantity calculation across transactions

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | `stock/index` | View all stock transactions | `stock` |
| `adjust()` | `stock/adjust` | Form to adjust stock at warehouse | `stock_adjust` |
| `saveadjustment()` | `stock/saveadjustment` | Process stock adjustment | `stock_adjust` |
| `move()` | `stock/move` | Form to transfer stock between warehouses | `stock_move` |
| `savemovement()` | `stock/savemovement` | Process stock transfer | `stock_move` |
| `view()` | `stock/view/{id}` | View stock transaction details | `stock_view` |
| `approve()` | `stock/approve/{id}` | Approve stock transaction | `stock_approve` |

## Data Layer
### Models Used
- `mainstock_m` - Main stock transaction records
- `stock_m` - Stock item details
- `product_m` - Product catalog
- `productwarehouse_m` - Warehouse information
- `productpurchaseitem_m` - Purchase quantities
- `productsaleitem_m` - Sale quantities

### Database Tables
- `mainstock` - Stock transaction headers:
  - `mainstockID` (PK)
  - `stockfromwarehouseID` - Source warehouse
  - `stocktowarehouseID` - Destination warehouse (NULL for adjustments)
  - `type` - Transaction type ('adjustment' or 'movement')
  - `memo` - Transaction notes
  - `mainstockuserID` - User who created
  - `mainstockusertypeID` - User type
  - `mainstockuname` - User name
  - `mainstockcreate_date` - Creation date
  - `schoolID` - School identifier
- `stock` - Stock item details:
  - `stockID` (PK)
  - `productID` - Product reference
  - `quantity` - Quantity (can be negative for decreases)
  - `mainstockID` - Parent transaction
  - `approved` - Approval status
  - `create_date` - Creation timestamp
  - `create_userID` - Creator user ID
  - `create_usertypeID` - Creator user type
  - `schoolID` - School identifier

## Validation Rules
1. **fromproductwarehouseID**: Required, numeric, max 11 chars, XSS clean
2. **toproductwarehouseID**: Optional for adjustments, numeric, max 11 chars, must differ from source
3. **productitems**: Required JSON array, each item must have productID and non-empty amount

## Dependencies & Interconnections
### Depends On (Upstream)
- `Product` - Product catalog
- `Productwarehouse` - Warehouse locations
- `Productpurchaseitem` - Purchase inventory levels
- `Productsaleitem` - Sales inventory levels

### Used By (Downstream)
- Inventory reports and analytics
- Stock level calculations

### Related Features
- **Product**: Products being adjusted/transferred
- **Productwarehouse**: Warehouse locations
- **Inventory_adjustment_memo**: Dedicated adjustment interface
- **Inventory_transfer_memo**: Dedicated transfer interface

## User Flows
### Primary Flow: Stock Adjustment
1. Admin navigates to `stock/adjust`
2. Selects warehouse (fromproductwarehouseID)
3. Adds products with quantities (positive=increase, negative=decrease)
4. Enters memo/notes
5. System validates product items have quantities
6. System creates mainstock record (type='adjustment')
7. System creates stock item records for each product
8. Redirect to stock index with success

### Secondary Flow: Stock Transfer
1. Admin navigates to `stock/move`
2. Selects source warehouse (fromproductwarehouseID)
3. Selects destination warehouse (toproductwarehouseID) - must differ
4. Adds products with transfer quantities
5. Enters memo/notes
6. System validates warehouses are different
7. System creates mainstock record (type='movement')
8. System creates stock item records
9. Redirect to index with success

### Approval Flow
1. User views stock transaction
2. Clicks approve
3. System validates user is not the creator
4. System sets approved=1 for stock item
5. Success message, redirect to index

## Edge Cases & Limitations
1. **Negative Quantities**: Allowed for adjustments (decreases), validated as non-empty
2. **Warehouse Validation**: Transfer requires different source/destination warehouses
3. **Self-Approval**: Users cannot approve their own stock entries
4. **Batch Processing**: Multiple products processed in single transaction
5. **No Rollback**: No built-in undo mechanism for stock adjustments
6. **JSON Submission**: Product items submitted as JSON array
7. **Available Stock**: No validation against actual inventory levels before adjustment

## Configuration
- Language file: `mvc/language/{lang}/stock_lang.php`
- Select2 UI for product/warehouse selection
- AJAX submission for save operations

## Notes for AI Agents
- **Transaction Types**: 'adjustment' = single warehouse, 'movement' = between warehouses
- **Quantity Sign**: Positive = increase stock, Negative = decrease stock (for adjustments)
- **Approval System**: Present but not enforced - transactions work without approval
- **Inventory Calculation**: Uses `totalquantities()` helper to sum quantities per mainstock
- **Available Stock Display**: Index shows purchase quantities vs sales quantities per product/warehouse
- **No Stock Validation**: System doesn't prevent negative inventory or overselling
- **JSON Format**: productitems array contains objects with productID and amount
- **Separate Interfaces**: Stock controller has adjust/move, but dedicated Inventory_adjustment_memo and Inventory_transfer_memo controllers exist (duplicated functionality)
- **Memo Field**: Free-text field for transaction notes/reasons
