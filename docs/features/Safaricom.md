# Feature: Safaricom

## Overview
**Controller**: `mvc/controllers/Safaricom.php`  
**Primary Purpose**: M-Pesa IPN (Instant Payment Notification) callback handler for STK Push payments  
**User Roles**: Public (no authentication required - webhook endpoint)  
**Status**: ✅ Active - Critical payment infrastructure

## Functionality
### Core Features
- Receive M-Pesa STK Push payment callbacks from Safaricom
- Process successful payments automatically
- Create payment records for students
- Update product sales payment status
- Send SMS notifications to parents/students
- QuickBooks payment synchronization
- Transaction verification and logging

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `ipn()` | safaricom/ipn | Handle M-Pesa IPN callbacks | None (public webhook) |

## Data Layer
### Models Used
- mainmpesa_m, mpesa_m, payment_m, globalpayment_m
- student_m, studentrelation_m, parents_m
- invoice_m, creditmemo_m, productsale_m, productsalepaid_m
- schoolyear_m, schoolterm_m, paymenttypes_m
- quickbookssettings_m, quickbookslog_m
- mailandsms_m, payment_gateway_option_m, setting_m

### Database Tables
- `mainmpesa` - Parent M-Pesa transaction record
- `mpesa` - Individual student payment records linked to mainmpesa
- `payment` - Final payment records after successful callback
- `globalpayment` - Payment grouping record
- `productsalepaid` - Product sale payments
- `productsale` - Product sale status updates

## Validation Rules
### IPN Callback Validation
- Accepts POST with JSON body from Safaricom
- Verifies `ResultCode == 0` for successful payment
- Matches `phonenumber` and `amount` to pending mainmpesa record
- Validates `paidstatus == '0'` (unpaid)

### Payment Data Extraction
From `CallbackMetadata.Item`:
- `Amount`: Payment amount (integer)
- `MpesaReceiptNumber`: Transaction ID
- `TransactionDate`: Payment timestamp
- `PhoneNumber`: Customer phone number

## Dependencies & Interconnections
### Depends On (Upstream)
- **Safaricom API**: Sends IPN callbacks
- **mainmpesa**: Pre-created payment request record
- **mpesa**: Student allocation records
- **payment_gateway_option**: M-Pesa configuration

### Used By (Downstream)
- **Payment**: Creates final payment records
- **Student_statement**: Displays payments
- **Mpesa**: Dashboard shows allocated/unallocated payments
- **Quickbooks**: Syncs payments to QBO

### Related Features
- Make_payment (initiates STK Push), Mpesa (dashboard), Payment, Quickbooks

## User Flows
### STK Push Payment Flow
1. Parent initiates payment via Make_payment
2. System creates `mainmpesa` record (paidstatus=0)
3. System creates `mpesa` records for each student
4. STK Push sent to parent's phone
5. Parent enters M-Pesa PIN
6. **Safaricom sends IPN callback to safaricom/ipn**
7. IPN handler processes callback (this controller)

### IPN Processing Flow (Regular Payment)
1. Receive POST from Safaricom with JSON body
2. Parse `stkCallback` data
3. Extract: Amount, MpesaReceiptNumber, PhoneNumber
4. Query `mainmpesa` where:
   - `paidstatus = '0'`
   - `phonenumber = PhoneNumber`
   - `amount = Amount`
5. If found and `module == null` (regular payment):
   - Query `mpesa` records for mainmpesaID
   - For each mpesa record:
     - Get student info from studentrelation
     - Create `globalpayment` record
     - Create `payment` record with transactionID = MpesaReceiptNumber
     - Optional: QuickBooks sync
   - Update `mainmpesa.paidstatus = '1'`
6. Log to `safaricom_messages.log`

### IPN Processing Flow (Product Sale)
1. Steps 1-4 same as above
2. If found and `module == 'productsale'`:
   - Get `productsalepaid` record by referencenumber (productsaleID)
   - Compare amounts:
     - Equal → `productsalestatus = 3` (fully paid)
     - Partial → `productsalestatus = 2` (partially paid)
     - Overpayment → `productsalestatus = 3` (fully paid)
   - Update `productsale` status
   - Update `mainmpesa.paidstatus = '1'`

