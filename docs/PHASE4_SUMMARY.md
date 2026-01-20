# Phase 4: HR & Attendance Features - Documentation Summary

## Overview
This document provides a comprehensive overview of all Phase 4 features documented for the Shuleapp school management system.

**Total Features Documented**: 14  
**Documentation Date**: January 2025  
**Status**: ✅ Complete

## Feature Categories

### 1. Attendance Management (4 features)

#### Sattendance (Student Attendance)
- **Purpose**: Daily attendance tracking for students with automated parent notifications
- **Key Features**: 
  - Class-based or subject-based attendance modes
  - Email/SMS notifications for absences
  - Leave integration
  - Monthly calendar view
- **Database**: `sattendance`, `subjectattendance`
- **Complexity**: High (notifications, multiple modes, complex validations)

#### Tattendance (Teacher Attendance)
- **Purpose**: Daily attendance tracking for teachers
- **Key Features**:
  - Monthly attendance grid (31 days)
  - Leave integration
  - PDF reports
- **Database**: `tattendance`
- **Complexity**: Medium (simpler than student attendance)

#### Eattendance (Exam Attendance)
- **Purpose**: Per-exam attendance tracking by subject
- **Key Features**:
  - One-time marking per exam/subject
  - Section-based filtering
  - AJAX-driven interface
- **Database**: `eattendance`
- **Complexity**: Medium (event-based, not daily)

#### Uattendance (User Attendance)
- **Purpose**: Attendance for custom user roles (non-students/teachers)
- **Key Features**:
  - Excludes predefined user types
  - Monthly tracking
  - Leave integration
- **Database**: `uattendance`
- **Complexity**: Medium (similar to teacher attendance)

### 2. Leave Management (4 features)

#### Leaveapplication (Leave Approval)
- **Purpose**: Manages incoming leave applications for approval/rejection
- **Key Features**:
  - View applications sent to logged-in user
  - Approve/reject toggle
  - Available leave balance calculation
  - PDF generation and email
- **Database**: `leaveapplication`
- **Complexity**: High (balance calculations, workflow)

#### Leaveapply (Leave Submission)
- **Purpose**: Allows users to submit leave applications
- **Key Features**:
  - Date range selection with daterangepicker
  - Attachment upload
  - Approver selection by role
  - Available balance display
- **Database**: `leaveapplication`
- **Complexity**: High (file uploads, complex validations)

#### Leaveassign (Leave Quota Allocation)
- **Purpose**: Assigns leave quotas per category and user type
- **Key Features**:
  - Category + usertype + year assignments
  - Unique constraint validation
  - Simple CRUD operations
- **Database**: `leaveassign`
- **Complexity**: Low (simple configuration)

#### Leavecategory (Leave Type Configuration)
- **Purpose**: Defines leave category types (sick, casual, etc.)
- **Key Features**:
  - Simple name-based categories
  - School-scoped unique names
  - Basic CRUD
- **Database**: `leavecategory`
- **Complexity**: Low (minimal logic)

### 3. Salary Management (4 features)

#### Salary_template (Monthly Salary Templates)
- **Purpose**: Defines salary structures with allowances and deductions
- **Key Features**:
  - Basic salary + overtime rate
  - Multiple allowances (dynamic)
  - Multiple deductions (dynamic)
  - Automatic gross/net calculation
- **Database**: `salary_template`, `salaryoption`
- **Complexity**: High (dynamic options, AJAX, calculations)

#### Hourly_template (Hourly Rate Templates)
- **Purpose**: Simple hourly rate definitions for part-time staff
- **Key Features**:
  - Grade name + hourly rate
  - No allowances/deductions
- **Database**: `hourly_template`
- **Complexity**: Low (just 2 fields)

#### Manage_salary (Salary Assignment)
- **Purpose**: Assigns salary templates to individual users
- **Key Features**:
  - Choose monthly or hourly pay type
  - Template selection
  - Salary slip generation (PDF/email)
  - Role-based user filtering
- **Database**: `manage_salary`
- **Complexity**: High (template integration, calculations, reporting)

#### Overtime (Overtime Tracking)
- **Purpose**: Records overtime hours with automatic pay calculation
- **Key Features**:
  - Date + hours entry
  - Auto-calculation using template overtime_rate
  - Filters to monthly-salary users only
- **Database**: `overtime`
- **Complexity**: Medium (calculation logic, user filtering)

### 4. Membership Management (2 features)

#### Hmember (Hostel Membership)
- **Purpose**: Assigns students to hostels with room categories
- **Key Features**:
  - Hostel + room category selection
  - Fee (hbalance) tracking
  - Gender validation
  - Updates student.hostel flag
- **Database**: `hmember`
- **Complexity**: Medium (gender validation, fee copying)

#### Tmember (Transport Membership)
- **Purpose**: Assigns students to transport routes
- **Key Features**:
  - Route selection with fare
  - Fee (tbalance) tracking
  - Updates student.transport flag
  - PDF/email support
- **Database**: `tmember`
- **Complexity**: Medium (fare copying, data denormalization)

## Key Interconnections

### Attendance ↔ Leave
- **Sattendance**, **Tattendance**, **Uattendance** → **Leaveapplication**
- Approved leaves (status=1) are displayed as 'L' in attendance grids
- Leave dates calculated excluding holidays/weekends (same logic as attendance)

