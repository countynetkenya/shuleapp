# Feature: Register

## Overview
**Controller**: `mvc/controllers/Register.php`  
**Primary Purpose**: Handles new systemadmin (school admin) self-registration without requiring existing credentials.  
**User Roles**: Public (unauthenticated users)  
**Status**: ✅ Active

## Functionality

### Core Features
- Self-service systemadmin account creation
- Email verification with credentials
- Automatic usertypeID = 1 assignment
- Photo upload support
- Auto-login after registration
- No school assignment (requires school creation after login)

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| GET | /register/index | Display registration form | Public |
| POST | /register/index | Process registration and auto-login | Public |

## Data Layer

### Models Used
- `systemadmin_m`: System admin CRUD and password hashing
- `usertype_m`: Usertype lookup for session

### Database Tables
- `systemadmin`: systemadminID (PK), name, dob, email, jod, username, password (hashed), usertypeID (always 1), schoolID (empty), photo, create_date, modify_date, create_userID (0), create_username, create_usertype ("Admin"), active

## Validation Rules
- **name**: required, max_length[60], xss_clean
- **dob**: required, max_length[10], callback_date_valid (dd-mm-yyyy), xss_clean
- **email**: required, max_length[40], valid_email, xss_clean, callback_unique_email (checks across all 5 user tables)
- **username**: required, min_length[4], max_length[40], xss_clean, callback_lol_username (checks across all 5 user tables)
- **password**: required, min_length[4], max_length[40], xss_clean

## Dependencies & Interconnections

### Depends On (Upstream)
- None (self-service registration)

### Used By (Downstream)  
- **School**: New systemadmin must create school after registration
- **Signin**: Uses same authentication mechanism

### Related Features
- **Signin**: Auto-login after registration
- **School**: First action after registration is creating a school
- **Systemadmin**: Creates systemadmin record

## User Flows

### Primary Flow: Self-Register
1. User navigates to /register/index
2. Fills registration form (name, dob, email, username, password)
3. System validates email and username uniqueness across all 5 user tables
4. System hashes password via systemadmin_m->hash()
5. Creates systemadmin record with:
   - usertypeID = 1 (Admin)
   - schoolID = empty (no schools assigned yet)
   - active = 1
   - create_userID = 0 (self-created)
   - jod = current date
6. Sends welcome email with credentials via usercreatemail()
7. Auto-logs in user (sets session variables)
8. Redirects to /school/add (since customCompute($schoolID) == 0)

## Edge Cases & Limitations
- No email verification step (credentials sent but not verified)
- No CAPTCHA protection (vulnerable to bot registrations)
- schoolID left empty; user must create school immediately
- Username/email collision check across 5 tables (performance)
- No admin approval workflow
- Default photo not set (will use system default)

## Configuration
- Uses `_layout_register` view (custom layout)
- Header assets: datepicker.js for DOB field

## Notes for AI Agents
- **Self-Service**: Only controller allowing unauthenticated systemadmin creation
- **Auto-Login**: After registration, user immediately logged in and redirected
- **School Requirement**: User redirected to /school/add because schoolID is empty
- **Unique Checks**: lol_username and unique_email check across student, parents, teacher, user, systemadmin tables
- **Password Hashing**: Uses systemadmin_m->hash() (check implementation for algorithm)
- **Email Sending**: usercreatemail() sends plaintext credentials (security concern)
- **Photo Upload**: Supports photo but not shown in form (frontend may be incomplete)
- **DOB Validation**: date_valid callback expects dd-mm-yyyy format
- **Production Security**: Consider disabling this endpoint in production or adding CAPTCHA
