# Feature: Systemadmin

## Overview
**Controller**: `mvc/controllers/Systemadmin.php`  
**Primary Purpose**: System administrator self-management within assigned schools (normal school context).  
**User Roles**: Systemadmin (usertypeID=1)  
**Status**: ✅ Active

## Core Functionality
- View/edit own systemadmin profile
- Cannot create new systemadmins (only Admin controller can)
- Photo upload
- Document management
- Salary and payment tracking
- Leave applications

## Key Models
systemadmin_m, usertype_m, manage_salary_m, salary_template_m, hourly_template_m, make_payment_m, document_m

## Critical Tables
- systemadmin: Same as Admin controller but accessed in school context

## Important Notes
- **Self-Service**: Systemadmins manage their own profiles
- **No Creation**: Cannot create other systemadmins (use Admin controller)
- **School Context**: Operates within assigned schools (not global like Admin)

## Validation
Same as Admin controller

## Dependencies
Depends on: School; Used by: User management within schools
