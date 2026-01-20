# Feature: Paymenttypes

## Overview
**Controller**: `mvc/controllers/Paymenttypes.php`  
**Primary Purpose**: Define payment method types with QuickBooks bank account mapping  
**User Roles**: Admin (1), Systemadmin (5)  
**Status**: ✅ Active

## Functionality
### Core Features
- Create/edit/delete payment method types (Cash, Check, Bank Transfer, M-Pesa, etc.)
- QuickBooks bank account mapping (DepositToAccountRef)
- Payment type uniqueness validation per school
- School-specific payment type isolation

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | paymenttypes | List all payment types | paymenttypes |
| `add()` | paymenttypes/add | Create new payment type | paymenttypes_add |
| `edit(id)` | paymenttypes/edit/[id] | Edit existing payment type | paymenttypes_edit |
| `delete(id)` | paymenttypes/delete/[id] | Delete payment type | paymenttypes_delete |
| `unique_paymenttypes()` | N/A | Validation callback for uniqueness | N/A |
| `getDepositToAccountRefObj()` | N/A | Internal: Fetch QBO bank accounts | N/A |

## Data Layer
### Models Used
- paymenttypes_m, quickbookssettings_m, quickbookslog_m

### Database Tables
- `paymenttypes` - Payment method definitions
  - paymenttypesID (PK), paymenttypes (name: Cash, Check, etc.)
  - note, deposittoaccountrefID (QuickBooks bank account ID)
  - deposittoaccountref (QuickBooks bank account name), schoolID

## Validation Rules
### Add/Edit Payment Type
- `paymenttypes`: required, max 60 chars, xss_clean, unique per school
- `note`: optional, max 200 chars, xss_clean

## Dependencies & Interconnections
### Depends On (Upstream)
- **QuickBooks**: Bank account synchronization (optional)

### Used By (Downstream)
- **Payment**: Payments reference paymenttypeID
- **Make_payment**: Payment method selection
- **Quickbooks**: Payment sync uses deposittoaccountref

### Related Features
- Payment, Make_payment, Quickbooks, Quickbookssettings

## User Flows
### Primary Flow: Create Payment Type
1. Admin navigates to paymenttypes/add
2. If QuickBooks enabled, fetches bank accounts from QBO
3. Enter payment type name (e.g., "Cash", "M-Pesa") and optional note
4. Optionally select QuickBooks bank account for deposits
5. Submit creates payment type record
6. Redirects to index with success message

### QuickBooks Integration Flow
1. Check QuickBooks settings active status
2. Configure DataService with OAuth credentials
3. Query QBO: `select * from Account where AccountType='Bank'`
4. Display bank accounts in dropdown
5. On save, store `deposittoaccountrefID` and `deposittoaccountref` name

## Edge Cases & Limitations
- **QuickBooks Optional**: Payment types work without QuickBooks; fields nullable
- **School Isolation**: Payment types scoped to schoolID
- **No Usage Check**: Deleting doesn't verify if used in payments (orphaned references)
- **QuickBooks Token**: If expired, bank accounts not displayed
- **Account Deletion**: If QBO account deleted, local reference remains (uses stored name)

## Configuration
### QuickBooks Settings (from quickbookssettings table)
- `client_id`, `client_secret`: OAuth2 credentials
- `stage`: development or production
- `active`: 0/1 enable/disable integration
- `sessionAccessToken`, `sessionAccessTokenExpiry`

## Notes for AI Agents
### QuickBooks Integration Pattern
- Uses QuickBooks PHP SDK (`vendor/quickbooks/`)
- Bank account query constant: `PAYMENT_ACCOUNT_TYPE = "Bank"`
- Logs all API calls to `quickbookslog`
- Stores logs in `mvc/logs/quickbooks/{date}/`

### DepositToAccountRef Storage
- Stores both ID and name: format `"ID,Name"` comma-separated
- Parsing: `explode(",", $deposittoaccountref)` => `[0]=ID, [1]=Name`
- Used when creating QBO Payment records to specify deposit account

### Common Payment Types
- Cash, Check, Bank Transfer, M-Pesa (mobile money), Credit/Debit Card, Online Gateway

### Performance Considerations
- QuickBooks DataService configured on every add/edit request
- Bank account query not cached
- Consider lazy loading QuickBooks integration

### Error Handling
- QuickBooks errors logged but not shown to user
- If query fails, form still works (dropdown empty)
- Exception handling with try/catch
- Logs errors to `quickbookslog` with status=ERROR