### Leave System Internal
- **Leavecategory** → **Leaveassign** → **Leaveapply** → **Leaveapplication**
- Categories defined → Quotas assigned → Applications submitted → Approvals processed

### Salary System
- **Salary_template** → **Manage_salary** → **Overtime**
- Templates defined → Assigned to users → Overtime calculated using template rates
- **Hourly_template** → **Manage_salary** (alternative salary model)

### Student Memberships
- **Student** ↔ **Hmember**: Updates student.hostel flag (0/1)
- **Student** ↔ **Tmember**: Updates student.transport flag (0/1)
- Both integrate with **Invoice** for fee billing

## Common Patterns

### Validation Patterns
1. **Date Validation**: dd-mm-yyyy format, not future, not holiday, not weekend, within school year
2. **Unique Callbacks**: Prevent '0' selections, check uniqueness with exclusion on edit
3. **School Scoping**: All queries filtered by `schoolID` session variable
4. **Year Lock**: Edit restrictions for non-current year (except superadmin)

### Permission Patterns
1. **Feature Permission**: `{feature}` for add/edit/delete
2. **View Permission**: `{feature}_view` for viewing/reporting
3. **Self-Access**: Users can view own records even without view permission

### Data Patterns
1. **Monthly Storage**: a1-a31 columns for day-by-day tracking
2. **Denormalization**: Copying values (fees, names) instead of FK references
3. **Status Fields**: NULL (pending), 0 (rejected), 1 (approved)
4. **Audit Trails**: create_date, modify_date, create_userID, create_usertypeID

## Database Schema Summary

### Attendance Tables
- `sattendance`: Student attendance (class mode)
- `subjectattendance`: Student attendance (subject mode)
- `tattendance`: Teacher attendance
- `eattendance`: Exam attendance
- `uattendance`: User attendance

### Leave Tables
- `leavecategory`: Leave types
- `leaveassign`: Leave quotas
- `leaveapplication`: Leave applications

### Salary Tables
- `salary_template`: Monthly salary structures
- `salaryoption`: Allowances and deductions
- `hourly_template`: Hourly rates
- `manage_salary`: User salary assignments
- `overtime`: Overtime records

### Membership Tables
- `hmember`: Hostel memberships
- `tmember`: Transport memberships

## Performance Considerations

### High-Impact Features
1. **Sattendance**: Notifications can slow down (30+ tag replacements per student)
2. **Leaveapply**: Real-time balance calculation on every page load
3. **Manage_salary**: Complex gross/net calculations with option iterations

### Optimization Opportunities
1. Cache holiday/weekend data (currently session-based)
2. Implement pagination on user/student lists
3. Lazy-load models in constructors
4. Add indexes on frequently-joined foreign keys

## Security Considerations

### Permission Checks
- All features use `permissionChecker()` for access control
- School ID filtering prevents cross-tenant data access
- User type and user ID validated on ownership checks

### Data Validation
- XSS cleaning on all inputs (`xss_clean`)
- File upload validation (type, size, extension)
- SQL injection prevention via CodeIgniter query builder

### Known Issues
- Some features don't validate foreign key existence before save
- Attachment filenames use SHA-512 (secure)
- Demo mode check for file deletion (prevents data loss in demos)

## Future Enhancement Opportunities

### Attendance
1. Biometric device integration
2. Bulk attendance import (CSV)
3. Real-time attendance dashboard
4. Attendance-based reporting (trends, anomalies)

### Leave
1. Multi-level approval workflow
2. Leave balance carry-forward
3. Leave type restrictions (max consecutive days)
4. Integration with calendar apps

### Salary
1. Salary revision history
2. Payroll processing automation
3. Tax calculation integration
4. Bank transfer file generation

### Membership
1. Hostel room assignment tracking
2. Transport route optimization
3. Fee payment integration
4. Occupancy reporting

## Documentation Quality Metrics

- **Total Pages**: 14 comprehensive documents
- **Total Size**: ~74KB of documentation
- **Lines of Documentation**: 2000+ lines
- **Coverage**: 100% of Phase 4 features
- **Sections per Feature**: 9 standardized sections
- **Code Examples**: Validation rules, callbacks, calculations
- **Interconnections**: Fully mapped dependencies

## Maintenance Notes

### Updating Documentation
When modifying Phase 4 features:
1. Update relevant .md file in `docs/features/`
2. Verify interconnections if changing dependencies
3. Update this summary if adding/removing features
4. Document new edge cases discovered during development

### Adding New Features
Follow the template structure:
1. Overview (purpose, roles, status)
2. Functionality (features, routes)
3. Data Layer (models, tables)
4. Validation Rules
5. Dependencies & Interconnections
6. User Flows
7. Edge Cases & Limitations
8. Configuration
9. Notes for AI Agents

## References

- **Feature Documentation**: `/docs/features/`
- **Controller Code**: `/mvc/controllers/`
- **Model Code**: `/mvc/models/`
- **Database Schema**: Contact DB admin for ERD
- **API Documentation**: Not yet available (generate from routes)

---

**Last Updated**: January 2025  
**Maintained By**: Development Team  
**Status**: ✅ Complete and up-to-date
