# Feature: Productwarehouse

## Overview
**Controller**: `mvc/controllers/Productwarehouse.php`  
**Primary Purpose**: Manages warehouse/storage location information for inventory organization  
**User Roles**: Admin, Inventory Manager  
**Status**: ✅ Active

## Functionality
### Core Features
- Warehouse/storage location management (add, edit, delete)
- Warehouse naming and coding system
- Contact information per warehouse
- Location address tracking
- Automatic timestamp tracking
- User audit trail
- School-specific warehouse organization

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | `productwarehouse/index` | List all warehouses | `productwarehouse` |
| `add()` | `productwarehouse/add` | Add new warehouse | `productwarehouse_add` |
| `edit()` | `productwarehouse/edit/{id}` | Edit warehouse | `productwarehouse_edit` |
| `delete()` | `productwarehouse/delete/{id}` | Delete warehouse | `productwarehouse_delete` |

## Data Layer
### Models Used
- `productwarehouse_m` - Warehouse operations

### Database Tables
- `productwarehouse` - Warehouse records:
  - `productwarehouseID` (PK)
  - `productwarehousename` - Warehouse name
  - `productwarehousecode` - Warehouse code (unique identifier)
  - `productwarehouseemail` - Contact email
  - `productwarehousephone` - Contact phone
  - `productwarehouseaddress` - Physical address
  - `create_date` - Creation timestamp
  - `modify_date` - Last modification timestamp
  - `create_userID` - Creator user ID
  - `create_usertypeID` - Creator user type
  - `schoolID` - School identifier

## Validation Rules
1. **productwarehousename**: Required, max 128 chars, XSS clean, unique combination with code
2. **productwarehousecode**: Required, max 128 chars, XSS clean, unique within school
3. **productwarehouseemail**: Optional, max 40 chars, valid email format, XSS clean
4. **productwarehousephone**: Optional, max 20 chars, XSS clean
5. **productwarehouseaddress**: Optional, max 520 chars, XSS clean

## Dependencies & Interconnections
### Depends On (Upstream)
- User authentication for creator tracking

### Used By (Downstream)
- `Product` - Products stored in warehouses
- `Stock` - Stock adjustments and transfers reference warehouses
- `Productpurchase` - Purchases received at warehouses
- `Productsale` - Sales from warehouses

### Related Features
- **Product**: Products located in warehouses
- **Stock**: Warehouse-based stock management
- **Productpurchase**: Receiving at warehouses
- **Productsale**: Shipping from warehouses

## User Flows
### Primary Flow: Add Warehouse
1. Admin navigates to `productwarehouse/add`
2. Enters warehouse name and unique code
3. Optionally enters email, phone, address
4. System validates name+code combination uniqueness
5. System validates code uniqueness separately
6. System sets timestamps
7. Warehouse saved with creator info, redirect to index

### Secondary Flow: Edit Warehouse
1. Admin clicks edit from index
2. System loads warehouse (validates schoolID)
3. Admin modifies details
4. System validates name+code uniqueness (excluding current)
5. System updates modify_date
6. Warehouse updated, redirect to index

## Edge Cases & Limitations
1. **Dual Uniqueness**: Both code alone AND name+code combination must be unique
2. **Code Required**: Warehouse code is mandatory, unlike optional fields
3. **Contact Optional**: Email, phone, address are optional
4. **Deletion**: No check if warehouse has inventory before deletion
5. **School Isolation**: Warehouses strictly filtered by schoolID
6. **Address Length**: Up to 520 characters for address

## Configuration
- Language file: `mvc/language/{lang}/productwarehouse_lang.php`

## Notes for AI Agents
- **Warehouse Code**: Acts as primary business key, must be unique within school
- **Delete Safety**: Should verify warehouse has no inventory before allowing deletion
- **Uniqueness Logic**: Has TWO uniqueness checks:
  1. Code must be unique
  2. Name+Code combination must be unique (seems redundant)
- **Contact Info**: Useful for multi-location schools with different warehouse managers
- **Transfer Operations**: Critical for stock movement between warehouses
