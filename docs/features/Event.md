# Feature: Event

## Overview
**Controller**: `mvc/controllers/Event.php`  
**Primary Purpose**: School event calendar management for scheduling and publicizing school events with photo uploads.  
**User Roles**: Admin (manage), All users (view)  
**Status**: ✅ Active

## Functionality
- **Event CRUD**: Create, edit, delete events
- **Event Details**: Title, description, date/time, location
- **Photo Upload**: Event images/posters
- **Email Sharing**: Send event details via email
- **Print Support**: Printable event flyers
- **Public Display**: Events shown on frontend calendar

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | event/index | event | List events |
| `add()` | event/add | event_add | Create event |
| `edit()` | event/edit/{id}` | event_edit | Update event |
| `view()` | event/view/{id}` | event_view | View event details |
| `delete()` | event/delete/{id}` | event_delete | Delete event |
| `print_preview()` | event/print_preview/{id}` | event_view | Print event |
| `send_mail()` | event/send_mail | event_view | Email event |

## Data Layer
- `event` - Events (title, description, date, photo, schoolyearID, schoolID, create_date, create_userID)

## Validation Rules
- **title**: Required, max 128 chars
- **description**: Required
- **date**: Required, valid date format
- **photo**: Optional photo upload (callback: `photoupload()`)

## Notes for AI Agents
- Events trigger alerts for users
- Frontend displays public events
- Photo stored in uploads directory
- Can email event to parents/students
- Integrates with Alert system for notifications

