# Feature: Purchase

## Overview
**Controller**: `mvc/controllers/Purchase.php`  
**Primary Purpose**: Track asset purchases from vendors with approval workflow  
**User Roles**: Admin (1), Systemadmin (5), others (pending approval)  
**Status**: ✅ Active

## Functionality
### Core Features
- Create/edit/delete asset purchase records
- Track vendor, purchaser, quantity, unit, price
- Three unit types: Kg, Piece, Other
- Approval workflow (status 0=pending, 1=approved)
- Optional service/expiration date tracking
- Date validation (dd-mm-yyyy format)

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | purchase | List all purchases | purchase |
| `add()` | purchase/add | Create new purchase | purchase_add |
| `edit(id)` | purchase/edit/[id] | Edit existing purchase | purchase_edit |
| `delete(id)` | purchase/delete/[id] | Delete purchase | purchase_delete |
| `status(id)` | purchase/status/[id] | Toggle approval status | purchase_edit |

## Data Layer
### Models Used
- purchase_m, user_m, asset_m, vendor_m

### Database Tables
- `purchase` - Purchase records
  - purchaseID (PK), assetID (FK), vendorID (FK)
  - purchased_by (userID), usertypeID
  - quantity, unit (1=Kg, 2=Piece, 3=Other), purchase_price
  - purchase_date, service_date, expire_date
  - status (0=pending, 1=approved)
  - create_date, modify_date, create_userID, create_usertypeID
  - schoolID

## Validation Rules
### Add/Edit Purchase
- `assetID`: required, numeric, max 128 chars, callback validates != 0
- `vendorID`: required, numeric, max 11 chars, callback validates != 0
- `purchased_by`: required, numeric, max 11 chars, callback validates != 0
- `quantity`: required, numeric, max 11 chars
- `unit`: required, numeric, max 11 chars, callback validates != 0
- `purchase_price`: required, max 11 chars
- `purchase_date`: optional, dd-mm-yyyy format, callback validates
- `service_date`: optional, dd-mm-yyyy format, callback validates
- `expire_date`: optional, dd-mm-yyyy format, callback validates

### Date Validation
- Format: dd-mm-yyyy
- Uses `checkdate()` for calendar validation
- All dates optional (nullable)

## Dependencies & Interconnections
### Depends On (Upstream)
- **Asset**: Assets being purchased
- **Vendor**: Vendors supplying assets
- **User**: Staff making purchases

### Used By (Downstream)
- Asset tracking system
- Vendor payment processing
- Inventory management

### Related Features
- Asset, Asset_assignment, Vendor, Expense

## User Flows
### Primary Flow: Create Purchase
1. Staff navigates to purchase/add
2. Select asset from dropdown
3. Select vendor
4. Select purchaser (user)
5. Enter quantity, unit, price
6. Optionally enter purchase/service/expire dates
7. Submit creates purchase:
   - If usertype 1 or 5: status=1 (auto-approved)
   - Otherwise: status=0 (pending approval)
8. Redirects to index with success message

### Approval Workflow
1. Non-admin creates purchase (status=0)
2. Admin/systemadmin views index, sees pending purchases
3. Admin clicks status toggle
4. Status flips: 0→1 (approve) or 1→0 (revoke)
5. Page refreshes with updated status

### Edit Purchase Flow
1. Load existing purchase with asset/vendor details
2. Modify fields
3. Submit applies same approval logic:
   - If usertype 1 or 5: status=1
   - Otherwise: status=0
4. Updates modify_date

## Edge Cases & Limitations
- **Auto-Approval**: Admin/systemadmin purchases auto-approved (bypasses workflow)
- **Status Toggle**: Any edit by non-admin resets status to 0 (needs re-approval)
- **No Audit Trail**: Status changes not logged (who approved/when)
- **Date Nullability**: Dates can be null (not enforced)
- **Unit Hardcoded**: Only 3 unit types, not configurable
- **No Purchase History**: Editing doesn't preserve previous versions
- **Debug Code**: Line 257 has `dd($array);` after redirect (unreachable dead code)

## Configuration
- No environment variables
- Unit types: 1=Kg, 2=Piece, 3=Other (hardcoded in controller and view)

## Notes for AI Agents
### Unit Type Mapping
```php
$this->data['unit'] = [
    1 => $this->lang->line('purchase_unit_kg'),
    2 => $this->lang->line('purchase_unit_piece'),
    3 => $this->lang->line('purchase_unit_other')
];
```

### Approval Logic
```php
if ($usertypeID == 1 || $usertypeID == 5) {
    $array["status"] = 1; // Auto-approved
} else {
    $array["status"] = 0; // Pending
}
```

### Status Toggle Logic
```php
if($purchase->status == 1) {
    $this->purchase_m->update_purchase(['status' => 0], $id); // Revoke
} else {
    $this->purchase_m->update_purchase(['status' => 1], $id); // Approve
}
```

### Date Conversion
```php
// Input: "25-12-2024" (dd-mm-yyyy)
if($this->input->post('purchase_date')) {
    $array["purchase_date"] = date("Y-m-d", strtotime($date)); // 2024-12-25
}
```

### Usertype Lookup on Purchased_by
```php
if($this->input->post('purchased_by')) {
    $user = $this->user_m->get_single_user([
        'userID' => $purchased_by,
        'schoolID' => $schoolID
    ]);
    $array['usertypeID'] = $user->usertypeID ?? 0;
}
```
- Stores purchaser's usertype for reference

### Dead Code Alert
- Line 257: `dd($array);` after redirect (never executes)
- Should be removed

### Select2 Integration
- Uses Select2 for asset, vendor, user dropdowns
- Assets: `assets/select2/css/select2.css`, `assets/select2/select2.js`

### Audit Fields
- create_date, create_userID, create_usertypeID: Set on add
- modify_date: Updated on edit
- No modify_userID (who edited not tracked)

### Permission Check for Status Toggle
- Method checks `permissionChecker('purchase_edit')`
- Redirects to exception page if no permission
- Allows any user with edit permission to approve (not restricted to admin)

