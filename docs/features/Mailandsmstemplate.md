# Feature: Mailandsmstemplate

## Overview
**Controller**: `mvc/controllers/Mailandsmstemplate.php`  
**Primary Purpose**: Manage reusable email and SMS templates with dynamic tag support for different user types.  
**User Roles**: Admin, Superadmin  
**Status**: ✅ Active

## Functionality
### Core Features
- **Template CRUD**: Create, read, update, delete message templates
- **Type Support**: Separate templates for email and SMS
- **User Type Targeting**: Templates specific to Admin, Teacher, Student, Parent
- **Dynamic Tags**: Insert placeholder tags that get replaced with real data
- **Tag Browser**: View available tags for each user type
- **WYSIWYG Editor**: Rich text editor for email templates

### Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | mailandsmstemplate/index | mailandsmstemplate | List all templates |
| `add()` | mailandsmstemplate/add | mailandsmstemplate_add | Create new template |
| `edit()` | mailandsmstemplate/edit/{id} | mailandsmstemplate_edit | Update template |
| `view()` | mailandsmstemplate/view/{id} | mailandsmstemplate_view | View template details |
| `delete()` | mailandsmstemplate/delete/{id} | mailandsmstemplate_delete | Delete template |
| `all_tag()` | Internal | - | Load available tags by usertype |

## Data Layer
### Models Used
- `mailandsmstemplate_m` - Template CRUD operations
- `mailandsmstemplatetag_m` - Available tag definitions
- `usertype_m` - User type information

### Database Tables
- `mailandsmstemplate` - Template storage (name, usertypeID, type, template, schoolID)
- `mailandsmstemplatetag` - Tag definitions and descriptions
- `usertype` - User type reference

## Validation Rules
### Email Template Rules (`rules_email()`)
- **type**: Required, "email", max 10 chars
- **email_name**: Required, template name, max 128 chars
- **email_user**: Required, usertype ID, max 15 chars, cannot be "select"
- **email_template**: Required, HTML content, max 20,000 chars

### SMS Template Rules (`rules_sms()`)
- **type**: Required, "sms", max 10 chars
- **sms_name**: Required, template name, max 128 chars
- **sms_user**: Required, usertype ID, max 15 chars, cannot be "select"
- **sms_template**: Required, plain text, max 1,500 chars (SMS length limit)

## Dependencies & Interconnections
### Depends On (Upstream)
- `Usertype` - User type definitions
- `Mailandsmstemplatetag` - Tag system

### Used By (Downstream)
- **Mailandsms** - Uses templates when sending bulk messages
- Template selection in message composition forms

### Related Features
- **Mailandsms** - Message sending system
- **Mailandsmstemplatetag** - Tag management (if exists)

## User Flows
### Create Email Template
1. Navigate to `mailandsmstemplate/add`
2. Select "Email" type
3. Choose user type (Admin, Teacher, Student, Parent)
4. Enter template name
5. Browse available tags for selected user type
6. Write template content with tags (e.g., "Hello [name]")
7. Submit to save

### Create SMS Template
1. Navigate to `mailandsmstemplate/add`
2. Select "SMS" type
3. Choose user type
4. Enter template name
5. Write short message with tags (max 1500 chars)
6. Submit to save

### Use Template in Mailandsms
1. Navigate to `mailandsms/add`
2. Select user type and message type
3. Choose template from dropdown
4. Template content auto-fills message field
5. Customize if needed
6. Send message

## Edge Cases & Limitations
- **Character Limits**: SMS templates limited to 1,500 chars (may be further limited by gateway)
- **Email Templates**: 20,000 char limit for rich text content
- **Tag Availability**: Not all tags work for all user types
- **Template Names**: Must be unique per user type and message type
- **Deletion**: Cannot delete if template is referenced in sent messages (may need cascade check)
- **Tag Syntax**: Tags must match exact format `[tagname]` to be replaced
- **HTML in SMS**: HTML tags in SMS templates will be sent as-is (not rendered)

## Configuration
### Available Tags by User Type
Tags are defined in `mailandsmstemplatetag` table:
- **Common**: `[name]`, `[email]`, `[phone]`, `[address]`
- **Student**: `[roll]`, `[class]`, `[section]`, `[exam_results]`, `[fees_balance]`
- **Teacher**: `[designation]`, `[department]`, `[subjects]`
- **Parent**: `[student_name]`, `[student_class]`, `[children_list]`
- **Admin**: `[admin_name]`, `[school_name]`

### Editor Configuration
- Uses `jquery-te-1.4.0` for rich text editing
- Supports HTML formatting for email templates
- Plain textarea for SMS templates

## Notes for AI Agents
- **Template vs Tag**: Templates are in `mailandsmstemplate`, tag definitions in `mailandsmstemplatetag`
- **Tag Processing**: Tags are NOT processed here - this controller only stores template text with tag placeholders
- **Tag Replacement**: Actual tag replacement happens in `Mailandsms::tagConvertor()` method
- **User Type Validation**: Callbacks `email_user_check()` and `sms_user_check()` ensure "select" placeholder not submitted
- **Two-Phase Creation**: Add and edit methods handle both email and SMS with conditional logic based on `type` field
- **Tag Display**: `all_tag()` method loads and displays available tags for the WYSIWYG editor
- **Template Reuse**: Templates are school-specific (schoolID field) and can be reused for multiple sends
- **Character Limit Rationale**: SMS 1,500 char limit allows for tag expansion while staying under gateway limits
- **No Template Versioning**: Updates overwrite existing templates - consider backing up before editing important templates
