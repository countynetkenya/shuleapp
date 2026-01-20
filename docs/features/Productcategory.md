# Feature: Productcategory

## Overview
**Controller**: `mvc/controllers/Productcategory.php`  
**Primary Purpose**: Manages product categories for organizing inventory items  
**User Roles**: Admin, Inventory Manager  
**Status**: ✅ Active

## Functionality
### Core Features
- Product category management (add, edit, delete)
- Category name and description tracking
- Automatic timestamp tracking
- User audit trail (creator tracking)
- School-specific category organization

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | `productcategory/index` | List all product categories | `productcategory` |
| `add()` | `productcategory/add` | Add new category | `productcategory_add` |
| `edit()` | `productcategory/edit/{id}` | Edit category | `productcategory_edit` |
| `delete()` | `productcategory/delete/{id}` | Delete category | `productcategory_delete` |

## Data Layer
### Models Used
- `productcategory_m` - Category operations

### Database Tables
- `productcategory` - Product categories:
  - `productcategoryID` (PK)
  - `productcategoryname` - Category name
  - `productcategorydesc` - Category description
  - `create_date` - Creation timestamp
  - `modify_date` - Last modification timestamp
  - `create_userID` - Creator user ID
  - `create_usertypeID` - Creator user type
  - `schoolID` - School identifier

## Validation Rules
1. **productcategoryname**: Required, max 128 chars, XSS clean, unique within school
2. **productcategorydesc**: Optional, max 520 chars, XSS clean

## Dependencies & Interconnections
### Depends On (Upstream)
- User authentication for creator tracking

### Used By (Downstream)
- `Product` - Products must belong to a category

### Related Features
- **Product**: Main consumer of categories

## User Flows
### Primary Flow: Add Category
1. Admin navigates to `productcategory/add`
2. Enters category name and optional description
3. System validates name uniqueness within school
4. System sets create_date and modify_date to now
5. Category saved with creator info, redirect to index

### Secondary Flow: Edit Category
1. Admin clicks edit from index
2. System loads category (validates schoolID)
3. Admin modifies name and/or description
4. System validates new name is unique (excluding current record)
5. System updates modify_date
6. Category updated, redirect to index

## Edge Cases & Limitations
1. **Name Uniqueness**: Enforced at school level
2. **Description Length**: Up to 520 characters allowed
3. **Deletion**: No check if category has products before deletion
4. **School Isolation**: Categories strictly filtered by schoolID

## Configuration
- Language file: `mvc/language/{lang}/productcategory_lang.php`

## Notes for AI Agents
- **Delete Safety**: Should verify no products use category before allowing deletion
- **Simple Structure**: Minimal fields - just name and description
- **Timestamp Tracking**: Both creation and modification dates tracked
- **Description Optional**: Category can exist with just a name
