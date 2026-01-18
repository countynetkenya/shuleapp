# Feature: Student

## Overview
**Controller**: `mvc/controllers/Student.php`  
**Primary Purpose**: Comprehensive student management including enrollment, profiles, attendance, marks, fees, QuickBooks integration, and document management.  
**User Roles**: Admin, Teacher (view own class)  
**Status**: ✅ Active

## Core Functionality
- CRUD for student records with photo upload
- QuickBooks customer sync integration
- Multi-year enrollment via studentrelation table
- Parent assignment
- Class/section assignment per schoolyear
- Group membership
- Extended attributes (studentextend table)
- Invoice and payment tracking
- Mark/exam history
- Attendance tracking (day and subject-based)
- Document uploads
- Email PDF reports
- Leave application history

## Key Models
student_m, parents_m, section_m, classes_m, studentrelation_m, studentgroup_m, studentextend_m, subject_m, invoice_m, payment_m, weaverandfine_m, exam_m, mark_m, document_m, leaveapplication_m, quickbookslog_m (20+ models)

## Critical Tables
- student: Core student data (name, dob, sex, religion, email, phone, photo, username, password, register_no, roll)
- studentrelation: Year-specific enrollment (studentID, classesID, sectionID, schoolyearID, rollno)
- studentextend: Extended attributes (blood_group, state, country, etc.)
- studentgroup: Group memberships

## Important Notes
- **QuickBooks Integration**: Creates/updates QB customers on add/edit
- **Multi-Year Design**: Same student can be in different classes each year via studentrelation
- **Complex Profile View**: Loads 10+ data sections (basic, parent, routine, attendance, marks, invoices, payments, documents)
- **Register Number**: Unique student identifier across years
- **Roll Number**: Class-specific, resets each year via studentrelation

## Validation
register_no: unique across all students and years, email: unique across all 5 user tables, username: unique across all 5 user tables, parentID: must exist in parents table, classesID/sectionID: must exist and match

## Dependencies
Depends on: School, Schoolyear, Classes, Section, Parents, Studentgroup; Used by: Invoice, Payment, Mark, Exam, Attendance, Routine
