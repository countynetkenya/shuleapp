# Feature: Asset_category

## Overview
**Controller**: `mvc/controllers/Asset_category.php`  
**Primary Purpose**: Simple CRUD for managing asset categories used to organize and classify physical assets.  
**User Roles**: Admin, Superadmin  
**Status**: ✅ Active

## Functionality
### Core Features
- **Category Management**: Create, edit, delete asset categories
- **Simple Structure**: Just category name and metadata
- **Active Status**: Track active vs. inactive categories
- **School-specific**: Categories scoped to school

### Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | asset_category/index | asset_category | List all categories |
| `add()` | asset_category/add | asset_category_add | Create new category |
| `edit()` | asset_category/edit/{id}` | asset_category_edit | Update category name |
| `delete()` | asset_category/delete/{id}` | asset_category_delete | Delete category |

## Data Layer
### Models Used
- `asset_category_m` - Category CRUD operations

### Database Tables
- `asset_category` - Categories (asset_categoryID, category, create_date, modify_date, create_userID, create_usertypeID, active, schoolID)

## Validation Rules
- **category**: Required, max 255 chars, category name

## Dependencies & Interconnections
### Depends On (Upstream)
- None (standalone)

### Used By (Downstream)
- **Asset** - Assets must belong to a category

### Related Features
- **Asset** - Uses categories for organization
- **Asset_assignment** - Inherits category from asset

## User Flows
### Create Category
1. Navigate to `asset_category/add`
2. Enter category name (e.g., "Electronics", "Furniture")
3. Submit - category created with active=1

### Edit Category
1. Navigate to `asset_category/index`
2. Click edit on category
3. Update name
4. Submit - modify_date updated

## Edge Cases & Limitations
- **Delete Restriction**: Should not allow delete if assets reference category (no foreign key check visible)
- **Duplicate Names**: No uniqueness check - can create duplicate categories
- **No Description**: Only stores name, no detailed description
- **Active Flag**: Has active field but no UI to toggle (always set to 1)
- **No Hierarchy**: Flat structure - no subcategories

## Configuration
None - straightforward CRUD

## Notes for AI Agents
- **Simple Controller**: Basic CRUD, no complex logic
- **Active Field**: Set to 1 on creation, never changed - consider adding toggle UI
- **Audit Fields**: Tracks create_userID, create_usertypeID, create_date, modify_date
- **No Soft Delete**: Deletion is permanent
- **School Scoping**: All queries filtered by `schoolID`
- **Foreign Key**: Consider adding check before delete to prevent orphaned assets
- **Category Examples**: Electronics, Furniture, Vehicles, Sports Equipment, Lab Equipment, Office Supplies

