# Feature: Conversation

## Overview
**Controller**: `mvc/controllers/Conversation.php`  
**Primary Purpose**: Internal messaging system for direct communication between users (teachers, students, parents, admins) with threading, drafts, and attachments.  
**User Roles**: All authenticated users  
**Status**: ✅ Active

## Functionality
### Core Features
- **Direct Messaging**: Send messages to individual users or user groups
- **Conversation Threading**: Messages organized in conversation threads
- **User Group Support**: Message multiple users simultaneously (class, usertype, individual)
- **Draft Messages**: Save messages as drafts before sending
- **File Attachments**: Attach files to messages
- **Inbox/Sent/Trash**: Separate views for message organization
- **Favorite Messages**: Mark important conversations
- **Message Deletion**: Soft delete with trash folder

### Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | conversation/index | conversation | View inbox |
| `draft()` | conversation/draft | conversation | View draft messages |
| `sent()` | conversation/sent | conversation | View sent messages |
| `trash()` | conversation/trash | conversation | View trashed messages |
| `create()` | conversation/create | conversation_add | Compose new message |
| `view()` | conversation/view/{id}` | conversation_view | View conversation thread |
| `draft_send()` | conversation/draft_send/{id}` | conversation | Send draft message |
| `fav_status()` | conversation/fav_status | conversation | Toggle favorite status |
| `delete_conversation()` | conversation/delete_conversation | conversation | Move to trash |
| `delete_trash_to_trash()` | conversation/delete_trash_to_trash | conversation | Permanent delete |
| `classCall()` | AJAX | - | Get students by class |
| `adminCall()` | AJAX | - | Get admin users |
| `teacherCall()` | AJAX | - | Get teachers |
| `parentCall()` | AJAX | - | Get parents |
| `userCall()` | AJAX | - | Get users by type |
| `studentCall()` | AJAX | - | Get students with filters |
| `open()` | conversation/open | - | Mark message as opened |

## Data Layer
### Models Used
- `conversation_m` - Conversation and message CRUD
- `user_m`, `systemadmin_m`, `teacher_m`, `student_m`, `parents_m` - User data
- `classes_m`, `studentrelation_m` - Student/class relationships
- `usertype_m` - User type definitions

### Database Tables
- `conversation` - Conversation threads (create_date, modify_date, draft, schoolID)
- `conversation_msg` - Individual messages (subject, msg, attach, create_date)
- `conversation_user` - Conversation participants (is_sender, trash, fav, status)

## Validation Rules
### Message Rules (`rules()`)
- **userGroup**: Required, numeric, max 11 chars, must be valid group ID
- **subject**: Required, max 250 chars
- **message**: Optional, max 500 chars (can be empty if attachment present)
- **attachment**: Optional, max 500 chars filename, callback: `fileUpload()`

### Reply Rules (`rules($reply=true)`)
- **reply**: Required, max 500 chars, callback: `unique_message()`
- Removes userGroup, message, subject validations

## Dependencies & Interconnections
### Depends On (Upstream)
- User management (Systemadmin, Teacher, Student, Parents)
- Classes and Sections (for student filtering)
- Alert system (for new message notifications)

### Used By (Downstream)
- **Alert** - Displays new message notifications
- **Dashboard** - May show recent messages widget

### Related Features
- **Alert** - Message notifications
- **Mailandsms** - Bulk messaging (different purpose)

## User Flows
### Send Message to Class
1. Navigate to `conversation/create`
2. Select "Student" user group
3. Select class from dropdown
4. AJAX loads students in that class
5. Select recipients (one or multiple)
6. Enter subject and message
7. Optionally attach file
8. Submit - creates conversation with all recipients

### Reply to Message
1. View conversation thread
2. Scroll to bottom reply form
3. Enter reply message
4. Optionally attach file
5. Submit - adds message to thread

### Save Draft
1. Start composing message
2. Click "Save as Draft" button
3. Message saved with `draft=1` flag
4. Access from Drafts folder
5. Click "Send" when ready

## Edge Cases & Limitations
- **Message Length**: 500 char limit per message
- **Attachment Handling**: File upload validation in `fileUpload()` callback
- **Trash Behavior**: Soft delete (trash flag) allows recovery
- **Permanent Delete**: "Delete from trash" is irreversible
- **Conversation Ownership**: All participants can delete their copy
- **Draft Sending**: Drafts can only be sent by creator
- **Group Messaging**: Each recipient gets separate conversation thread
- **Status Tracking**: Tracks whether recipient has opened message

## Configuration
### File Upload Settings
- Configured in `fileUpload()` callback
- Likely uses CodeIgniter's upload library
- Max filename length: 500 chars

### User Group Types
Based on AJAX endpoints:
- Class-based (students in specific class)
- Admin users
- Teachers
- Parents
- Individual users
- Custom user selections

## Notes for AI Agents
- **Threading**: Conversations organized by `conversation_id`, messages by `msg_id`
- **Participants**: `conversation_user` table links users to conversations
- **Sender Flag**: `is_sender=1` identifies message creator
- **Trash Levels**: trash=1 (in trash), trash=2 (permanently deleted)
- **Favorite System**: `fav` field in `conversation_user` table
- **Status Field**: Tracks read/unread status
- **Alert Integration**: New messages trigger alerts via Alert controller
- **Multi-recipient**: When sending to group, creates separate conversation for each recipient
- **Draft Logic**: `draft=1` flag prevents message from appearing in recipient's inbox
- **Open Tracking**: `open()` method marks message as read
- **Permission Gating**: Heavy use of `permissionChecker()` throughout
- **School Scoping**: All conversations scoped to `schoolID`
- **AJAX Dependency**: User selection dropdowns populated via AJAX calls
- **Attachment Storage**: Files stored with original filename tracking
