# Feature: Dashboard

## Overview
**Controller**: `mvc/controllers/Dashboard.php`  
**Primary Purpose**: Main landing page with role-based widgets, counters, analytics, and automatic monthly invoice generation system.  
**User Roles**: All authenticated users (content varies by role)  
**Status**: ✅ Active

## Functionality

### Core Features
- Student/Teacher/Parent/Class/Subject/User counters
- Fee collection totals and analytics
- Library/Hostel/Transport member statistics
- Automated monthly invoice generation (_automation method)
- Recent notices and upcoming events
- Monthly expense tracking
- Today's visitors count
- Role-based widget visibility

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| GET | /dashboard/index | Display dashboard with widgets | Authenticated users |

## Data Layer

### Models Used
Over 20 models loaded: systemadmin_m, dashboard_m, automation_shudulu_m, automation_rec_m, setting_m, notice_m, user_m, student_m, classes_m, teacher_m, parents_m, sattendance_m, subjectattendance_m, subject_m, feetypes_m, invoice_m, creditmemo_m, expense_m, payment_m, lmember_m, book_m, issue_m, hmember_m, tmember_m, event_m, holiday_m, visitorinfo_m, income_m, make_payment_m, maininvoice_m, studentrelation_m, divisions_m

### Database Tables
Queries 20+ tables for widget data

## Dependencies & Interconnections

### Depends On (Upstream)
- **School**: Requires active schoolID
- **Schoolyear**: Requires defaultschoolyearID
- **Setting**: auto_invoice_generate toggle

### Used By (Downstream)  
- **All Features**: Central navigation hub

## User Flows

### Primary Flow: View Dashboard
1. User logs in successfully
2. Redirected to /dashboard/index
3. System runs _automation() if auto_invoice_generate enabled
4. System counts entities (students, teachers, etc.)
5. System calculates fee statistics
6. Displays role-appropriate widgets
7. Shows recent notices and events

### Auto-Invoice Flow (_automation method)
1. Runs on every dashboard load if enabled
2. Checks if day >= automation setting (e.g., 5th of month)
3. Checks if invoices already generated this month
4. For each student with library/transport/hostel balance:
   - Creates invoice record
   - Creates maininvoice record
   - Records automation_rec entry
5. Prevents duplicate generation via automation_shudulu tracking

## Edge Cases & Limitations
- _automation() runs on EVERY dashboard page load (performance concern)
- Heavy query load with 20+ model loads
- No caching of counter data
- Invoice automation tied to dashboard access (won't run if nobody logs in)

## Configuration
- **auto_invoice_generate**: 0 = disabled, 1 = enabled
- **automation**: Day of month to trigger invoice generation (1-31)
- **ex_class**: Classes to exclude from automation

## Notes for AI Agents
- **Performance Critical**: Dashboard loads 20+ models and runs automation logic
- **Auto-Invoice Trigger**: Only runs when user visits dashboard, not on cron
- **Module Numbers**: Library = 5427279, Transport = 872677678, Hostel = 478361 (magic numbers)
- **Automation Logic**: Complex invoice generation in constructor (lines 65-400+)
- **Widget Queries**: Each widget runs separate query; potential for optimization
- **schoolyearID Context**: All queries scoped to active schoolyear
