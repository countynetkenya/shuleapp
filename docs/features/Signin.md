# Feature: Signin

## Overview
**Controller**: `mvc/controllers/Signin.php`  
**Primary Purpose**: Handles user authentication and session management with role-based redirection logic documented in LEARNINGS.md.  
**User Roles**: Public (unauthenticated users)  
**Status**: ✅ Active

## Functionality

### Core Features
- Username/password authentication across 5 user tables
- Optional Google reCAPTCHA support
- Remember me cookie functionality
- Change password functionality
- Role-based redirection after login
- Multi-school selection for users with > 1 school
- Session initialization with schoolID, defaultschoolyearID, lang

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| GET | /signin/index | Display login form | Public |
| POST | /signin/index | Process login and redirect based on usertype | Public |
| GET | /signin/signout | Logout and destroy session | Authenticated |
| GET | /signin/recoverpassword | Display password recovery form | Public |
| POST | /signin/recoverpassword | Send password reset email | Public |
| GET | /signin/change_password | Display change password form | Authenticated |
| POST | /signin/change_password | Update password | Authenticated |

## Data Layer

### Models Used
- `signin_m`: Authentication logic
- `user_m`: User lookup across tables
- `school_m`: School selection
- `site_m`: Site info retrieval for session

### Database Tables
Queries across 5 tables:
- `systemadmin`: usertypeID = 0
- `student`: usertypeID = 3
- `teacher`: usertypeID = 2
- `parents`: usertypeID = 4
- `user`: usertypeID >= 5

## Validation Rules
- **username**: required, max_length[40], xss_clean
- **password**: required, max_length[40], xss_clean
- **g-recaptcha-response**: required (if captcha enabled)
- **Change Password**:
  - old_password: required, min_length[4], max_length[40], callback_old_password_unique
  - new_password: required, min_length[4], max_length[40]
  - re_password: required, matches[new_password]

## Dependencies & Interconnections

### Depends On (Upstream)
- **All User Tables**: Searches systemadmin, student, teacher, parents, user for credentials
- **School**: Multi-school users require school selection
- **Site**: Session requires siteInfo (schoolID, defaultschoolyearID, lang)

### Used By (Downstream)  
- **All Features**: Session variables set by Signin are used everywhere
- **Dashboard**: Redirect target after successful login

### Related Features
- **Register**: New systemadmin registration
- **Resetpassword**: Password recovery
- **School**: Multi-school selection logic

## User Flows

### Primary Flow: Login (as documented in LEARNINGS.md)
1. User enters username/password
2. System validates credentials across 5 user tables
3. System identifies usertypeID
4. **Redirection Logic** (CRITICAL):
   - **usertypeID = 0 (Superadmin)**: Redirect to `/admin/index`, set schoolID = 0
   - **usertypeID = 1 or 5-8 (Admin/Staff)**:
     - If assigned to >1 school: Redirect to `/school/select`
     - If assigned to 0 schools: Redirect to `/school/add`
     - If assigned to 1 school: Set session (schoolID, defaultschoolyearID, lang), redirect to `/dashboard/index`
   - **usertypeID = 2, 3, 4 (Teacher/Student/Parent)**: Set session for first school, redirect to `/dashboard/index`

### Primary Flow: Change Password
1. Authenticated user navigates to /signin/change_password
2. Enters old password, new password, re-enter new password
3. System validates old password matches current
4. System validates new passwords match
5. Updates password (hashed) in appropriate user table
6. Displays success message

## Edge Cases & Limitations
- Username/password checked across 5 tables (performance impact)
- Multi-school logic only for usertypeID 1, 5-8 (not teachers/students/parents)
- reCAPTCHA setting controlled by siteinfos->captcha_status (0 = enabled, 1 = disabled)
- Remember me cookie duration not configurable
- Password reset email requires mail configuration

## Configuration
- **captcha_status**: 0 = reCAPTCHA enabled, 1 = disabled (default)
- **recaptcha_site_key**: Google reCAPTCHA site key
- **recaptcha_secret_key**: Google reCAPTCHA secret key
- Cookie settings: username/password stored if "Remember me" checked

## Notes for AI Agents
- **CRITICAL**: Login redirection logic is complex; see LEARNINGS.md for detailed flow
- **Superadmin Special Case**: usertypeID = 0 bypasses school selection, sets schoolID = 0
- **Multi-School Check**: `customCompute($schoolID) > 1` determines if school selection needed
- **Session Variables Set**: schoolID, defaultschoolyearID, lang, loginuserID, usertypeID, usertype, username, name, email
- **Password Hashing**: Uses model-specific hash methods (systemadmin_m->hash(), etc.)
- **Cookie Security**: _setCookie() method handles remember me functionality
- **reCAPTCHA**: Only validated if captcha_status == 0 (counter-intuitive)
- **Layout**: Uses `_layout_signin` view (different from main layout)
