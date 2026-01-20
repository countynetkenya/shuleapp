# Feature: Complain

## Overview
**Controller**: `mvc/controllers/Complain.php`  
**Primary Purpose**: Formal complaint/grievance system allowing users to file, track, and manage complaints with attachments and email notifications.  
**User Roles**: All users (can file), Admin (can view all)  
**Status**: ✅ Active

## Functionality
### Core Features
- **Complaint Filing**: Users submit complaints about specific individuals
- **Attachment Support**: Upload supporting documents
- **User Targeting**: Complaints filed against specific user (any usertype)
- **Admin Overview**: Admins see all complaints, users see only their own
- **Email Notification**: Send complaint via email
- **Print Support**: Print-friendly complaint view
- **User Filtering**: AJAX-based user selection by type and class

### Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | complain/index | complain | List complaints (all if admin, own if user) |
| `add()` | complain/add | complain_add | File new complaint |
| `edit()` | complain/edit/{id}` | complain_edit | Edit complaint |
| `view()` | complain/view/{id}` | complain_view | View complaint details |
| `delete()` | complain/delete/{id}` | complain_delete | Delete complaint |
| `print_preview()` | complain/print_preview/{id}` | complain_view | Printable complaint |
| `send_mail()` | complain/send_mail | complain_view | Email complaint |
| `allStudent()` | AJAX | - | Get students by class |
| `allusers()` | AJAX | - | Get users by usertype |
| `download()` | complain/download/{id}` | complain_view | Download attachment |

## Data Layer
### Models Used
- `complain_m` - Complaint CRUD
- `systemadmin_m`, `teacher_m`, `student_m`, `parents_m`, `user_m` - User data
- `classes_m`, `section_m`, `studentrelation_m` - Student relationships

### Database Tables
- `complain` - Complaints (title, usertypeID, userID, description, attachment, schoolyearID, schoolID, create_date, create_userID, create_usertypeID)

## Validation Rules
- **title**: Required, max 128 chars
- **usertypeID**: Required (type of user being complained about)
- **userID**: Required (specific user being complained about)
- **description**: Required, rich text
- **attachment**: Optional, max 200 chars filename, callback: `attachUpload()`

## Dependencies & Interconnections
### Depends On (Upstream)
- All user type modules (for targeting complaints)
- Classes/Sections (for student filtering)

### Used By (Downstream)
- HR/Admin for complaint tracking and resolution

### Related Features
- **Notice** - Announcements
- **Conversation** - Direct messaging (alternative to formal complaints)

## User Flows
### File Complaint
1. Navigate to `complain/add`
2. Enter complaint title
3. Select usertype of person being complained about
4. AJAX loads users of that type
5. Select specific user
6. Write description
7. Optionally attach file
8. Submit - complaint recorded with creator's userID

### Admin Review Complaints
1. Admin navigates to `complain/index`
2. Sees all complaints across all users
3. Clicks complaint to view details
4. Can print, email, or take action

## Edge Cases & Limitations
- **Visibility**: Non-admins only see complaints they created
- **Edit Restrictions**: Users can only edit their own complaints
- **School Year Scoping**: Complaints tied to school year
- **Attachment Storage**: Single attachment per complaint
- **No Status Field**: No built-in workflow (pending/resolved)
- **Anonymous**: No anonymous complaints - always tracked to creator

## Configuration
- Attachment upload path configured in `attachUpload()` callback
- Email settings use system email configuration

## Notes for AI Agents
- **Creator Tracking**: Records `create_userID` and `create_usertypeID`
- **Access Control**: `usertypeID==1` check for admin-only views
- **AJAX User Loading**: `allusers()` and `allStudent()` populate dropdowns
- **Attachment Handling**: Files stored with generated names, original names preserved
- **Download Method**: Serves file with original filename for download
- **Print View**: Separate template for print layout
- **Email Integration**: Uses system email settings to send complaints
- **No Resolution Tracking**: System only stores complaints, doesn't track resolution status

