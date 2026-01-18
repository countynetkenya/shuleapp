# Feature: Invoice

## Overview
**Controller**: `mvc/controllers/Invoice.php`  
**Primary Purpose**: Student fee invoice management with QuickBooks and payment gateway integration  
**User Roles**: Admin (1), Systemadmin (5), Student (3), Parent (4)  
**Status**: ✅ Active

## Functionality
### Core Features
- Create/edit/delete student invoices with fee types
- Bundle fee types support (multiple fees in one invoice)
- Discount management per invoice
- QuickBooks Online synchronization (creates QBO invoices)
- Multiple payment gateway integration (Stripe, PayPal, Voguepay, PayUmoney, Mpesa)
- Email invoice PDFs to parents/students
- School term-based invoice tracking
- Soft delete with `deleted_at` flag
- Invoice activation/deactivation status

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | invoice | List invoices (filtered by usertype) | invoice |
| `add()` | invoice/add | Create new invoice | invoice_add |
| `edit(id)` | invoice/edit/[id] | Edit existing invoice | invoice_edit |
| `delete(id)` | invoice/delete/[id] | Soft delete invoice | invoice_delete |
| `view(id)` | invoice/view/[id] | View invoice details | invoice |
| `print_preview(id)` | invoice/print_preview/[id] | Print-friendly invoice | invoice |
| `send_pdf_to_mail()` | invoice/send_pdf_to_mail | Email invoice PDF | invoice |
| `getInvoiceItem()` | invoice/getInvoiceItem | AJAX: Get invoice items | invoice |
| `getStudent()` | invoice/getStudent | AJAX: Get students by class | invoice_add |
| `getMainInvoice()` | invoice/getMainInvoice | AJAX: Get main invoice data | invoice |
| `getSection()` | invoice/getSection | AJAX: Get sections by class | invoice_add |
| `getBundleFeetypePrice()` | invoice/getBundleFeetypePrice | AJAX: Get bundle fee prices | invoice_add |

## Data Layer
### Models Used
- invoice_m, feetypes_m, bundlefeetypes_m, bundlefeetype_feetypes_m, invoice_feetypes_m
- payment_m, classes_m, student_m, parents_m, section_m, user_m
- weaverandfine_m, payment_settings_m, globalpayment_m, maininvoice_m
- studentrelation_m, studentgroup_m, schoolterm_m
- payment_gateway_m, payment_gateway_option_m
- quickbookssettings_m, quickbookslog_m

### Database Tables
- `invoice` - Individual fee invoices
- `maininvoice` - Parent invoice grouping multiple invoices
- `invoice_feetypes` - Fee items on invoice
- `feetypes` - Available fee types
- `bundlefeetypes` - Bundles of multiple fees
- `bundlefeetype_feetypes` - Fee types in bundles
- `payment` - Payments against invoices
- `globalpayment` - Global payment tracking
- `quickbookslog` - QuickBooks API log

## Validation Rules
### Add/Edit Invoice
- `classesID`: required, numeric, 11 chars max, validates class exists
- `studentID`: required, numeric, 11 chars max, validates student exists
- `invoice_active`: optional, validates active status
- `schooltermID`: required, numeric, validates term exists
- `feetypeitems`: required, JSON array of fee items
- `date`: required, dd-mm-yyyy format, 10 chars max

### Fee Type Items
- Must have valid `feetypeID`, `fee`, `amount`, `discount`
- Amount/discount must be numeric
- Discount cannot exceed 100%

### Send Email
- `to`: required, valid email
- `subject`: required
- `message`: optional

## Dependencies & Interconnections
### Depends On (Upstream)
- **Classes**: Student class selection
- **Student/Parents**: Invoice recipients
- **Feetypes**: Fee definitions
- **Bundlefeetypes**: Fee bundles
- **Schoolterm**: Term association
- **QuickBooks**: External accounting sync
- **Payment Gateways**: Online payment processing

