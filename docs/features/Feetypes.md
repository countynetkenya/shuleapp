# Feature: Feetypes

## Overview
**Controller**: `mvc/controllers/Feetypes.php`  
**Primary Purpose**: Define and manage fee types for student invoicing with QuickBooks income account mapping  
**User Roles**: Admin (1), Systemadmin (5)  
**Status**: ✅ Active

## Functionality
### Core Features
- Create/edit/delete fee type definitions
- Monthly fee type generation (auto-creates 12 fee types, one per month)
- QuickBooks income account mapping
- Fee type uniqueness validation per school
- School-specific fee type isolation

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | feetypes | List all fee types | feetypes |
| `add()` | feetypes/add | Create new fee type | feetypes_add |
| `edit(id)` | feetypes/edit/[id] | Edit existing fee type | feetypes_edit |
| `delete(id)` | feetypes/delete/[id] | Delete fee type | feetypes_delete |
| `unique_feetypes()` | N/A | Validation callback for uniqueness | N/A |
| `getIncomeAccountObj()` | N/A | Internal: Fetch QBO income accounts | N/A |

## Data Layer
### Models Used
- feetypes_m
- quickbookssettings_m
- quickbookslog_m

### Database Tables
- `feetypes` - Fee type definitions
  - feetypesID (PK), feetypes (name), note
  - incomeaccountID (QuickBooks account ID)
  - incomeaccount (QuickBooks account name), schoolID

## Validation Rules
### Add/Edit Fee Type
- `feetypes`: required, max 60 chars, xss_clean, unique per school
- `note`: optional, max 200 chars, xss_clean
- `monthly`: optional, numeric, max 11 chars (boolean 0/1)

### Monthly Fee Types
- When `monthly=1`, creates 12 fee types with format: `{name} [Jan]`, `{name} [Feb]`, etc.
- Each month's fee type validated for uniqueness independently

## Dependencies & Interconnections
### Depends On (Upstream)
- **QuickBooks**: Income account synchronization (optional)

### Used By (Downstream)
- **Invoice**: Fee types selected when creating invoices
- **Bundlefeetypes**: Fee types included in bundles
- **Creditmemo**: Fee types referenced in credit memos

### Related Features
- Invoice, Bundlefeetypes, Quickbooks, Quickbookssettings

## User Flows
### Primary Flow: Create Fee Type
1. Admin navigates to feetypes/add
2. If QuickBooks enabled, fetches income accounts from QBO
3. Enter fee type name and optional note
4. Optionally select QuickBooks income account
5. Check "Monthly" to auto-generate 12 monthly variants
6. Submit creates single OR 12 monthly fee type records
7. Redirects to index with success message

### QuickBooks Integration Flow
1. Check QuickBooks settings active status
2. Configure DataService with OAuth credentials
3. Retrieve access token from settings
4. Query QBO: `select Name from Account where AccountType='Income'`
5. Display income accounts in dropdown
6. On save, store `incomeaccountID` and `incomeaccount` name

## Edge Cases & Limitations
- **Monthly Generation**: Cannot edit monthly fee types back to single type
- **QuickBooks Optional**: Fee types work without QuickBooks; fields nullable
- **Uniqueness Check**: Monthly types check each month individually
- **School Isolation**: Fee types scoped to schoolID
- **No Cascade Delete**: Deleting doesn't check if used in invoices (orphaned references)
- **QuickBooks Token**: If expired, income accounts not displayed (graceful degradation)

## Configuration
### QuickBooks Settings (from quickbookssettings table)
- `client_id`, `client_secret`: OAuth2 credentials
- `stage`: development or production (sandbox vs live)
- `active`: 0/1 enable/disable integration
- `sessionAccessToken`: Serialized OAuth token
- `sessionAccessTokenExpiry`: Unix timestamp

## Notes for AI Agents
### QuickBooks Integration Pattern
- Uses QuickBooks PHP SDK (`vendor/quickbooks/`)
- OAuth 2.0 via `quickbooksConfig()` (inherited from Admin_Controller)
- Income account query constant: `INCOME_ACCOUNT_TYPE = "Income"`
- Logs all API calls to `quickbookslog` with IP, request, status
- Logs stored in `mvc/logs/quickbooks/{date}/`

### Monthly Fee Type Logic
```php
if($monthly) {
    for($i = 1; $i<=12; $i++) {
        $month = date('M', mktime(0, 0, 0, $i)); // Jan, Feb, Mar
        $feetypes = "{name} [{$month}]"; // Insert 12 records
    }
}
```

### Income Account Storage
- Stores both ID and name: format `"ID,Name"` comma-separated
- Parsing: `explode(",", $incomeaccount)` => `[0]=ID, [1]=Name`
- Redundancy in case QBO account deleted

### Performance Considerations
- QuickBooks DataService configured on every add/edit (even if not used)
- Income account query not cached, can be slow
- Consider lazy loading QuickBooks integration

