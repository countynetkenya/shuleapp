# Feature: Product

## Overview
**Controller**: `mvc/controllers/Product.php`  
**Primary Purpose**: Manages product catalog including pricing, categories, and warehouse-specific inventory tracking  
**User Roles**: Admin, Inventory Manager  
**Status**: ✅ Active

## Functionality
### Core Features
- Product catalog management (add, edit, view, delete)
- Buying and selling price tracking
- Product categorization
- Product descriptions
- Warehouse-specific stock viewing
- Product history tracking (purchases, sales, adjustments, movements)
- Date range and monthly filtering for product history
- Automatic timestamp tracking for creation/modification

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | `product/index/{warehouseID?}` | List products with stock levels | `product` |
| `add()` | `product/add` | Add new product | `product_add` |
| `edit()` | `product/edit/{id}` | Edit product details | `product_edit` |
| `view()` | `product/view?id={id}&warehouse={wh}&from={date}&to={date}&month={mm-yyyy}` | View product details and history | `product_view` |
| `delete()` | `product/delete/{id}` | Delete product | `product_delete` |
| `product_list()` | `product/product_list` | AJAX: Redirect to warehouse filter | - |

## Data Layer
### Models Used
- `product_m` - Product catalog operations
- `productcategory_m` - Product categories
- `productsaleitem_m` - Sales transactions
- `productpurchaseitem_m` - Purchase transactions
- `productwarehouse_m` - Warehouse locations
- `stock_m` - Stock adjustments/movements

### Database Tables
- `product` - Product catalog:
  - `productID` (PK)
  - `productname` - Product name
  - `productcategoryID` - Category reference
  - `productbuyingprice` - Purchase/cost price
  - `productsellingprice` - Selling price
  - `productdesc` - Product description (max 250 chars)
  - `create_date` - Creation timestamp
  - `modify_date` - Last modification timestamp
  - `create_userID` - Creator user ID
  - `create_usertypeID` - Creator user type
  - `schoolID` - School identifier
- `productcategory` - Product categories
- `productpurchaseitem` - Purchase history
- `productsaleitem` - Sales history
- `stock` - Adjustments/movements

## Validation Rules
1. **productname**: Required, max 60 chars, XSS clean, unique within school
2. **productcategoryID**: Required, numeric, max 11 chars, must be > 0 (valid category)
3. **productbuyingprice**: Required, numeric, max 15 chars
4. **productsellingprice**: Required, numeric, max 15 chars
5. **productdesc**: Optional, max 250 chars, XSS clean

## Dependencies & Interconnections
### Depends On (Upstream)
- `Productcategory` - Product must belong to category
- User authentication for creator tracking

### Used By (Downstream)
- `Productpurchase` - Products purchased
- `Productsale` - Products sold
- `Stock` - Products adjusted/transferred
- `Productpurchaseitem` - Purchase line items
- `Productsaleitem` - Sales line items

### Related Features
- **Productcategory**: Categorizes products
- **Productwarehouse**: Stores products
- **Stock**: Adjustments and transfers
- **Productpurchase**: Receiving inventory
- **Productsale**: Selling inventory

## User Flows
### Primary Flow: Add Product
1. Admin navigates to `product/add`
2. Enters product name, selects category
3. Enters buying price and selling price
4. Optionally enters description
5. System validates uniqueness of product name
6. System validates category exists
7. System sets create_date and modify_date to now
8. Product saved with creator info, redirect to index

### Secondary Flow: View Product History
1. Admin selects product from index
2. Selects warehouse to view
3. Optionally selects date range or month
4. System displays:
   - Basic info (name, category, prices)
   - Last buying price from purchases
   - Average unit cost
   - Last supplier
   - Warehouse quantity breakdown
   - Received items (purchases)
   - Sold items (sales)
   - Adjustments
   - Movements in/out
5. All filtered by selected warehouse and date range

### Warehouse Filtering Flow
1. Index displays all products by default
2. User selects warehouse from dropdown
3. System shows purchase/sale quantities specific to that warehouse
4. User can view detailed product history for that warehouse

## Edge Cases & Limitations
1. **Product Name Uniqueness**: Checked at school level only
2. **Category Required**: Cannot save product without valid category
3. **Price Fields**: Both buying and selling prices required, no validation for selling > buying
4. **Description Length**: Limited to 250 characters
5. **No Barcode/SKU**: No built-in barcode or SKU field
6. **Deletion**: No check for existing inventory before allowing deletion
7. **History Filtering**: Supports date range OR month, not both simultaneously

## Configuration
- Language file: `mvc/language/{lang}/product_lang.php`
- Select2 UI for category selection
- Maximum description length: 250 characters

## Notes for AI Agents
- **Pricing Strategy**: System tracks both buying and selling prices but doesn't enforce profit margin
- **Modification Tracking**: `modify_date` updated on edit but `create_date` preserved
- **History View**: Complex query combining purchases, sales, adjustments, and movements
- **Warehouse Filtering**: Index can show all warehouses or filter by one
- **Date Filtering**: Supports custom range (from/to) or month (mm-yyyy format)
- **Stock Levels**: Calculated from purchases - sales + adjustments ± movements
- **Average Cost**: Calculated from all purchases, useful for inventory valuation
- **Last Supplier**: Retrieved from most recent purchase
- **Category Dropdown**: Shows 0 as default, validation catches this
- **Quantity Display**: Index shows purchase and sale quantities separately per warehouse
- **Delete Safety**: Should check for inventory transactions before allowing deletion
