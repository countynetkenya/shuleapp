# Feature: Divisions

## Overview
**Controller**: `mvc/controllers/Divisions.php`  
**Primary Purpose**: Manage school divisions (Primary, Secondary, High School, etc.) for class organization.  
**User Roles**: Admin  
**Status**: ✅ Active

## Core Functionality
- CRUD for division definitions
- Classes grouped by division
- Hierarchical school structure

## Key Models
divisions_m, classes_m

## Critical Tables
- divisions: divisionsID (PK), divisions (name), note, schoolID

## Important Notes
- **Class Organization**: All classes belong to a division
- **Kenya Context**: Primary (Std 1-8), Secondary (Form 1-4)
- **Denormalized**: Classes table stores division name for performance

## Validation
divisions: required, unique per school

## Dependencies
Depends on: School; Used by: Classes (divisionID reference)
