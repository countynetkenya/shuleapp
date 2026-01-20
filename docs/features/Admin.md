# Feature: Admin

## Overview
**Controller**: `mvc/controllers/Admin.php`  
**Primary Purpose**: Superadmin panel for managing systemadmins across all schools (schoolID=0 context).  
**User Roles**: Superadmin (usertypeID=0)  
**Status**: ✅ Active

## Core Functionality
- CRUD for systemadmins from superadmin context
- Multi-school assignment to systemadmins
- Photo upload support
- Salary template assignment
- Document management
- View systemadmin profiles

## Key Models
systemadmin_m, school_m, usertype_m, manage_salary_m, salary_template_m, hourly_template_m, make_payment_m, document_m

## Critical Tables
- systemadmin: systemadminID (PK), name, dob, sex, religion, email, phone, address, jod, photo, username, password, usertypeID (1), schoolID (comma-separated), active

## Important Notes
- **Context**: Accessed only by usertypeID=0 (superadmin) with schoolID=0
- **Multi-School**: schoolID field is comma-separated list of assigned schools
- **usertypeID**: Always 1 for systemadmins
- **Difference from Systemadmin controller**: Admin manages systemadmins from superadmin view

## Validation
email: unique across all 5 user tables, username: unique across all 5 user tables, schoolID: optional (can be empty before school creation)

## Dependencies
Depends on: None (superadmin level); Used by: School creation, User management
