# Feature: Fees_balance_tier

## Overview
**Controller**: `mvc/controllers/Fees_balance_tier.php`  
**Primary Purpose**: Categorize students into fee balance tiers based on payment progress over time (15/30/45 days)  
**User Roles**: Admin (1), Systemadmin (5)  
**Status**: ✅ Active

## Functionality
### Core Features
- Configure tier thresholds (percentage paid vs days elapsed)
- Three tier periods: 15 days, 30 days, 45 days
- Generate tier reports filtering by class, student group, or individual student
- Calculate balance percentage: `(PaymentMade / TotalPayable) * 100`
- Assign students to tiers based on payment progress

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | fees_balance_tier | Configure tier values | fees_balance_tier |
| `report()` | fees_balance_tier/report | Generate tier report | fees_balance_tier_report |
| `getFeesBalanceTierReport()` | fees_balance_tier/getFeesBalanceTierReport (AJAX) | Calculate tier assignments | fees_balance_tier_report |

## Data Layer
### Models Used
- fees_balance_tier_m, classes_m, studentgroup_m, studentrelation_m
- schoolterm_m, invoice_m, creditmemo_m, payment_m

### Database Tables
- `fees_balance_tier` - Tier definitions (system-wide, not editable)
  - fees_balance_tier_id (PK), name (e.g., "Good Standing")
  - days (15, 30, 45)
- `fees_balance_tier_values` - School-specific tier thresholds
  - fees_balance_tier_value_id (PK)
  - fees_balance_tier_id (FK)
  - tier_value (percentage threshold), schoolID

## Validation Rules
### Configure Tiers (index)
- Each tier ID field: optional, max 3 chars, numeric
- Error message: "Please enter a valid percentage value (0-100)"

### Report Filters
- `classesID`: optional, xss_clean
- `studentgroupID`: optional, xss_clean
- `studentID`: optional, xss_clean
- `feesBalanceTier`: optional, xss_clean

## Dependencies & Interconnections
### Depends On (Upstream)
- **Classes**: Student filtering
- **Studentgroup**: Group filtering
- **Student/Studentrelation**: Student data
- **Schoolterm**: Current term date range
- **Invoice**: Invoice amounts
- **Creditmemo**: Credit amounts
- **Payment**: Payment amounts

### Used By (Downstream)
- Reports module (tier-based fee follow-up)

### Related Features
- Invoice, Payment, Student_statement, Schoolterm

## User Flows
### Primary Flow: Configure Tiers
1. Admin navigates to fees_balance_tier/index
2. View tier definitions grouped by days (15/30/45)
3. For each tier, enter percentage threshold (e.g., 50% for "At Risk")
4. Submit updates all tier values for school via batch update
5. Redirects with success message

### Tier Report Generation Flow
1. Admin navigates to fees_balance_tier/report
2. Select optional filters (class, group, student, specific tier)
3. Submit triggers AJAX request to getFeesBalanceTierReport()
4. Backend calculates for each student:
   - Brought forward balance (invoices before term)
   - Current term invoices and credits
   - Payments in term and before term
   - Total payable = BF balance + term invoices - credits
   - Payment percentage = (Payments / Payable) * 100
   - Days elapsed since term start
5. Assign tier based on days + percentage threshold
6. Filter students by selected tier (if specified)
7. Return HTML report via AJAX

## Edge Cases & Limitations
- **Tier Definitions Fixed**: Cannot add/remove tiers (15/30/45 days hardcoded)
- **Current Term Only**: Report only calculates for current school term
- **No Historical Tiers**: Doesn't track tier changes over time
- **Positive Balances Only**: Only assigns tier if `Payable > 0`
- **Percentage Match**: Uses `>=` for tier assignment (tier_value is minimum percentage)
- **Days Logic**: Uses `>=` for day thresholds (15, 30, 45 days)

## Configuration
- No environment variables
- Tier definitions stored in `fees_balance_tier` table (system-wide)
- Thresholds stored per school in `fees_balance_tier_values`

## Notes for AI Agents
### Balance Calculation Logic
```php
// Brought Forward (before term)
$BalanceBf = ($AmountBf - $CreditBf) - $PaymentBf;

// Current Term
$Payable = $BalanceBf + $Amount - $Credit;
$Paid = $Payment + $PaymentBf;

// Percentage
$balancePercentage = ($Paid / $Payable) * 100;

// Days elapsed
$dateFrom = new DateTime($schoolterm->startingdate);
$dateTo = new DateTime(); // today
$diff = $dateFrom->diff($dateTo)->days;
```

### Tier Assignment Logic
```php
if ($diff >= 15) {
    $tier = get_tier_where("tier_value >=" . $balancePercentage, "days" => 15);
} elseif ($diff >= 30) {
    $tier = get_tier_where("tier_value >=" . $balancePercentage, "days" => 30);
} elseif ($diff >= 45) {
    $tier = get_tier_where("tier_value >=" . $balancePercentage, "days" => 45);
}
```
Note: Logic appears flawed (should use if-elseif-else, not sequential >=)

### Performance Considerations
- Aggregates all invoices, credits, payments per student
- Loops through all students in filter
- Complex calculations in controller (not optimized query)
- Consider caching tier assignments

### Typical Tier Structure
- **15 days**: Good Standing (80%+), At Risk (50-79%), Critical (<50%)
- **30 days**: Good Standing (60%+), At Risk (30-59%), Critical (<30%)
- **45 days**: Good Standing (40%+), At Risk (20-39%), Critical (<20%)

### Data Aggregation Helpers
- `totalAmountAndDiscount()`: Sums invoice amounts and discounts
- `totalPaymentAndWeaver()`: Sums payments
- Methods handle grouping by studentID

### Report Output
- Returns HTML table rendered from `fees_balance_tier/report` view
- AJAX response: `{"status": true, "render": "<table>...</table>"}`
- No PDF/Excel export built-in

