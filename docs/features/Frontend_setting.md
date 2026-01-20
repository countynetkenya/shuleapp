# Feature: Frontend_setting

## Overview
**Controller**: `mvc/controllers/Frontend_setting.php`  
**Primary Purpose**: Configuration interface for public website settings (homepage selection, SEO, analytics codes).  
**User Roles**: Admin, Superadmin  
**Status**: ✅ Active

## Functionality
- **Homepage Selection**: Choose which page/post appears as homepage
- **SEO Settings**: Meta tags, keywords, descriptions
- **Analytics Integration**: Google Analytics, tracking codes
- **Frontend Toggle**: Enable/disable public website

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | frontend_setting/index | frontend_setting | View/update settings |

## Data Layer
- `frontend_setting` table - Single row with configuration

## Notes for AI Agents
- Single-row configuration table
- Homepage can be a page, post, or default template
- SEO fields for search engine optimization
- Analytics code injected into frontend layout

