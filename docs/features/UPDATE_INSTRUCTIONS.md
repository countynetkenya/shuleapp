# Phase 3 Financial Documentation Update Instructions

## Status Summary
- **Completed (3/25)**: Invoice, Payment, Paymenthistory
- **In Progress (22/25)**: Remaining financial features
- **Not Found (1)**: Stripe (integrated via payment gateways, not standalone controller)

## Documentation Approach
Each feature doc includes:
1. Overview (controller path, purpose, user roles, status)
2. Core features list
3. Routes & methods table with permissions
4. Data layer (models, DB tables)
5. Validation rules
6. Dependencies (upstream/downstream/related)
7. User flows
8. Edge cases & limitations
9. Configuration (env vars, settings)
10. Notes for AI agents (implementation specifics, patterns, security)

## Feature Groupings for Efficient Documentation

### Group 1: Core Fee Management (5 features)
- Make_payment - Staff salary payments
- Feetypes - Fee type definitions
- Bundlefeetypes - Grouped fee bundles
- Fees_balance_tier - Balance-based fee tiers
- Credittypes - Credit/refund type definitions

### Group 2: Financial Transactions (4 features)
- Expense - School expense tracking
- Income - School income tracking
- Purchase - Asset purchase management
- Creditmemo - Credit memos and refunds

### Group 3: Payment Integrations (5 features)
- Mpesa - M-Pesa payment dashboard
- Safaricom - M-Pesa Daraja API callbacks
- Paymentsettings - Payment gateway configuration
- Paymenttypes - Payment method definitions
- Make_payment - Online payment processing

### Group 4: QuickBooks Integration (2 features)
- Quickbooks - QuickBooks sync interface
- Quickbookssettings - QuickBooks OAuth configuration

### Group 5: Reporting & Statements (2 features)
- Student_statement - Student financial statements
- Accountledgerreport - General ledger reports

### Group 6: Inventory/Products (4 features)
- Vendor - Vendor/supplier management
- Inventoryinvoice - Inventory-based invoicing
- Productpurchase - Product purchase management
- Productsale - Product sales tracking

## Next Steps
1. Document groups systematically (1-2 groups per session)
2. Test documentation accuracy against actual controller code
3. Cross-reference related features
4. Add security warnings where applicable (especially for public endpoints)
5. Commit progress after each group

## Key Patterns to Document
- QuickBooks OAuth flow and token refresh
- M-Pesa callback handling and idempotency
- Soft delete pattern (deleted_at flag)
- School year locking
- Balance calculation formulas
- Payment gateway integration patterns
- User role-based filtering
