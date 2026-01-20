# Feature: Update

## Overview
**Controller**: `mvc/controllers/Update.php`  
**Primary Purpose**: Auto-update system for downloading and installing software updates from remote server.  
**User Roles**: Superadmin only  
**Status**: ⚠️ Legacy (disabled in demo, use with caution)

## Functionality
- **Version Checking**: Queries remote server for available updates
- **Auto-download**: Downloads update packages
- **Auto-install**: Extracts and applies updates
- **Backup**: Should backup before update (if implemented)

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | update/index | superadmin | Check for updates and install |

## Configuration
- Version check URL: `http://demo.inilabs.net/autoupdate/update/index`
- Update files URL: `http://demo.inilabs.net/autoupdate/updatefiles/school/`
- Download path: `uploads/update/`
- Demo mode: Disabled

## Notes for AI Agents
- **Security Risk**: Auto-update from remote server can be dangerous if compromised
- **Disabled in Demo**: Protected by demo mode check
- **Manual Alternative**: Consider manual updates via Git/FTP instead
- **Backup First**: Always backup database and files before updating
- **Version Tracking**: Check `update` table for version history
- **Testing**: Test updates in staging environment first

