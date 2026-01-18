# Feature: Studentgroup

## Overview
**Controller**: `mvc/controllers/Studentgroup.php`  
**Primary Purpose**: Create and manage student groups for organization, bulk operations, and extracurricular activities.  
**User Roles**: Admin  
**Status**: ✅ Active

## Core Functionality
- CRUD for student groups
- Assign multiple students to groups
- Track group membership via studentrelation table

## Key Models
studentgroup_m, studentrelation_m

## Critical Tables
- studentgroup: studentgroupID (PK), group_name, description, schoolID
- studentrelation: groupID field links students to groups

## Important Notes
- **Use Cases**: Clubs, sports teams, academic groups, bus routes, etc.
- **Multi-Group**: Students can belong to multiple groups
- **Bulk Operations**: Groups enable bulk messaging, fee assignment, etc.

## Validation
group_name: required, unique per school

## Dependencies
Depends on: School; Used by: Student assignments, Bulk operations
