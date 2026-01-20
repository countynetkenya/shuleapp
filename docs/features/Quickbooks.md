# Feature: Quickbooks

## Overview
**Controller**: `mvc/controllers/Quickbooks.php`  
**Primary Purpose**: QuickBooks Online OAuth authentication and token management  
**User Roles**: Admin (1), Systemadmin (5)  
**Status**: ✅ Active

## Functionality
### Core Features
- QuickBooks Online OAuth 2.0 authentication flow
- Access token and refresh token management
- Token refresh automation
- Connection status display
- Disconnect functionality

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | quickbooks | Show QB connection status and connect button | quickbooks |
| `callback()` | quickbooks/callback | OAuth callback handler | None (public) |
| `refreshtoken()` | quickbooks/refreshtoken | Refresh expired access token | quickbooks |
| `disconnect()` | quickbooks/disconnect | Disconnect QB integration | quickbooks |

## Data Layer
### Models Used
- quickbookssettings_m, quickbookslog_m

### Database Tables
- `quickbookssettings`: Settings with field_names and field_values
  - `client_id`, `client_secret`, `stage`, `active`
  - `sessionAccessToken`, `sessionAccessTokenExpiry`
  - `sessionRefreshToken`, `sessionRefreshTokenExpiry`
  - `realmID` (company ID)

## Validation Rules
- OAuth state parameter validation
- Realm ID validation
- Token expiry validation

## Dependencies & Interconnections
### Depends On
- **Quickbookssettings**: Client ID, secret, stage configuration
- **QuickBooks API**: External OAuth service

### Used By
- **Invoice**: Syncs invoices to QBO
- **Creditmemo**: Syncs credit memos to QBO
- **Payment**: Syncs payments to QBO
- **Feetypes**: Maps to QBO items
- **Credittypes**: Maps to QBO items

## User Flows
### Connect to QuickBooks
1. Admin navigates to quickbooks
2. Clicks "Connect to QuickBooks" button
3. Redirected to Intuit OAuth page
4. Signs in to QuickBooks company
5. Authorizes app
6. Redirected back to quickbooks/callback
7. System exchanges code for tokens
8. Stores tokens in quickbookssettings
9. Displays connection success

### Token Refresh Flow
1. System checks token expiry before API calls
2. If expired: calls quickbooks/refreshtoken
3. Uses refresh token to get new access token
4. Updates quickbookssettings with new tokens
5. Continues with original API call

## Edge Cases & Limitations
### Known Issues
1. **Manual Token Refresh**: Tokens don't auto-refresh on expiry during API calls
2. **No Error Recovery**: Failed token refresh requires manual reconnect
3. **Single Company**: Only one QB company per school

### Constraints
- Access token expires after 1 hour
- Refresh token expires after 100 days
- Must reconnect after refresh token expires

## Configuration
### QuickBooks App Setup
1. Create app at developer.intuit.com
2. Get Client ID and Client Secret
3. Set redirect URI: `https://yourdomain.com/quickbooks/callback`
4. Configure in Quickbookssettings controller

### Environment Settings
- `stage`: "development" or "production"
- Development: sandbox.api.intuit.com
- Production: api.intuit.com

## Notes for AI Agents
### Key Patterns
- OAuth 2.0 authorization code flow
- Token storage in database as serialized PHP objects
- Tokens managed per school
- RealmID identifies QB company

### Security Considerations
- Store tokens encrypted (currently not encrypted!)
- Validate callback state parameter
- Use HTTPS for callback URL

### Common Modifications
- Add token encryption
- Implement automatic token refresh
- Add multi-company support
- Add webhook subscriptions
- Implement sync queue

### Debugging Tips
- Check `quickbookslog` for API errors
- Verify token expiry: `now() < sessionAccessTokenExpiry`
- Check `realmID` matches QB company
- Test in sandbox before production