## Edge Cases & Limitations
### Known Issues
1. **No Signature Verification**: Callback not cryptographically verified (security risk)
2. **No Retry Logic**: Failed processing not retried automatically
3. **No Idempotency Check**: Duplicate callbacks may create duplicate payments
4. **No Transaction Query**: Does not verify with Safaricom API

### Constraints
- Callback must arrive within timeout window (typically 60 seconds)
- Phone number must exactly match mainmpesa record (format: 254...)
- Amount must exactly match (no tolerance for rounding differences)
- mainmpesa record must exist before callback arrives

### Error Handling
- Invalid JSON → Silently fails (no error response)
- No matching mainmpesa → Silently fails (payment orphaned)
- Database error → Not logged, payment lost
- QuickBooks error → Logged to quickbookslog, payment still created

## Configuration
### Required M-Pesa Settings
Located in `payment_gateway_option` table:
- `mpesa_shortcode`: Paybill or Till number (must match Safaricom config)
- `mpesa_consumer_key`: Safaricom app consumer key
- `mpesa_consumer_secret`: Safaricom app consumer secret
- `mpesa_passkey`: Lipa Na M-Pesa Online passkey

### Callback URL Configuration
In Safaricom portal, set callback URL to:
```
https://yourdomain.com/safaricom/ipn
```

### Server Requirements
- Public HTTPS endpoint (Safaricom requires SSL)
- No authentication/session required
- Must respond quickly (< 10 seconds)

## Notes for AI Agents
### Key Patterns
1. **Webhook Pattern**: No authentication, processes external callbacks
2. **Two-Phase Payment**: mainmpesa created first, IPN completes payment
3. **Module Switching**: Handles both regular payments and product sales
4. **Idempotency**: Should check `paidstatus` to prevent duplicate processing

### Security Considerations
⚠️ **CRITICAL SECURITY ISSUES**:
1. No IP whitelist → Any IP can send fake callbacks
2. No signature verification → Callbacks not authenticated
3. No request validation → Malicious data could corrupt database
4. Direct database writes → No transaction rollback on partial failure

### Recommended Security Improvements
1. Add Safaricom IP whitelist check
2. Implement callback signature verification
3. Add request ID tracking for idempotency
4. Use database transactions
5. Rate limiting on endpoint
6. Detailed error logging

### Common Modifications
- Add callback signature verification
- Implement idempotency checking
- Add transaction status query to Safaricom
- Retry failed payments
- Send email/SMS notifications
- Enhanced logging to database table
- Add webhook event table for audit trail

### Testing
**Test Credentials**: Use LEARNINGS.md Safaricom sandbox credentials

**Testing Callback Locally**:
```bash
curl -X POST https://yourdomain.com/safaricom/ipn \
  -H "Content-Type: application/json" \
  -d '{
    "stkCallback": {
      "ResultCode": 0,
      "CallbackMetadata": {
        "Item": [
          {"Name": "Amount", "Value": 100},
          {"Name": "MpesaReceiptNumber", "Value": "TEST123"},
          {"Name": "PhoneNumber", "Value": "254712345678"}
        ]
      }
    }
  }'
```

### Debugging Tips
1. Check `safaricom_messages.log` for raw callback data
2. Check `safaricom_errors.log` for errors
3. Verify `mainmpesa` record exists with correct phone/amount
4. Check `mainmpesa.paidstatus`:
   - '0' = pending
   - '1' = paid
5. Verify payment created in `payment` table
6. Check `globalpayment` for parent record
7. For product sales: check `productsale.productsalestatus`

### Log File Locations
- `safaricom_messages.log` - All IPN callbacks
- `safaricom_errors.log` - Errors during processing
- `mvc/logs/quickbooks/YYYY-MM-DD/` - QuickBooks API logs
