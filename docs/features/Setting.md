# Feature: Setting

## Overview
**Controller**: `mvc/controllers/Setting.php`  
**Primary Purpose**: School-wide configuration management including profile, currency, themes, attendance mode, automation, and integration settings.  
**User Roles**: Admin  
**Status**: ✅ Active

## Functionality

### Core Features
- School profile (name, address, phone, email)
- Currency configuration (code, symbol)
- Theme selection (backend/frontend)
- Attendance mode (day-based vs subject-based)
- Auto-invoice generation toggle and day setting
- Language selection
- Google Analytics integration
- Student ID format
- reCAPTCHA settings
- Photo/logo upload
- Frontend enable/disable toggle

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| GET | /setting/index | Display settings form | Admin_Controller |
| POST | /setting/update | Update school settings | Admin_Controller |
| POST | /setting/do_upload | Upload school logo | Admin_Controller |

## Data Layer

### Models Used
- setting_m: Settings CRUD
- school_m: School reference
- schoolyear_m: Year selection
- themes_m: Theme options
- classes_m: Class exclusion for automation
- mailandsmstemplate_m: Template integration

## Validation Rules
- sname: required, max_length[128]
- phone: required, max_length[25]
- automation: max_length[5], callback_unique_day (1-31)
- auto_invoice_generate: required, max_length[5] (0 or 1)
- note: required, max_length[5] (marks display toggle)
- currency_code: required, max_length[11]
- currency_symbol: required, max_length[3]
- address: required, max_length[200]
- frontendorbackend: required (frontend toggle)
- language: required
- Many more fields...

## Configuration
- **attendance**: 'day' or 'subject' (critical for attendance tracking)
- **auto_invoice_generate**: 0 = disabled, 1 = enabled
- **automation**: Day of month (1-31) for auto-invoicing
- **currency_code**: e.g., 'KES', 'USD'
- **currency_symbol**: e.g., 'KSH', '$'
- **backend_theme**: Theme folder name
- **frontend_theme**: Theme folder name
- **student_ID_format**: ID generation format (1-4)
- **captcha_status**: 0 = enabled, 1 = disabled
- **ex_class**: Comma-separated classIDs to exclude from automation

## Notes for AI Agents
- **Critical Setting: attendance mode** affects entire attendance system
- **Auto-Invoice Dependency**: automation day + auto_invoice_generate control dashboard automation
- **Photo Upload**: Separate do_upload endpoint for logo
- **Theme System**: backend_theme and frontend_theme reference theme folder names
- **Language**: Changes affect entire UI for school
- **Student ID Format**: 4 different formats available
- **ex_class**: Classes excluded from auto-invoice generation
