# Feature: Sociallink

## Overview
**Controller**: `mvc/controllers/Sociallink.php`  
**Primary Purpose**: Manage social media links displayed in website footer/header (Facebook, Twitter, LinkedIn, etc.).  
**User Roles**: Admin, Superadmin  
**Status**: ✅ Active

## Functionality
- **Social Link CRUD**: Create, edit, delete social media links
- **Icon Support**: Select social media platform icon
- **URL Management**: Enter profile URLs
- **Display Order**: Control link sequence
- **Active Status**: Show/hide links

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | sociallink/index | sociallink | List all links |
| `add()` | sociallink/add | sociallink_add | Add social link |
| `edit()` | sociallink/edit/{id}` | sociallink_edit | Update link |
| `delete()` | sociallink/delete/{id}` | sociallink_delete | Delete link |

## Data Layer
- `sociallink` - Social links (title, url, icon, status, schoolID)

## Validation Rules
- **title**: Required (e.g., "Facebook", "Twitter")
- **url**: Required, valid URL format
- **icon**: Icon class name (e.g., "fa-facebook")
- **status**: Active/inactive

## Notes for AI Agents
- Displayed in frontend footer typically
- Icon field likely uses Font Awesome classes
- Common platforms: Facebook, Twitter, LinkedIn, Instagram, YouTube, Pinterest
- Frontend reads active links and renders icons

