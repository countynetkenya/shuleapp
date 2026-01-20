# Feature: Mailandsms

## Overview
**Controller**: `mvc/controllers/Mailandsms.php`  
**Primary Purpose**: Comprehensive email and SMS communication system for sending bulk messages to students, teachers, parents, and staff with template support, delivery tracking, and multiple SMS gateway integration.  
**User Roles**: Admin, Teacher (view only), Superadmin  
**Status**: ✅ Active

## Functionality
### Core Features
- **Bulk Email/SMS Sending**: Send messages to user groups (by type, class, individual)
- **Template Management**: Use pre-configured templates with dynamic tag replacement
- **Delivery Tracking**: Queue, review, send, and track delivery status
- **SMS Gateway Integration**: Supports multiple SMS providers (Twilio, Clickatell, Bulk SMS, Msg91, etc.)
- **Personalization**: Dynamic tags for student marks, fees, attendance, exam results
- **Attachment Support**: Email attachments
- **Parent Contact Options**: Send to parents instead of students
- **Delivery Reports**: Track sent/failed messages

### Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | mailandsms/index | mailandsms | List all sent messages |
| `review()` | mailandsms/review | mailandsms | Review pending messages before sending |
| `add()` | mailandsms/add | mailandsms_add | Create new email/SMS |
| `send()` | mailandsms/send | mailandsms_send | Send queued message |
| `resend()` | mailandsms/resend | mailandsms | Resend failed message |
| `discard()` | mailandsms/discard | mailandsms_discard | Delete message |
| `test()` | mailandsms/test | mailandsms_add | Test email/SMS configuration |
| `view()` | mailandsms/view | mailandsms_view | View message details |
| `alltemplate()` | AJAX | - | Get templates by usertype |
| `allusers()` | AJAX | - | Get users by type |
| `allstudent()` | AJAX | - | Get students by class |
| `allsection()` | AJAX | - | Get sections by class |
| `alltemplatedesign()` | AJAX | - | Get template content |
| `studentStatement()` | AJAX | - | Generate student fee statement |
| `examResults()` | AJAX | - | Generate student exam results |

## Data Layer
### Models Used
- `mailandsms_m` - Message queue management
- `mailandsmstemplate_m` - Message templates
- `mailandsmstemplatetag_m` - Template tag definitions
- `emailsetting_m` - SMTP configuration
- `payment_gateway_option_m` - SMS gateway settings
- `systemadmin_m`, `teacher_m`, `student_m`, `parents_m`, `user_m` - User data
- `classes_m`, `section_m`, `studentrelation_m` - Student enrollment
- `mark_m`, `grade_m`, `exam_m` - Academic records
- `payment_m`, `invoice_m`, `creditmemo_m` - Financial data
- `subject_m`, `schoolterm_m`, `fees_balance_tier_m` - Supporting data
- `studentgroup_m` - Student grouping

### Database Tables
- `mailandsms` - Message queue and history
- `mailandsmstemplate` - Message templates
- `mailandsmstemplatetag` - Available template tags
- `emailsetting` - SMTP/email configuration
- `payment_gateway_option` - SMS gateway credentials
- References: `systemadmin`, `teacher`, `student`, `parents`, `user`
- References: `classes`, `section`, `studentrelation`, `mark`, `exam`, `invoice`, `payment`

## Validation Rules
### Email Rules (`rules_mail()`)
- **email_usertypeID**: Required, max 15 chars, must exist
- **email_schoolyear**: Optional, XSS clean
- **email_class**: Optional, XSS clean
- **email_users**: Optional (specific user or "select" for all)
- **email_template**: Optional template ID
- **email_subject**: Required, max 255 chars
- **email_message**: Required, max 20,000 chars
- **email_attachment**: Optional file upload
- **email_use_parent_contact**: Boolean (send to parent instead of student)

### SMS Rules (`rules_sms()`)
- **sms_usertypeID**: Required, max 15 chars, must exist
- **sms_schoolyear**: Optional, XSS clean
- **sms_class**: Optional, XSS clean
- **sms_users**: Optional (specific user or "select" for all)
- **sms_template**: Optional template ID
- **sms_getway**: Required, max 15 chars, must be configured
- **sms_message**: Required, max 20,000 chars
- **sms_active**: Optional, must be unique if set

### Other Email Rules (`rules_otheremail()`)
- **otheremail_email**: Required, valid email format
- **otheremail_subject**: Required, max 255 chars
- **otheremail_message**: Required, max 20,000 chars

### Other SMS Rules (`rules_othersms()`)
- **othersms_number**: Required phone number
- **othersms_getway**: Required SMS gateway
- **othersms_message**: Required, max 20,000 chars

