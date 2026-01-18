# Feature: Schoolyear

## Overview
**Controller**: `mvc/controllers/Schoolyear.php`  
**Primary Purpose**: Manages academic year periods, allowing schools to define multiple year/semester configurations with start and end dates.  
**User Roles**: Admin (requires permissionChecker('schoolyear'))  
**Status**: ✅ Active

## Functionality

### Core Features
- Create and manage academic year periods
- Support for semester/term divisions within years
- Toggle active schoolyear for session context
- Date range validation (start < end)
- Prevent deletion of default schoolyear (ID 1)

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| GET | /schoolyear/index | List all schoolyears for active school | Admin_Controller |
| POST | /schoolyear/schoolyear_list | AJAX endpoint for schoolyear selection | Admin_Controller |
| GET | /schoolyear/add | Display add schoolyear form | Admin_Controller |
| POST | /schoolyear/add | Create new schoolyear | Admin_Controller |
| GET | /schoolyear/edit/{id} | Display edit schoolyear form | Admin_Controller |
| POST | /schoolyear/edit/{id} | Update schoolyear | Admin_Controller |
| GET | /schoolyear/delete/{id} | Delete schoolyear (except ID 1) | Admin_Controller |
| GET | /schoolyear/toggleschoolyear/{id} | Set active schoolyear in session | permissionChecker('schoolyear') |

## Data Layer

### Models Used
- `schoolyear_m`: Schoolyear CRUD operations

### Database Tables
- `schoolyear`: schoolyearID (PK), schoolyear, schoolyeartitle, schooltype, startingdate, endingdate, semestercode, create_date, modify_date, create_userID, create_username, create_usertype, schoolID

## Validation Rules
- **schoolyear**: required, max_length[128], xss_clean, callback_unique_schoolyear
- **schoolyeartitle**: max_length[128], xss_clean, callback_unique_schoolyeartitle
- **startingdate**: required, max_length[10], xss_clean, callback_date_valid
- **endingdate**: required, max_length[10], xss_clean, callback_date_valid, callback_unique_endingdate
- **semestercode**: max_length[11], numeric
- **Date Format**: dd-mm-yyyy
- **Date Logic**: endingdate must be > startingdate

## Dependencies & Interconnections

### Depends On (Upstream)
- **School**: schoolyearID links to schoolID

### Used By (Downstream)  
- **All Features**: defaultschoolyearID in session scopes data to active academic year
- **Dashboard**: Displays current year context
- **Student**: Filters students by year
- **Attendance**: Scoped to schoolyear

### Related Features
- **School**: Auto-creates default schoolyear on school creation
- **Setting**: school_year field references schoolyearID

## User Flows

### Primary Flow: Create Academic Year
1. Admin navigates to /schoolyear/add
2. Enters schoolyear (e.g., "2024-2025"), optional title, start/end dates
3. System validates dates (dd-mm-yyyy format, end > start)
4. Creates record with audit fields (create_userID, create_username, create_usertype)
5. Redirects to /schoolyear/index

### Primary Flow: Toggle Active Year
1. User clicks toggle link in navigation
2. System validates permission
3. Sets session variable: defaultschoolyearID = {id}
4. Redirects to previous page (HTTP_REFERER)
5. All subsequent queries filter by new schoolyearID

## Edge Cases & Limitations
- Cannot delete schoolyearID = 1 (hard-coded protection)
- schoolyear and schoolyeartitle uniqueness scoped to schoolID
- semestercode field exists but not actively used in validation
- schooltype defaults to 'classbase' (not user-configurable)

## Configuration
- Default schooltype: 'classbase'
- Date format: dd-mm-yyyy (validated by checkdate())

## Notes for AI Agents
- **Session Context**: toggleschoolyear sets `defaultschoolyearID` in session; all queries must respect this
- **Date Handling**: Inputs are dd-mm-yyyy, stored as Y-m-d; conversion happens in controller
- **Audit Trail**: create_userID, create_username, create_usertype track record creation
- **Uniqueness**: schoolyear alone OR schoolyear+schoolyeartitle combo must be unique per schoolID
- **Header Assets**: Uses datepicker.js for date inputs
- **Redirection Logic**: POST to schoolyear_list echoes URL instead of redirecting (odd pattern)

