# Feature: Alert

## Overview
**Controller**: `mvc/controllers/Alert.php`  
**Primary Purpose**: Real-time notification system that aggregates and displays unread items (notices, events, holidays, messages) in a dropdown alert panel.  
**User Roles**: All authenticated users  
**Status**: ✅ Active

## Functionality
### Core Features
- **Unified Alert System**: Aggregates notices, events, holidays, and messages
- **Read/Unread Tracking**: Marks items as read when clicked
- **AJAX Polling**: Real-time alert updates without page refresh
- **Alert Dropdown**: Displays unread count and recent items
- **Multi-type Support**: Handles different alert types (notice, event, holiday, message)
- **Time Display**: Shows relative time for each alert
- **Permission-based**: Only shows alerts user has permission to view
- **Redirect on Click**: Routes to appropriate view when alert clicked

### Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | alert/index/{type}/{id}` | - | Mark alert as read, redirect to item |
| `alert()` | alert/alert | - | AJAX endpoint for fetching alerts |
| `_alertNotice()` | Internal | notice_view | Fetch unread notices |
| `_alertEvent()` | Internal | event_view | Fetch unread events |
| `_alertHoliday()` | Internal | holiday_view | Fetch unread holidays |
| `_alertMessage()` | Internal | conversation | Fetch unread messages |
| `_userAlert()` | Internal | - | Load user's read alert history |
| `_alertOrder()` | Internal | - | Sort alerts by date descending |
| `_alertMarkup()` | Internal | - | Generate HTML for alert dropdown |
| `_pusher()` | Internal | - | Format single alert item |
| `_timer()` | Internal | - | Calculate relative time display |

## Data Layer
### Models Used
- `alert_m` - Alert tracking (read/unread status)
- `notice_m` - Notice data
- `event_m` - Event data
- `holiday_m` - Holiday data
- `conversation_m` - Message data

### Database Tables
- `alert` - Tracks which items user has seen (userID, usertypeID, itemID, itemname)
- References: `notice`, `event`, `holiday`, `conversation` (msg table)

## Validation Rules
No form validation (read-only system, no user input except clicks)

### Alert Marking Logic
- When user clicks alert, record inserted: `(userID, usertypeID, itemID, itemname)`
- Future alert queries exclude items in user's alert history
- **itemname** values: `notice`, `event`, `holiday`, `message`

## Dependencies & Interconnections
### Depends On (Upstream)
- **Notice** - Fetches new notices
- **Event** - Fetches new events
- **Holiday** - Fetches new holidays
- **Conversation** - Fetches new messages
- Permission system - Checks user permissions for each alert type

### Used By (Downstream)
- **Dashboard** - Alert dropdown in header
- **Page Header** - Bell icon with unread count
- AJAX polling from all pages (via `alert/alert` endpoint)

### Related Features
- **Notice** - Notice management
- **Event** - Event calendar
- **Holiday** - Holiday calendar
- **Conversation** - Messaging system

## User Flows
### View Alerts
1. Page loads with alert dropdown in header
2. JavaScript polls `alert/alert` endpoint every X seconds
3. Unread count displays on bell icon
4. User clicks bell to view alert dropdown
5. Dropdown shows recent unread items with titles and times

### Mark Alert as Read
1. User clicks on alert item in dropdown
2. AJAX call to `alert/index/{type}/{id}`
3. System records read status in `alert` table
4. User redirected to item view (e.g., `notice/view/5`)
5. Alert disappears from dropdown on next refresh

### Alert Visibility Rules
- **Notice**: Requires `notice_view` permission
- **Event**: Requires `event_view` permission
- **Holiday**: Requires `holiday_view` permission
- **Message**: Requires `conversation` permission
- Only shows items for current school year

## Edge Cases & Limitations
- **Polling Frequency**: Too frequent polling can increase server load
- **Alert Persistence**: Alerts never expire - old school year items remain unless cleaned
- **Read Status**: Once marked read, cannot mark unread
- **Multi-device**: Read status not synced in real-time across devices (only on poll)
- **Message Threading**: Messages mark entire conversation thread as read
- **Performance**: Large number of unread items may slow alert query
- **Permissions**: Permission changes don't retroactively remove old alerts
- **Alert History**: No UI to view read alerts (one-way marking)

## Configuration
### Polling Settings
Configured in JavaScript (typically in layout header):
```javascript
setInterval(function() {
    $.ajax({
        url: base_url + 'alert/alert',
        type: 'GET',
        // ... handle response
    });
}, 30000); // Poll every 30 seconds
```

### Alert Types Configuration
Hardcoded alert types:
- `notice` - Routes to `notice/view/{id}`
- `event` - Routes to `event/view/{id}`
- `holiday` - Routes to `holiday/view/{id}`
- `message` - Routes to `conversation/view/{id}`

## Notes for AI Agents
- **AJAX Only**: `alert()` method checks `$this->input->is_ajax_request()` - must be called via AJAX
- **Private Methods**: All data fetching methods are private (underscore prefix)
- **Alert Aggregation**: System pulls from 4 different tables, merges, sorts by `create_date`
- **Markup Generation**: `_alertMarkup()` generates HTML string for dropdown (not JSON)
- **Time Display**: `_timer()` calculates relative time ("2 hours ago", "1 day ago")
- **Read Tracking**: Uses combination of `userID + usertypeID + itemID + itemname` as composite key
- **Message Special Case**: For messages, marks all messages in conversation thread as read
- **Performance Consideration**: Each alert poll runs 4-5 separate queries - consider caching
- **School Year Scoping**: All alerts filtered by `defaultschoolyearID` from session
- **Permission Gating**: Each alert type wrapped in `permissionChecker()` - respects user permissions
- **No Delete**: Alerts are never deleted from `alert` table, only inserted
- **Redirect Logic**: `index()` method handles all redirect logic based on `itemname` parameter
- **Session Dependency**: Requires `loggedin`, `loginuserID`, `usertypeID`, `defaultschoolyearID` in session
- **HTML Output**: Returns raw HTML, not JSON - frontend inserts directly into dropdown
