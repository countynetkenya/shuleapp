# Feature: Posts_categories

## Overview
**Controller**: `mvc/controllers/Posts_categories.php`  
**Primary Purpose**: Simple CRUD for managing blog post categories.  
**User Roles**: Admin, Superadmin  
**Status**: ✅ Active

## Functionality
- **Category CRUD**: Create, edit, delete post categories
- **Simple Structure**: Category name only

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | posts_categories/index | posts_categories | List categories |
| `add()` | posts_categories/add | posts_categories_add | Create category |
| `edit()` | posts_categories/edit/{id}` | posts_categories_edit | Update category |
| `delete()` | posts_categories/delete/{id}` | posts_categories_delete | Delete category |

## Data Layer
- `posts_categories` - Categories (posts_categoriesID, title, schoolID)

## Validation Rules
- **title**: Required, max 255 chars

## Notes for AI Agents
- Basic category management for Posts
- Used to organize blog posts by topic (News, Events, Announcements, etc.)
- No hierarchy - flat category structure
- Should prevent delete if posts reference category

