# Feature: Posts

## Overview
**Controller**: `mvc/controllers/Posts.php`  
**Primary Purpose**: Blog/news system with categories, featured images, and publish scheduling for school announcements and articles.  
**User Roles**: Admin, Superadmin  
**Status**: ✅ Active

## Functionality
- **Post CRUD**: Create, edit, delete blog posts
- **Category Support**: Organize posts by categories
- **WYSIWYG Editor**: Rich text content editor
- **Featured Images**: Upload post thumbnails
- **SEO Optimization**: Meta tags for each post
- **URL Slugs**: Clean, SEO-friendly URLs
- **Publish Scheduling**: Schedule future posts
- **Status Management**: Draft, Published, Unpublished

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | posts/index | posts | List all posts |
| `add()` | posts/add | posts_add | Create new post |
| `edit()` | posts/edit/{id}` | posts_edit | Update post |
| `delete()` | posts/delete/{id}` | posts_delete | Delete post |
| `addCategory()` | AJAX | posts_add | Create category inline |

## Data Layer
- `posts` - Blog posts (title, content, url, categoryID, meta_title, meta_description, publish_date, expire_date, status, featured_image, author_userID)
- `posts_categories` - Post categories

## Validation Rules
- **title**: Required, max 255 chars
- **content**: Required, rich text
- **url**: Required, unique, URL-friendly (callback: `unique_url()`)
- **categoryID**: Optional, must exist if provided
- **publish_date**: Valid date (callback: `validateDate()`)
- **expire_date**: After publish_date (callback: `dateCompare()`)
- **featured_image**: File upload validation (callback: `fileUpload()`)

## Notes for AI Agents
- Very similar to Pages but with category support
- Author tracked via `author_userID`
- Used for school news, announcements, blog articles
- Featured image displayed in post listings
- Category can be created inline during post creation (`addCategory()`)
- URL field must be unique across all posts
- File handling similar to Pages controller

