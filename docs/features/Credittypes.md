# Feature: Credittypes

## Overview
**Controller**: `mvc/controllers/Credittypes.php`  
**Primary Purpose**: Define and manage credit type categories for student fee credits  
**User Roles**: Admin (1), Systemadmin (5)  
**Status**: ✅ Active

## Functionality
### Core Features
- Create/edit/delete credit type definitions
- QuickBooks income account mapping
- Monthly credit type generation (creates 12 types automatically)
- Unique credit type name validation per school
- QuickBooks Online income account integration

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | credittypes | List all credit types | credittypes |
| `add()` | credittypes/add | Create new credit type | credittypes_add |
| `edit(id)` | credittypes/edit/[id] | Edit credit type | credittypes_edit |
| `delete(id)` | credittypes/delete/[id] | Delete credit type | credittypes_delete |

## Data Layer
### Models Used
- credittypes_m, quickbookssettings_m, quickbookslog_m

### Database Tables
- `credittypes`: credittypesID, credittypes, note, incomeaccountID, incomeaccount, schoolID

## Validation Rules
- `credittypes`: required, max 60 chars, unique per school
- `note`: optional, max 200 chars
- `monthly`: optional, numeric, creates 12 types if enabled

## Dependencies & Interconnections
### Depends On
- **QuickBooks**: Income account definitions (optional)

### Used By
- **Creditmemo**: References credit types when creating credits
- **QuickBooks**: Maps credit types to QBO items with income accounts

## User Flows
### Monthly Generation Flow
1. Admin checks "monthly" checkbox
2. Enters base name (e.g., "Scholarship")
3. System generates 12 credit types: "Scholarship [Jan]" ... "Scholarship [Dec]"
4. All 12 share same note and QB account

### QuickBooks Integration Flow
1. If QB enabled: Query `SELECT Name FROM Account WHERE AccountType='Income'`
2. Admin selects income account
3. System stores `incomeaccountID` and `incomeaccount` name
4. When credit memo created: QB item uses this income account

## Edge Cases & Limitations
### Known Issues
1. **No Bulk Delete**: Must delete credit types one at a time
2. **No Archive**: Hard delete only (no soft delete)
3. **In-Use Check**: No validation if credit type used in credit memos

### Constraints
- Credit type name must be unique per school
- Cannot delete if referenced in credit memos (DB constraint)

## Configuration
### QuickBooks Settings
- `active`: "1" to enable QB account dropdown
- `sessionAccessToken`: Valid OAuth2 token required

## Notes for AI Agents
### Key Patterns
- Monthly generation creates 12 credit types with month suffixes [Jan] through [Dec]
- QB account stores both ID and name for offline display
- Income account used when syncing credit memos to QuickBooks

### Debugging Tips
- Check QB token expiry: `now() < sessionAccessTokenExpiry`
- Verify `incomeaccountID` populated for QB sync
- Check `quickbookslog` for API errors
