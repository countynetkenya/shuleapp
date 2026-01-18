# Feature: Bundlefeetypes

## Overview
**Controller**: `mvc/controllers/Bundlefeetypes.php`  
**Primary Purpose**: Create fee bundles that group multiple fee types with preset amounts for bulk invoicing  
**User Roles**: Admin (1), Systemadmin (5)  
**Status**: ✅ Active

## Functionality
### Core Features
- Create/edit/delete fee bundles (e.g., "Full Year Tuition" = Tuition + Books + Lab Fees)
- Add multiple fee types to bundle with individual amounts
- Dynamic fee type selection via AJAX
- School year lock (edit/delete only in active year unless superadmin)
- JSON-based fee type item handling

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | bundlefeetypes | List all bundles | bundlefeetypes |
| `add()` | bundlefeetypes/add | Create new bundle | bundlefeetypes_add |
| `edit(id)` | bundlefeetypes/edit/[id] | Edit existing bundle | bundlefeetypes_edit |
| `delete(id)` | bundlefeetypes/delete/[id] | Delete bundle | bundlefeetypes_delete |
| `getfeetypes()` | bundlefeetypes/getfeetypes (AJAX) | Get fee types in bundle | bundlefeetypes |
| `unique_bundlefeetypes()` | N/A | Validation callback | N/A |
| `unique_feetypeitems()` | N/A | Validation callback for items | N/A |

## Data Layer
### Models Used
- bundlefeetypes_m, bundlefeetype_feetypes_m, feetypes_m

### Database Tables
- `bundlefeetypes` - Bundle definitions
  - bundlefeetypesID (PK), bundlefeetypes (name), note, schoolID
- `bundlefeetype_feetypes` - Fee types in each bundle
  - bundlefeetype_feetypesID (PK)
  - bundlefeetypesID (FK), feetypesID (FK)
  - feetypes (name copy), amount, schoolID

## Validation Rules
### Add/Edit Bundle
- `bundlefeetypes`: required, max 60 chars, xss_clean, unique per school
- `feetypeitems`: required, JSON array, callback validates non-empty amounts
- `note`: optional, max 200 chars, xss_clean

### Fee Type Items Validation
- JSON format: `[{"feetypeID": 1, "amount": 5000}, ...]`
- Each item must have non-empty amount
- Validates at least one fee type in bundle

## Dependencies & Interconnections
### Depends On (Upstream)
- **Feetypes**: Fee types selected for bundle

### Used By (Downstream)
- **Invoice**: Bundles selected when creating invoices (expands to individual fees)
- **Invoice/getBundleFeetypePrice()**: AJAX endpoint to fetch bundle amounts

### Related Features
- Invoice, Feetypes

## User Flows
### Primary Flow: Create Bundle
1. Admin navigates to bundlefeetypes/add
2. Enter bundle name and note
3. Select fee types from dropdown (Select2)
4. Enter amount for each fee type
5. Add/remove fee types dynamically (JavaScript)
6. Submit validates fee type items
7. Creates bundle record + individual bundlefeetype_feetypes records
8. Returns JSON success message (AJAX form submission)

### Edit Bundle Flow
1. Load existing bundle and fee types
2. Display current fee types with amounts
3. Modify fee types/amounts
4. Submit deletes old bundlefeetype_feetypes records
5. Inserts new batch of fee type associations
6. Returns JSON success

### AJAX Fee Type Retrieval
- Frontend calls `getfeetypes()` with bundlefeetypeID
- Returns JSON array of fee types with amounts
- Used for populating invoice form when bundle selected

## Edge Cases & Limitations
- **School Year Lock**: Cannot edit/delete in past years (unless superadmin usertypeID=1)
- **JSON Submission**: Form submits via AJAX, returns JSON (not standard redirect)
- **Batch Delete**: On edit, deletes ALL old fee types then re-inserts (not incremental)
- **Fee Type Redundancy**: Stores fee type name in bundlefeetype_feetypes (denormalized)
- **No Cascade Delete**: Deleting bundle doesn't check if used in invoices
- **Amount Required**: Cannot save bundle with fee types but no amounts

## Configuration
- No environment variables or settings required
- School year lock uses `$this->data['siteinfos']->school_year` comparison

## Notes for AI Agents
### JSON Fee Type Items Format
```json
[
  {"feetypeID": 1, "amount": 5000},
  {"feetypeID": 3, "amount": 2500},
  {"feetypeID": 5, "amount": 1000}
]
```

### Batch Insert Logic
```php
$feetypesArray = [];
foreach($feetypeitems as $item) {
    $feetypesArray[] = [
        'feetypesID' => $item->feetypeID,
        'feetypes' => $feetype[$item->feetypeID], // Name lookup
        'amount' => $item->amount,
        'bundlefeetypesID' => $id,
        'schoolID' => $schoolID
    ];
}
$this->bundlefeetype_feetypes_m->insert_batch_bundlefeetype_feetypes($feetypesArray);
```

### Edit Pattern: Delete + Re-Insert
- Editing doesn't update existing records
- Deletes all: `delete_bundlefeetype_feetypes_by_bundlefeetypeID($id)`
- Then inserts new batch (simpler than tracking adds/removes)

### Select2 Integration
- Uses Select2 for multi-select fee type dropdown
- Assets: `assets/select2/css/select2.css`, `assets/select2/select2.js`

### AJAX Response Pattern
- Forms submit via AJAX POST
- Returns: `{"status": true, "message": "Success"}`
- Frontend handles redirect after JSON success

### Denormalization Trade-off
- Stores `feetypes` name in bundlefeetype_feetypes table
- Pro: Faster queries, no join needed
- Con: Name changes in feetypes not reflected in bundles
- Used for display and invoice generation

