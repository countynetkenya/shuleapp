# Feature: Parents

## Overview
**Controller**: `mvc/controllers/Parents.php`  
**Primary Purpose**: Parent/guardian management with child linkage and communication features.  
**User Roles**: Admin  
**Status**: ✅ Active

## Core Functionality
- CRUD for parent/guardian records
- Father and mother information separation
- Link to multiple students (children)
- Document uploads
- Photo upload support
- Email PDF reports

## Key Models
parents_m, student_m, usertype_m, section_m, document_m, studentrelation_m

## Critical Tables
- parents: parentsID (PK), name, father_name, mother_name, father_profession, mother_profession, email, phone, address, photo, username, password, usertypeID (4), schoolID

## Important Notes
- **usertypeID**: Always 4 for parents
- **Multiple Children**: One parent can be linked to multiple students
- **Father/Mother Separation**: Separate fields for both parents' info
- **Login Capability**: Has username/password for parent portal access
- **Document Access**: Can upload/view documents

## Validation
email: unique across all 5 user tables (if provided), username: unique across all 5 user tables, phone: min 5, max 25 characters

## Dependencies
Depends on: School; Used by: Student (parent assignment)
