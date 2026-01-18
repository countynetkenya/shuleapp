# Feature: Quickbookssettings

## Overview
**Controller**: `mvc/controllers/Quickbookssettings.php`  
**Primary Purpose**: Configure QuickBooks Online integration settings  
**User Roles**: Admin (1), Systemadmin (5)  
**Status**: ✅ Active

## Functionality
### Core Features
- Configure QuickBooks Client ID and Secret
- Set development/production environment
- Enable/disable QuickBooks integration
- Display authorization URL
- Test QB connection

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | quickbookssettings | Configure QB settings | quickbookssettings |

## Data Layer
### Models Used
- quickbookssettings_m

### Database Tables
- `quickbookssettings`: Settings stored as key-value pairs
  - quickbookssettingsID, field_names, field_values, schoolID

## Validation Rules
- `client_id`: required, max 255 chars
- `client_secret`: required, max 255 chars
- `stage`: required, "development" or "production"
- `active`: "1" (enabled) or "0" (disabled)

## Dependencies & Interconnections
### Used By
- **Quickbooks**: Uses settings for OAuth flow
- **Invoice/Creditmemo/Payment**: Check if QB enabled before syncing

## User Flows
1. Admin navigates to quickbookssettings
2. Enter Client ID and Secret from Intuit developer portal
3. Select stage (development/production)
4. Set active status (enable/disable)
5. Save settings
6. Authorization URL displayed
7. Click to connect via Quickbooks controller

## Configuration
### QuickBooks App Registration
1. Go to developer.intuit.com
2. Create app
3. Get credentials:
   - Client ID (Production Keys or Sandbox Keys)
   - Client Secret
4. Set Redirect URI: `https://yourdomain.com/quickbooks/callback`

## Notes for AI Agents
### Key Patterns
- Batch update using `update_batch_quickbookssetting_values()`
- Settings stored with quickbookssettingsID: 1=client_id, 2=client_secret, 5=stage, 6=active

### Common Modifications
- Add settings validation
- Add test connection button
- Display connection status
- Add settings export/import
