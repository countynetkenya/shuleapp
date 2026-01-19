# Feature: Asset

## Overview
**Controller**: `mvc/controllers/Asset.php`  
**Primary Purpose**: Physical asset inventory management system for tracking school property (equipment, furniture, technology) with categorization, location tracking, and condition monitoring.  
**User Roles**: Admin, Superadmin  
**Status**: ✅ Active

## Functionality
### Core Features
- **Asset Registration**: Record assets with serial numbers, descriptions, categories
- **Status Tracking**: Monitor asset status (Available, Assigned, Under Repair, Retired)
- **Condition Monitoring**: Track asset condition (Excellent, Good, Fair, Poor)
- **Category Organization**: Group assets by type (Electronics, Furniture, Vehicles, etc.)
- **Location Management**: Track physical location of assets
- **Attachment Support**: Upload photos, receipts, manuals
- **Print Support**: Generate asset reports
- **Email Sharing**: Send asset details via email

### Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | asset/index | asset | List all assets with category/location |
| `add()` | asset/add | asset_add | Register new asset |
| `edit()` | asset/edit/{id}` | asset_edit | Update asset details |
| `view()` | asset/view/{id}` | asset_view | View asset information |
| `delete()` | asset/delete/{id}` | asset_delete | Delete asset record |
| `print_preview()` | asset/print_preview/{id}` | asset_view | Printable asset sheet |
| `send_mail()` | asset/send_mail | asset_view | Email asset details |
| `download()` | asset/download/{id}` | asset_view | Download attachment |

## Data Layer
### Models Used
- `asset_m` - Asset CRUD operations
- `asset_category_m` - Asset categories
- `location_m` - Location/room management

### Database Tables
- `asset` - Asset records (serial, description, status, asset_condition, asset_categoryID, asset_locationID, attachment, schoolID)
- `asset_category` - Asset categories
- `location` - Physical locations

## Validation Rules
- **serial**: Required, unique, max 40 chars (callback: `unique_serial()`)
- **description**: Required, max 100 chars
- **status**: Required, numeric, max 20 chars, must be valid status (callback: `unique_status()`)
- **asset_condition**: Required, numeric, max 20 chars, must be valid condition (callback: `unique_condition()`)
- **asset_categoryID**: Required, numeric, max 11 chars, must exist (callback: `unique_category()`)
- **asset_locationID**: Optional, numeric, max 11 chars, must exist if provided (callback: `unique_loaction()`)
- **attachment**: Optional, max 512 chars filename (callback: `attachUpload()`)

## Dependencies & Interconnections
### Depends On (Upstream)
- **Asset_category** - Category definitions
- **Location** - Location/room definitions

### Used By (Downstream)
- **Asset_assignment** - Asset assignment to users

### Related Features
- **Asset_assignment** - Track who has which assets
- **Asset_category** - Organize assets by type
- **Location** - Physical location tracking

## User Flows
### Register New Asset
1. Navigate to `asset/add`
2. Enter serial number (barcode/asset tag)
3. Enter description (make/model details)
4. Select category (Electronics, Furniture, etc.)
5. Select status (Available)
6. Select condition (Excellent, Good, Fair, Poor)
7. Select location (room/building)
8. Upload attachment (photo, receipt)
9. Submit - asset registered in inventory

### Track Asset Lifecycle
1. Asset registered as "Available"
2. When assigned to user, status changed to "Assigned" (via Asset_assignment)
3. If damaged, condition updated to "Fair" or "Poor"
4. If broken, status changed to "Under Repair"
5. When obsolete, status changed to "Retired"

## Edge Cases & Limitations
- **Serial Number Uniqueness**: Must be unique across all assets
- **Status Values**: Likely enum or lookup table (1=Available, 2=Assigned, etc.)
- **Condition Values**: Numeric representation of condition
- **Category Required**: Cannot create asset without category
- **Location Optional**: Assets can exist without location assignment
- **Attachment Storage**: Single attachment per asset
- **School Scoping**: Assets tied to specific school
- **No Audit Trail**: Updates overwrite previous data (no history)

## Configuration
### Status Values (likely in language file or constant)
- Available
- Assigned
- Under Repair
- Retired

### Condition Values
- Excellent
- Good
- Fair
- Poor

### Asset Categories
Managed via Asset_category controller (e.g., Electronics, Furniture, Vehicles, Sports Equipment)

## Notes for AI Agents
- **Serial Number**: Primary identifier for assets - enforce uniqueness
- **Category Relationship**: Joins `asset_category` table for display
- **Location Relationship**: Joins `location` table for physical tracking
- **Assignment Link**: When asset assigned, update status to "Assigned" and create `asset_assignment` record
- **Attachment Handling**: Files stored separately, filename saved in database
- **Print Format**: Generate labels/sheets for physical asset tags
- **Email Feature**: Share asset details with maintenance staff, vendors
- **Validation Callbacks**: All callbacks check against lookup tables or enums
- **School Scoping**: All queries filtered by `schoolID` from session
- **No Depreciation**: System doesn't track financial depreciation
- **No Maintenance Log**: Repairs/maintenance tracked elsewhere (possibly in notes)

