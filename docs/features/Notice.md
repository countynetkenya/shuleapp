# Feature: Notice

## Overview
**Controller**: `mvc/controllers/Notice.php`  
**Primary Purpose**: School-wide bulletin board system for posting announcements, news, and important notices visible to all users.  
**User Roles**: Admin, Teacher (view), Student (view), Parent (view), Superadmin  
**Status**: ✅ Active

## Functionality
### Core Features
- **Notice Management**: Create, edit, view, delete school notices
- **Rich Text Editor**: Support for formatted content
- **Date-based Organization**: Notices tied to specific dates
- **School Year Filtering**: Notices scoped to school years
- **Print Support**: Print-friendly notice preview
- **Email Sharing**: Send notice via email to specific recipients
- **Public Display**: Notices appear on dashboard and notice board

### Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | notice/index | notice | List all notices for current school year |
| `add()` | notice/add | notice_add | Create new notice |
| `edit()` | notice/edit/{id}` | notice_edit | Update existing notice |
| `view()` | notice/view/{id}` | notice_view | View notice details |
| `delete()` | notice/delete/{id}` | notice_delete | Delete notice |
| `print_preview()` | notice/print_preview/{id}` | notice_view | Printable notice view |
| `send_mail()` | notice/send_mail | notice_view | Email notice to recipient |

## Data Layer
### Models Used
- `notice_m` - Notice CRUD operations
- `alert_m` - User alert system integration

### Database Tables
- `notice` - Notice storage (title, date, notice, schoolyearID, schoolID, create_date, create_userID, create_usertypeID)
- `alert` - Alert system for new notice notifications

## Validation Rules
### Notice Rules (`rules()`)
- **title**: Required, max 128 chars
- **date**: Required, max 10 chars, valid date format (callback: `date_valid()`)
- **notice**: Required, rich text content

### Email Send Rules (`send_mail_rules()`)
- **to**: Required, max 60 chars, valid email address
- **subject**: Required
- **message**: Optional, additional message
- **noticeID**: Required, max 10 chars, must exist (callback: `unique_data()`)

## Dependencies & Interconnections
### Depends On (Upstream)
- `Alert` - For notifying users about new notices
- `Schoolyear` - For year-based filtering

### Used By (Downstream)
- **Dashboard** - Displays recent notices
- **Alert** - Triggers alerts when new notice posted
- **Frontend** (if configured) - May show public notices

### Related Features
- **Alert** - Notification system
- **Event** - Related to calendar events
- **Dashboard** - Notice display

## User Flows
### Create Notice
1. Navigate to `notice/add`
2. Enter notice title
3. Select date for the notice
4. Write notice content using rich text editor
5. Submit - notice saved and alerts sent to users
6. Redirects to notice list

### Email Notice
1. View notice at `notice/view/{id}`
2. Click "Send Email" button
3. Enter recipient email address
4. Optionally add custom message
5. Submit - email sent with notice content

### View and Print Notice
1. Navigate to notice list
2. Click on notice to view details
3. Click "Print" for print-friendly version
4. Browser print dialog opens

## Edge Cases & Limitations
- **Date Validation**: Date must be valid format (callback validates)
- **School Year Restriction**: Can only edit notices for current school year (unless superadmin)
- **Alert Integration**: New notices trigger alerts - ensure alert system is working
- **Email Dependency**: Sending notice via email requires configured email settings
- **Rich Text**: Notice content supports HTML - ensure proper sanitization
- **Deletion**: No soft delete - deletion is permanent
- **Past Notices**: Old school year notices are view-only

## Configuration
### Rich Text Editor
- Uses `jquery-te-1.4.0` for WYSIWYG editing
- Supports formatting, lists, links

### Datepicker
- Uses `assets/datepicker/datepicker.js`
- Date format must match system locale

### School Year Behavior
- Check in `add()` and `edit()`: `$this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')`
- Superadmin (usertypeID=1) can bypass year restrictions

## Notes for AI Agents
- **Alert Trigger**: When a new notice is created, the Alert system should automatically notify relevant users
- **Date Callback**: `date_valid()` callback uses `DateTime::createFromFormat()` to validate date strings
- **Permission Check**: Add/Edit restricted to current school year unless user is superadmin
- **Email Function**: `send_mail()` loads notice data, formats email with notice content
- **Print Preview**: Separate view template for print-friendly layout (no header/footer navigation)
- **XSS Protection**: All inputs use `xss_clean` validation rule
- **School Scoping**: All queries filtered by `schoolID` from session
- **Create Tracking**: Records `create_userID` and `create_usertypeID` for audit trail
- **Unique Data Callback**: `unique_data()` validates noticeID is not "0" (select placeholder)
