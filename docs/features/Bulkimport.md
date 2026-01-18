# Feature: Bulkimport

## Overview
**Controller**: `mvc/controllers/Bulkimport.php`  
**Primary Purpose**: CSV/Excel bulk import for students, teachers, parents, subjects, and other entities.  
**User Roles**: Admin  
**Status**: ✅ Active

## Core Functionality
- CSV template downloads for each entity type
- Excel file upload and parsing
- Batch creation with validation
- Error reporting
- Support for: students, teachers, parents, subjects, classes, sections

## Key Models
All entity models (student_m, teacher_m, parents_m, subject_m, classes_m, section_m, etc.)

## Critical Tables
Inserts into respective entity tables based on import type

## Important Notes
- **CSV Templates**: Provides downloadable templates with correct column headers
- **Validation**: Validates each row before insert
- **Error Handling**: Returns list of failed rows with reasons
- **Performance**: Processes large files in batches

## Validation
Per-entity validation rules applied to each row

## Dependencies
Depends on: All entity controllers; Used by: Initial setup, data migration
