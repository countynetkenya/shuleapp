# Feature: School

## Overview
**Controller**: `mvc/controllers/School.php`  
**Primary Purpose**: Manages school instances in multi-tenant architecture; handles school creation, editing, and selection for users with multiple school access.  
**User Roles**: Admin (Systemadmin - usertypeID 0)  
**Status**: ✅ Active

## Functionality

### Core Features
- Create new school instances with default settings
- Edit existing school names
- School selection interface for multi-school users
- Auto-creation of default schoolyear, settings, permissions, and payment gateway options
- Auto-assignment of new schools to systemadmins and users

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| GET/POST | /school/select | Select active school from multiple assigned schools | Authenticated users |
| GET | /school/index | List all schools | Admin_Controller |
| GET | /school/add | Display add school form | Admin_Controller |
| POST | /school/add | Create new school with defaults | Admin_Controller |
| GET | /school/edit/{id} | Display edit school form | Admin_Controller |
| POST | /school/edit/{id} | Update school name | Admin_Controller |

## Data Layer

### Models Used
- `school_m`: School CRUD operations
- `systemadmin_m`: Systemadmin management
- `user_m`: User management
- `payment_gateway_option_m`: Payment gateway settings
- `schoolyear_m`: Auto-create default schoolyear
- `setting_m`: Auto-create default settings
- `permission_m`: Auto-create permission relationships
- `quickbookssettings_m`: Auto-create QuickBooks settings
- `site_m`: Retrieve site info for session

### Database Tables
- `school`: schoolID (PK), name, create_date
- `schoolyear`: Links to school for academic year
- `setting`: School-specific configuration (attendance, currency, theme, etc.)
- `permission_relationships`: Auto-created for usertypes 1-7
- `quickbookssettings_values`: Auto-created QB settings (8 entries)
- `payment_gateway_option_values`: Auto-created payment options (17 entries)

## Validation Rules
- **name**: required, max_length[60], xss_clean, callback_unique_school

## Dependencies & Interconnections

### Depends On (Upstream)
- **Systemadmin**: Schools must be assigned to systemadmins
- **User**: Multi-school users require school selection

### Used By (Downstream)  
- **Dashboard**: Requires active schoolID in session
- **All Features**: schoolID scopes all data in multi-tenant architecture

### Related Features
- **Schoolyear**: Auto-created on school creation
- **Setting**: Auto-created with defaults (currency: KES, theme: default, etc.)
- **Permission**: Bulk permissions created for 7 usertypes
- **Systemadmin**: Schools added to systemadmin's schoolID list
- **User**: Schools added to user's schoolID list

## User Flows

### Primary Flow: Create New School
1. Admin navigates to /school/add
2. Enters school name
3. System creates school record
4. System auto-creates: default schoolyear (current year), settings record, 500+ permission relationships for usertypes 1-7, 8 QuickBooks settings, 17 payment gateway options
5. System updates all systemadmins and users with new schoolID
6. Redirects to school/index with success message

### Primary Flow: Select School (Multi-School Users)
1. User logs in with access to multiple schools
2. Redirected to /school/select
3. Selects school from dropdown
4. System sets session: schoolID, defaultschoolyearID, lang
5. Redirects to /dashboard/index

## Edge Cases & Limitations
- School deletion not implemented (only creation/editing)
- Cannot edit school beyond name field
- School selection required for multi-school users (blocks access until selected)
- Massive permission bulk insert (500+ rows) on school creation

## Configuration
- Settings auto-created: attendance: 'day', automation: 5, currency_code: 'KES', currency_symbol: 'KSH', backend_theme: 'default', language: 'english', student_ID_format: 1
- Default schoolyear: Format `YYYY-YYYY+1`, dates: Jan 1 - Dec 31

## Notes for AI Agents
- **Critical**: New school creation triggers cascading inserts across 5+ tables (~500+ rows)
- **Performance**: School add operation is heavy; not optimized for bulk creation
- **Multi-Tenancy**: schoolID is the primary tenant identifier; all queries must filter by it
- **Session Management**: school/select updates session vars: schoolID, defaultschoolyearID, lang
- **Permission Complexity**: Hard-coded permission arrays (lines 125-948) are brittle; changes require manual updates
- **User Assignment**: All systemadmins/users with matching schoolID get new school appended to their comma-separated schoolID field

