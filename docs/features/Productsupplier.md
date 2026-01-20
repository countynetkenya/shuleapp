# Feature: Productsupplier

## Overview
**Controller**: `mvc/controllers/Productsupplier.php`  
**Primary Purpose**: Manages supplier/vendor information for product procurement  
**User Roles**: Admin, Inventory Manager, Procurement Officer  
**Status**: ✅ Active

## Functionality
### Core Features
- Supplier/vendor management (add, edit, delete)
- Company and contact person tracking
- Contact information (email, phone, address)
- Automatic timestamp tracking
- User audit trail
- School-specific supplier organization

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | `productsupplier/index` | List all suppliers | `productsupplier` |
| `add()` | `productsupplier/add` | Add new supplier | `productsupplier_add` |
| `edit()` | `productsupplier/edit/{id}` | Edit supplier | `productsupplier_edit` |
| `delete()` | `productsupplier/delete/{id}` | Delete supplier | `productsupplier_delete` |

## Data Layer
### Models Used
- `productsupplier_m` - Supplier operations

### Database Tables
- `productsupplier` - Supplier records:
  - `productsupplierID` (PK)
  - `productsuppliercompanyname` - Company name
  - `productsuppliername` - Contact person name
  - `productsupplieremail` - Email address
  - `productsupplierphone` - Phone number
  - `productsupplieraddress` - Physical address
  - `create_date` - Creation timestamp
  - `modify_date` - Last modification timestamp
  - `create_userID` - Creator user ID
  - `create_usertypeID` - Creator user type
  - `schoolID` - School identifier

## Validation Rules
1. **productsuppliercompanyname**: Required, max 128 chars, XSS clean, unique within school
2. **productsuppliername**: Required, max 40 chars, XSS clean
3. **productsupplieremail**: Optional, max 40 chars, valid email format, XSS clean
4. **productsupplierphone**: Optional, max 25 chars, min 5 chars, XSS clean
5. **productsupplieraddress**: Optional, max 128 chars, XSS clean

## Dependencies & Interconnections
### Depends On (Upstream)
- User authentication for creator tracking

### Used By (Downstream)
- `Productpurchase` - Purchases from suppliers

### Related Features
- **Productpurchase**: Records purchases from suppliers

## User Flows
### Primary Flow: Add Supplier
1. Admin navigates to `productsupplier/add`
2. Enters company name and contact person name
3. Optionally enters email, phone, address
4. System validates company name uniqueness
5. System validates email format if provided
6. System sets timestamps
7. Supplier saved with creator info, redirect to index

### Secondary Flow: Edit Supplier
1. Admin clicks edit from index
2. System loads supplier (validates schoolID)
3. Admin modifies details
4. System validates company name uniqueness (excluding current)
5. System updates modify_date
6. Supplier updated, redirect to index

## Edge Cases & Limitations
1. **Company Name Uniqueness**: Enforced at school level
2. **Contact Optional**: Email, phone, address are optional
3. **Phone Validation**: Minimum 5 characters if provided
4. **Deletion**: No check if supplier has purchase orders before deletion
5. **School Isolation**: Suppliers strictly filtered by schoolID

## Configuration
- Language file: `mvc/language/{lang}/productsupplier_lang.php`

## Notes for AI Agents
- **Delete Safety**: Should verify no purchase orders exist before allowing deletion
- **Contact Information**: Email and phone optional but recommended for procurement
- **Multiple Contacts**: System supports only one contact person per supplier
- **Address Format**: Free-text field, no structured address components
