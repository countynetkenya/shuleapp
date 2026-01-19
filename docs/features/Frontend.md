# Feature: Frontend

## Overview
**Controller**: `mvc/controllers/Frontend.php`  
**Primary Purpose**: Public-facing website controller that renders pages, posts, events, and notices for visitors (not logged-in users).  
**User Roles**: Public (no login required)  
**Status**: ✅ Active

## Functionality
### Core Features
- **Homepage Rendering**: Displays configured homepage (page, post, or default)
- **Page Display**: Renders static CMS pages by URL
- **Post Display**: Shows blog posts by URL
- **Event Listing**: Public event calendar
- **Notice Board**: Public notice viewing
- **Contact Form**: Email submission from contact page
- **Event RSVP**: "Going" feature for events

### Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | / (frontend) | public | Homepage |
| `page()` | frontend/page/{url}` | public | Display CMS page |
| `post()` | frontend/post/{url}` | public | Display blog post |
| `home()` | frontend/home | public | Default homepage |
| `event()` | frontend/event | public | Event calendar |
| `eventGoing()` | frontend/eventGoing | public | RSVP for event |
| `notice()` | frontend/notice | public | Notice board |
| `contactMailSend()` | frontend/contactMailSend | public | Send contact form |

## Data Layer
### Models Used
- `pages_m` - CMS pages
- `posts_m` - Blog posts  
- `event_m` - Events
- `notice_m` - Notices
- `media_gallery_m` - Images
- `slider_m` - Homepage slider

### Database Tables
- `pages`, `posts`, `event`, `notice`, `media_gallery`, `slider`
- `frontend_setting` - Homepage configuration

## Dependencies & Interconnections
### Depends On (Upstream)
- **Pages** - Content management
- **Posts** - Blog content
- **Event** - Calendar events
- **Notice** - Announcements
- **Frontend_setting** - Website configuration

### Used By (Downstream)
- Public visitors
- Search engines (SEO)

### Related Features
- **Frontend_setting**, **Frontendmenu**, **Pages**, **Posts**, **Sociallink**

## Notes for AI Agents
- **Frontend_Controller**: Extends special controller for public access (no login)
- **Homepage Logic**: Checks `frontend_setting` for configured homepage, falls back to `home()`
- **SEO**: Pages and posts have meta tags for search engines
- **URL Routing**: Clean URLs via page/post `url` field
- **Contact Form**: Sends email using system email settings
- **Event RSVP**: Tracks visitor interest in events (may require cookies/session)