## Dependencies & Interconnections
### Depends On (Upstream)
- `Email Library` (CodeIgniter) - SMTP email sending
- `Inilabs Library` - Custom utilities
- `Emailsetting` - SMTP configuration
- `Payment_gateway_option` - SMS gateway credentials (Twilio, Clickatell, Bulk SMS, Msg91, etc.)
- User modules (Systemadmin, Teacher, Student, Parents)
- Academic modules (Classes, Section, Exam, Mark, Grade)
- Financial modules (Invoice, Payment, Creditmemo)

### Used By (Downstream)
- Various controllers that trigger automated notifications
- Cron jobs for scheduled messaging (if configured)
- Parent/Student portals for communication tracking

### Related Features
- **Mailandsmstemplate** - Template management
- **Alert** - System notifications
- **Notice** - Bulletin board
- **Conversation** - Direct messaging
- **Complain** - Complaint system
- **Emailsetting** - SMTP configuration
- **Payment_gateway_option** - SMS gateway setup

## User Flows
### Send Bulk Email
1. Navigate to `mailandsms/add`
2. Select "Email" tab
3. Choose usertype (Admin, Teacher, Student, Parent)
4. Select school year and class (for students)
5. Choose specific user or "All users"
6. Select template (optional) or write custom message
7. Enter subject and message (with template tags)
8. Add attachments (optional)
9. Submit - message queued in review
10. Navigate to `mailandsms/review`
11. Preview message with tag replacements
12. Click "Send" to deliver

### Send Bulk SMS
1. Navigate to `mailandsms/add`
2. Select "SMS" tab
3. Choose usertype and filters
4. Select SMS gateway
5. Enter message with tags
6. Submit - message queued
7. Review and send from review page

### Test Configuration
1. Navigate to `mailandsms/test`
2. Choose "Email" or "SMS"
3. Enter test recipient
4. Send test message
5. Verify delivery

### Template Tag Usage
- Tags like `[name]`, `[email]`, `[phone]`, `[class]`, `[roll]`
- Student-specific: `[exam_results]`, `[attendance]`, `[fees_balance]`, `[invoice_statement]`
- Parent-specific: `[parent_name]`, `[student_name]`
- System replaces tags before sending

## Edge Cases & Limitations
- **Large Recipient Lists**: Bulk sends process sequentially, may timeout for very large lists
- **SMS Gateway Limits**: Subject to provider rate limits and quotas
- **Email Deliverability**: Depends on SMTP configuration and sender reputation
- **Template Tags**: Not all tags available for all usertypes
- **Attachment Size**: Limited by PHP upload_max_filesize
- **Phone Number Format**: Must match gateway requirements
- **Queue Processing**: Messages stay in "review" status until manually sent
- **Failed Delivery**: Can resend individual failed messages
- **Character Limits**: SMS typically 160 chars (gateway-dependent)

## Configuration
### Email Settings (emailsetting table)
- **protocol**: SMTP, Mail, Sendmail
- **smtp_host**: SMTP server
- **smtp_port**: Usually 587 or 465
- **smtp_user**: Authentication username
- **smtp_pass**: Encrypted password
- **mailtype**: HTML or text

### SMS Gateway Settings (payment_gateway_option)
Supported gateways:
- **Twilio**: Account SID, Auth Token
- **Clickatell**: API Key
- **Bulk SMS**: Username, Password
- **Msg91**: Auth Key
- **Others**: Gateway-specific credentials

Configuration stored in `payment_gateway_option` table with:
- `gateway_name`: Provider name
- `gateway_username`: Username/API key
- `gateway_password`: Password/secret
- `status`: Active/inactive

### Template Tag System
Tags defined in `mailandsmstemplatetag` table:
- Tag name (e.g., `[name]`)
- Tag description
- Usertype applicability
- Dynamic value source

## Notes for AI Agents
- **Gateway Integration**: Uses `inilabs` library for SMS gateway abstraction. Check `libraries/Inilabs.php` for gateway implementations.
- **Tag Processing**: `tagConvertor()` method handles all dynamic replacements. Complex tags like `[exam_results]` and `[invoice_statement]` generate formatted HTML/text blocks.
- **Lazy Loading Warning**: This controller loads 20+ models in `__construct()`. Per LEARNINGS.md, this should be refactored to load models only when needed.
- **Email Library**: Uses CodeIgniter's Email library. Configuration loaded from `emailsetting_m`.
- **Delivery Tracking**: Messages marked as `reviewed=0` until sent, then `reviewed=1`. Failed sends can be identified and retried.
- **Security**: Always validate usertype permissions before sending. Messages tied to sender's `usertypeID` and `loginuserID`.
- **Batch Processing**: For large sends, consider implementing queue worker or chunking to avoid timeouts.
- **Parent Contact**: When `use_parent_contact=1`, system looks up parent email/phone from student relationship.
- **Financial Tags**: Invoice statement and fees balance tags query payment/invoice tables and format results.
- **Exam Results Tag**: Queries mark/grade tables, formats as table with subjects, marks, and grades.
- **Character Encoding**: Messages support UTF-8 for multi-language content.
- **Testing**: Use `test()` method to verify gateway configuration before bulk sends.