### Used By (Downstream)
- **Payment**: Payments reference invoiceID
- **Student_statement**: Displays invoices on statements
- **Quickbooks**: Syncs invoices to QuickBooks Online
- **Make_payment**: Online payment checkout

### Related Features
- Payment, Creditmemo, Paymenthistory, Student_statement
- Quickbooks, Quickbookssettings
- Feetypes, Bundlefeetypes, Fees_balance_tier

## User Flows
### Primary Flow: Create Invoice
1. Admin selects class → sections load via AJAX
2. Select student → checks for existing invoices
3. Choose fee types or bundle
4. Set amounts and optional discount per fee
5. Submit → creates:
   - `maininvoice` record
   - Individual `invoice` records for each fee
   - `invoice_feetypes` line items
   - `globalpayment` tracking record
6. Optional: Sync to QuickBooks (if enabled)
7. Email invoice PDF to parent/student

### Payment Gateway Flow
1. Parent/student views invoice
2. Clicks "Pay Online"
3. Redirects to payment gateway (Stripe/PayPal/etc.)
4. Gateway callback creates payment record
5. Invoice status updates

### QuickBooks Sync Flow
1. Check QuickBooks settings (active, token valid)
2. Find/create customer in QBO (student)
3. Find/create fee items in QBO
4. Create QBO invoice with line items
5. Log transaction to `quickbookslog`
6. Handle token refresh if expired

## Edge Cases & Limitations
- **Soft Delete**: Invoices marked `deleted_at=0` not truly deleted
- **Bundle Expansion**: Bundle fees create multiple invoice records
- **QuickBooks Token**: Auto-refreshes if expired, may fail if refresh token invalid
- **Payment Gateway**: Cannot edit/delete invoices paid via gateway (Paypal/Stripe/etc.)
- **School Year Lock**: Can only edit invoices in active school year (unless superadmin)
- **Duplicate Prevention**: Validates student doesn't have duplicate invoice for same fee in term
- **Discount Limits**: Discount % validation prevents > 100%

## Configuration
### Environment Variables (via getenv)
- None directly, uses database settings

### Payment Gateway Settings
- Configured via `paymentsettings` and `payment_gateway_option` tables
- Each gateway has status flag and specific API credentials
- Gateways: stripe, paypal, voguepay, payumoney, mpesa

### QuickBooks Settings
- `client_id`: OAuth2 client ID
- `client_secret`: OAuth2 client secret  
- `stage`: development or production
- `active`: 0/1 enable/disable
- `sessionAccessToken`: Serialized OAuth token
- `sessionAccessTokenExpiry`: Unix timestamp
- Stored in `quickbookssettings` table

## Notes for AI Agents
### Payment Gateway Integration
- Uses PaymentGateway library (`libraries/PaymentGateway/PaymentGateway.php`)
- Gateway-specific logic in `libraries/PaymentGateway/Service/`
- Each gateway has validation rules in `language/[lang]/[gateway]_rules_lang.php`
- Callback URLs must be registered with gateway providers

### QuickBooks Integration
- Requires QuickBooks PHP SDK (`vendor/quickbooks/`)
- Uses OAuth 2.0 three-legged auth flow
- Token refresh logic in inherited `quickbooksConfig()` and `refreshToken()` methods
- Customer DisplayName: `{studentID}-{name}`
- Item creation: Maps fee types to QBO Items
- Logs all API calls to `quickbookslog` table

### Invoice Calculation Logic
```php
$grandTotal = $amount - (($amount / 100) * $discount)
$balance = $grandTotal - $totalPaid - $weaver + $fine
```
- Discount is percentage-based
- Weaver = scholarship/waiver (reduces balance)
- Fine = late payment penalty (increases balance)

### Soft Delete Pattern
- `deleted_at=1` means active
- `deleted_at=0` means deleted
- Queries must filter `deleted_at=1` to show active invoices

### Performance Considerations
- Heavy model loading in `__construct()` - consider lazy loading
- QuickBooks API calls can be slow - implement timeout handling
- Payment gateway callbacks may retry - implement idempotency checks

