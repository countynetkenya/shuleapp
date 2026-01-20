# Feature: Creditmemo

## Overview
**Controller**: `mvc/controllers/Creditmemo.php`  
**Primary Purpose**: Student fee credit memo management with QuickBooks synchronization  
**User Roles**: Admin (1), Systemadmin (5), Student (3), Parent (4)  
**Status**: ✅ Active

## Functionality
### Core Features
- Create/edit/delete student credit memos (credits against fees)
- Multiple credit type items per credit memo
- QuickBooks Online synchronization (creates QBO credit memos)
- Email credit memo PDFs to parents/students
- School term-based credit memo tracking
- Soft delete with `deleted_at` flag
- Credit memo activation/deactivation status
- Balance calculation (invoices - payments - credits)

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | creditmemo | List credit memos (filtered by usertype) | creditmemo |
| `add()` | creditmemo/add | Create new credit memo | creditmemo_add |
| `edit(id)` | creditmemo/edit/[id] | Edit existing credit memo | creditmemo_edit |
| `delete(id)` | creditmemo/delete/[id] | Soft delete credit memo | creditmemo_delete |
| `view(id)` | creditmemo/view/[id] | View credit memo details | creditmemo |
| `print_preview(id)` | creditmemo/print_preview/[id] | Print-friendly credit memo | creditmemo |
| `send_pdf_to_mail()` | creditmemo/send_pdf_to_mail | Email credit memo PDF | creditmemo |
| `getCreditMemoItem()` | creditmemo/getCreditMemoItem | AJAX: Get credit memo items | creditmemo |
| `getStudent()` | creditmemo/getStudent | AJAX: Get students by class | creditmemo_add |
| `getSection()` | creditmemo/getSection | AJAX: Get sections by class | creditmemo_add |

## Data Layer
### Models Used
- creditmemo_m, credittypes_m, payment_m
- classes_m, student_m, parents_m, section_m, user_m
- weaverandfine_m, payment_settings_m, globalpayment_m, maincreditmemo_m
- studentrelation_m, studentgroup_m, schoolterm_m
- quickbookssettings_m, quickbookslog_m

### Database Tables
- `creditmemo` - Individual credit memos with `quickbooks_status`, `deleted_at`
- `maincreditmemo` - Parent credit memo grouping multiple credits
- `credittypes` - Available credit type definitions with QB income account mapping
- `quickbookslog` - QuickBooks API log
- `studentrelation` - Student-class-year relationships

## Validation Rules
### Add/Edit Credit Memo
- `classesID`: required, numeric, 11 chars max, validates class exists
- `studentID`: required, numeric, 11 chars max, validates student exists
- `creditmemo_active`: optional, validates active status
- `schooltermID`: required, numeric, validates term exists
- `credittypeitems`: required, JSON array of credit type items
- `date`: required, dd-mm-yyyy format, 10 chars max

### Credit Type Items
- Must have valid `credittypeID`, `credittype`, `amount`
- Amount must be numeric
- Amount must be > 0

## Dependencies & Interconnections
### Depends On (Upstream)
- **Classes**: Student class selection
- **Student/Parents**: Credit memo recipients
- **Credittypes**: Credit type definitions with QB income accounts
- **Schoolterm**: Term association
- **QuickBooks**: External accounting sync

### Used By (Downstream)
- **Student_statement**: Displays credit memos to reduce balance
- **Quickbooks**: Syncs credit memos to QuickBooks Online
- **Balance Calculations**: Credits reduce student balances (Invoice - Payment - Creditmemo)

### Related Features
- Invoice, Payment, Student_statement, Quickbooks, Credittypes

## User Flows
### Primary Flow: Create Credit Memo
1. Admin selects class → sections load via AJAX
2. Select student → checks for existing invoices
3. Select credit type(s) and amounts
4. Assign to school term
5. Set date and optional notes
6. Submit → Creates:
   - maincreditmemo record
   - creditmemo record(s) for each credit type
   - QuickBooks sync (if enabled and authenticated)

### QuickBooks Sync Flow
1. Admin creates credit memo
2. If QuickBooks enabled:
   - Create/query customer (studentID-name format)
   - Create/query credit memo item (credit type as QBO Service item)
   - Create QBO credit memo with DocNumber = creditmemoID
   - Update local credit memo: `quickbooks_status = 1`
   - Log all API calls to `quickbookslog`

## Edge Cases & Limitations
### Known Issues
1. **Soft Delete Only**: Credit memos with `deleted_at = 0` not shown
2. **No Edit After Reference**: Cannot edit credit memos once referenced
3. **QuickBooks Sync Once**: No update mechanism, only create
4. **Date Validation**: Strict dd-mm-yyyy format required

### Constraints
- Credit memo cannot exceed total invoice amount for student
- School term must be active
- Student must be enrolled in selected school year
- QuickBooks token must be valid and not expired for sync
- Credit type must have `incomeaccountID` set for QB sync

## Configuration
### Required Settings
- **School Year**: Must have active school year
- **School Term**: At least one term defined
- **Credit Types**: Pre-defined credit types required

### Optional QuickBooks Settings
Located in `quickbookssettings` table:
- `client_id`: QuickBooks app client ID
- `client_secret`: QuickBooks app secret
- `active`: "1" to enable sync
- `sessionAccessToken`: OAuth2 token (serialized)
- `sessionAccessTokenExpiry`: Token expiry timestamp

## Notes for AI Agents
### Key Patterns
1. **Credit Memo Structure**: Similar to Invoice but creates credits instead of debits
2. **Balance Impact**: Credits reduce student balance: `Balance = Invoice - Payment - Creditmemo`
3. **QuickBooks Item Sync**: Each credittype syncs to QBO as Service item with Income account
4. **Multi-Credit Support**: One credit memo can contain multiple credit types

### Common Modifications
- Add credit memo approval workflow
- Implement credit memo number generation
- Add credit memo templates
- Bulk credit memo creation
- Credit memo expiration dates
- Credit application tracking (which invoices credits applied to)

### Debugging Tips
- Check `quickbookslog` table for QB API errors
- Verify `deleted_at = 1` for active credit memos
- Check `maincreditmemo` for parent record
- Verify student enrollment in school year
- Check credittype `incomeaccountID` for QB sync
