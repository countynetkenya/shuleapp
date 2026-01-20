# Feature: Vendor

## Overview
**Controller**: `mvc/controllers/Vendor.php`  
**Primary Purpose**: Vendor/supplier management for product purchases  
**User Roles**: Admin (1), Systemadmin (5)  
**Status**: ✅ Active

## Functionality
### Core Features
- Create/edit/delete vendor records
- Store vendor contact information
- Unique email validation per school
- Vendor contact person details
- Integration with product purchase module

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | vendor | List all vendors | vendor |
| `add()` | vendor/add | Create new vendor | vendor_add |
| `edit(id)` | vendor/edit/[id] | Edit vendor | vendor_edit |
| `delete(id)` | vendor/delete/[id] | Delete vendor | vendor_delete |
| `view(id)` | vendor/view/[id] | View vendor details | vendor |

## Data Layer
### Models Used
- vendor_m, student_m

### Database Tables
- `vendor`: vendorID, name, email, phone, contact_name, schoolID

## Validation Rules
- `name`: required, max 128 chars
- `email`: optional, max 40 chars, valid email, unique per school
- `phone`: optional, max 25 chars
- `contact_name`: optional, max 40 chars

## Dependencies & Interconnections
### Used By
- **Productpurchase**: References vendors when creating purchase orders
- **Purchase Reports**: Vendor-wise purchase analysis

## Edge Cases & Limitations
### Known Issues
1. **No Archive**: Hard delete only (no soft delete)
2. **No Validation**: Can delete vendor even if referenced in purchases

### Constraints
- Email must be unique per school (if provided)
- Cannot have duplicate vendor names (not validated)

## Notes for AI Agents
### Key Patterns
- Simple CRUD for vendor management
- Email uniqueness check uses `vendorID !=` for edit mode
- School isolation via `schoolID` filter

### Common Modifications
- Add vendor categories
- Add vendor payment terms
- Add vendor address fields
- Add vendor tax ID
- Implement soft delete
- Add vendor performance metrics
