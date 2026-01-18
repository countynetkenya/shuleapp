# Feature: Menu

## Overview
**Controller**: `mvc/controllers/Menu.php`  
**Primary Purpose**: Dynamic menu builder for navigation (DEMO-ONLY - disabled in production)  
**User Roles**: Admin  
**Status**: ✅ Active

## Functionality

### Core Features
- Hierarchical menu structure
- Parent-child relationships
- Icon assignment
- Priority ordering

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| GET | /menu/index | List all menu items | Admin |
| GET/POST | /menu/add | Create menu item | Admin |

## Data Layer

### Models Used
- `menu_m`

### Database Tables
- menu: menuID, menuName, parentID, link, icon, status, priority, pullRight

## Validation Rules
- menuName: required, max_length[120]
- parentID: numeric (parent menu ID)
- link: required (URL path)

## Dependencies & Interconnections

### Depends On (Upstream)


### Used By (Downstream)  


## Edge Cases & Limitations
- Disabled in production/non-demo environments
- Redirects to dashboard if not demo mode

## Notes for AI Agents
- CRITICAL: Only works in demo mode (config_item('demo'))
- In production, menus are hard-coded in views
