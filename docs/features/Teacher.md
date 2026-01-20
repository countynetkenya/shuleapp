# Feature: Teacher

## Overview
**Controller**: `mvc/controllers/Teacher.php`  
**Primary Purpose**: Teacher management with salary tracking, attendance, document uploads, and comprehensive profile viewing.  
**User Roles**: Admin  
**Status**: ✅ Active

## Core Functionality
- CRUD for teacher records with photo upload
- Salary template assignment (monthly or hourly)
- Attendance tracking (tattendance)
- Payment history
- Document uploads/downloads
- Leave application history
- Email PDF reports
- Subject assignments (via subjectteacher table)
- Class teacher assignments

## Key Models
teacher_m, manage_salary_m, salary_template_m, hourly_template_m, salaryoption_m, make_payment_m, tattendance_m, document_m, leaveapplication_m, routine_m, subject_m, exam_m, mark_m

## Critical Tables
- teacher: teacherID (PK), name, designation, dob, sex, religion, email, phone, address, jod, photo, username, password, schoolID
- tattendance: Teacher attendance records
- subjectteacher: Many-to-many teacher-subject relationships

## Important Notes
- **usertypeID**: Always 2 for teachers
- **Salary Integration**: Links to manage_salary for salary/hourly rate assignment
- **Class Teacher**: Can be assigned as class teacher in classes table
- **Subject Teacher**: Can teach multiple subjects via subjectteacher table
- **Attendance**: Separate tattendance table for teacher attendance
- **Username**: Must be unique across all 5 user tables

## Validation
email: unique across all 5 user tables, username: unique across all 5 user tables, designation: required, dob/jod: date format dd-mm-yyyy

## Dependencies
Depends on: School; Used by: Classes (class teacher), Subject (subject teacher), Routine, Attendance
