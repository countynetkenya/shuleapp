# Feature: Asset_assignment

## Overview
**Controller**: `mvc/controllers/Asset_assignment.php`  
**Primary Purpose**: Manages assignment/checkout of physical assets to users (teachers, students, staff) with quantity tracking, dates, and return status monitoring.  
**User Roles**: Admin, Superadmin  
**Status**: ✅ Active

## Functionality
### Core Features
- **Asset Checkout**: Assign assets to users with quantity tracking
- **Multi-user Support**: Assign to any usertype (Admin, Teacher, Student, Parent, User)
- **Quantity Management**: Track assigned quantities and return quantities
- **Date Tracking**: Record check-out and check-in dates
- **Status Monitoring**: Track assignment status (Assigned, Returned, Partially Returned)
- **Assignment History**: View all current and past assignments
- **Email/Print Support**: Generate assignment records

### Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | asset_assignment/index | asset_assignment | List all assignments with user details |
| `add()` | asset_assignment/add | asset_assignment_add | Create new assignment |
| `edit()` | asset_assignment/edit/{id}` | asset_assignment_edit | Update assignment (return assets) |
| `view()` | asset_assignment/view/{id}` | asset_assignment_view | View assignment details |
| `delete()` | asset_assignment/delete/{id}` | asset_assignment_delete | Delete assignment record |
| `print_preview()` | asset_assignment/print_preview/{id}` | asset_assignment_view | Printable assignment form |
| `send_mail()` | asset_assignment/send_mail | asset_assignment_view | Email assignment details |

## Data Layer
### Models Used
- `asset_assignment_m` - Assignment CRUD
- `asset_m` - Asset information
- `user_m`, `systemadmin_m`, `teacher_m`, `student_m`, `parents_m` - User data
- `location_m` - Location tracking
- `classes_m` - Student class information
- `purchase_m` - Asset purchase information (if needed)

### Database Tables
- `asset_assignment` - Assignment records (assetID, check_out_to (userID), usertypeID, assigned_quantity, check_out_date, due_return_date, check_in_date, check_in_quantity, note, status, schoolID)
- References: `asset`, `systemadmin`, `teacher`, `student`, `parents`, `user`

## Validation Rules
- **assetID**: Required, max 128 chars, must exist (callback: `unique_assetID()`)
- **assigned_quantity**: Required, numeric, max 128 chars, must not exceed available quantity (callback: `valid_quantity()`)
- **check_out_to**: Required, user ID to assign asset to
- **check_out_to_usertypeID**: Required, usertype of assignee
- **check_out_date**: Required, valid date format
- **due_return_date**: Optional, valid date format
- **check_in_date**: Optional, return date
- **check_in_quantity**: Optional, quantity returned
- **note**: Optional, assignment notes
- **status**: Required, valid status value (callback: `unique_status()`)

## Dependencies & Interconnections
### Depends On (Upstream)
- **Asset** - Must have assets registered before assignment
- All user type modules - To assign assets to users

### Used By (Downstream)
- Asset inventory management - Updates asset availability
- User profiles - May show assets assigned to user

### Related Features
- **Asset** - Asset registry
- **Asset_category** - Asset organization
- **Location** - Track where assigned assets are located

## User Flows
### Assign Asset to Teacher
1. Navigate to `asset_assignment/add`
2. Select asset from dropdown
3. Enter quantity to assign
4. Select usertype "Teacher"
5. AJAX loads teacher dropdown
6. Select specific teacher
7. Enter checkout date and due return date
8. Add optional note
9. Submit - asset marked as assigned

### Return Asset
1. Navigate to `asset_assignment/edit/{id}`
2. Enter check-in date
3. Enter quantity returned
4. If quantity returned < assigned quantity, status = "Partially Returned"
5. If quantity returned == assigned quantity, status = "Returned"
6. Submit - asset availability updated

## Edge Cases & Limitations
- **Quantity Validation**: Cannot assign more than available quantity
- **Partial Returns**: System tracks partial returns vs. full returns
- **Multiple Assignments**: Same asset can be assigned to multiple users if quantity permits
- **No Asset Reserve**: Assignments don't reserve assets - first-come-first-served
- **Date Validation**: Check-in date must be >= check-out date
- **User Lookup**: Uses separate query for each usertype (performance consideration)
- **Overdue Tracking**: Due date tracked but no automatic overdue alerts
- **Deletion**: Deleting assignment doesn't restore asset quantity automatically

## Configuration
### Status Values
- Assigned
- Returned
- Partially Returned
- (Possibly others defined in validation)

### User Type Mapping
- 1 = Systemadmin
- 2 = Teacher
- 3 = Student
- 4 = Parents
- Others = User table

## Notes for AI Agents
- **userTableCall() Method**: Private method that queries appropriate user table based on usertypeID - consider refactoring for performance
- **Quantity Tracking**: `assigned_quantity` - `check_in_quantity` = still outstanding
- **Status Updates**: When editing, system should auto-update status based on return quantity
- **Asset Availability**: Should update `asset` table quantity when assignment created/returned
- **School Scoping**: All assignments filtered by `schoolID`
- **Join Performance**: Index page joins assignment with asset and user tables - may be slow with many records
- **Date Format**: Ensure consistent date format across check-out, due, and check-in dates
- **Email/Print**: Generate assignment slips for user signature on checkout
- **Audit Trail**: Tracks create_userID and create_usertypeID for accountability
- **No Barcode**: No barcode scanning - manual asset selection
- **Return Validation**: `valid_quantity()` should ensure check-in quantity <= assigned quantity

