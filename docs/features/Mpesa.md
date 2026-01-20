# Feature: Mpesa

## Overview
**Controller**: `mvc/controllers/Mpesa.php`  
**Primary Purpose**: M-Pesa payment dashboard showing allocated and unallocated payments  
**User Roles**: Admin (1), Systemadmin (5)  
**Status**: ✅ Active

## Functionality
### Core Features
- View M-Pesa payments allocated to students
- View M-Pesa payments not yet allocated (orphan payments)
- Payment reconciliation dashboard
- Integration with Safaricom IPN callback payments

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | mpesa | List allocated/unallocated M-Pesa payments | mpesa |

## Data Layer
### Models Used
- payment_m

### Database Tables
- `payment` - All payments with `transactionID` from M-Pesa
- `studentrelation` - Student enrollment (joined for allocated payments)

### Key Queries
- **Allocated**: `srstudentID != null` (payments linked to students)
- **Unallocated**: `srstudentID = null` (orphan payments from IPN)

## Dependencies & Interconnections
### Depends On
- **Safaricom**: IPN callback creates payment records
- **Payment**: Core payment table

### Used By
- **Admin Dashboard**: Reconciliation of M-Pesa payments
- **Payment Allocation**: Manual matching of unallocated payments

### Related Features
- Safaricom (IPN handler), Payment, Student_statement

## User Flows
### View Allocated Payments
1. Admin navigates to mpesa
2. System queries payments with `srstudentID != null`
3. Displays list with student names via studentrelation join
4. Shows: student, amount, date, transaction ID

### View Unallocated Payments
1. Same page, separate tab/section
2. System queries payments with `srstudentID = null`
3. Displays orphan payments from IPN callbacks
4. Admin can manually allocate to students

## Edge Cases & Limitations
### Known Issues
1. **Manual Allocation**: No auto-matching of unallocated payments
2. **No Bulk Operations**: Must allocate payments one at a time
3. **No Search/Filter**: Cannot search by transaction ID or amount

### Constraints
- Relies on Safaricom IPN to create payment records
- Student allocation must match phone number or manual assignment

## Configuration
### M-Pesa Settings
Located in `payment_gateway_option` table:
- `mpesa_shortcode`: Paybill or Till number
- `mpesa_consumer_key`: Safaricom app key
- `mpesa_consumer_secret`: Safaricom app secret
- `mpesa_passkey`: Lipa Na M-Pesa Online passkey

## Notes for AI Agents
### Key Patterns
- **Allocated**: Payments successfully linked to students during IPN processing
- **Unallocated**: Payments from IPN where student match failed (wrong phone, no reference)
- **Transaction ID**: M-Pesa receipt number from Safaricom

### Common Modifications
- Add search/filter by transaction ID, date range, amount
- Implement bulk allocation from CSV
- Auto-matching algorithm (phone number, amount, date)
- Payment reconciliation reports
- Export to Excel/PDF

### Debugging Tips
- Check `safaricom_messages.log` for IPN callback details
- Verify payment created in `payment` table
- Check `mainmpesa` table for parent record
- Verify phone number format matches (254...)
- Check `transactionID` for M-Pesa receipt number
