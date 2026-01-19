# Feature: Pages

## Overview
**Controller**: `mvc/controllers/Pages.php`  
**Primary Purpose**: CMS for creating static website pages (About, Contact, Services, etc.) with WYSIWYG editor and SEO support.  
**User Roles**: Admin, Superadmin  
**Status**: ✅ Active

## Functionality
- **Page CRUD**: Create, edit, delete static pages
- **WYSIWYG Editor**: Rich text editor with image upload
- **URL Slugs**: SEO-friendly URLs
- **SEO Fields**: Meta title, description, keywords
- **Publish Scheduling**: Set publish and expiry dates
- **Status Control**: Draft, Published, Unpublished
- **Featured Images**: Upload page banners
- **Template Selection**: Choose page layout template

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | pages/index | pages | List all pages |
| `add()` | pages/add | pages_add | Create new page |
| `edit()` | pages/edit/{id}` | pages_edit | Update page |
| `delete()` | pages/delete/{id}` | pages_delete | Delete page |

## Data Layer
- `pages` - Page content (title, content, url, meta_title, meta_description, publish_date, expire_date, status, template, featured_image)

## Validation Rules
- **title**: Required, max 255 chars
- **content**: Required, rich text (callback: `unique_content()`)
- **url**: Required, unique, URL-friendly (callback: `unique_url()`)
- **publish_date**: Valid date format (callback: `validateDate()`)
- **expire_date**: Must be after publish_date (callback: `dateCompare()`)
- **featured_image**: File upload validation (callback: `fileUpload()`)

## Notes for AI Agents
- Content stored as HTML from WYSIWYG editor
- URL field used for routing (e.g., `/page/about-us`)
- Featured image uploaded via AJAX file handler
- Template field determines which view file renders the page
- Publish/expire dates control visibility on frontend
- File upload methods: `setFileToEditor()`, `setFileInfo()`, `deleteFileInfo()`

