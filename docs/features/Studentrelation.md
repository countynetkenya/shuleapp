# Feature: Studentrelation

## Overview
**Model**: `mvc/models/Studentrelation_m.php`  
**Primary Purpose**: Junction table model managing student enrollment in classes/sections per academic year.  
**User Roles**: N/A (data model, no controller)  
**Status**: ✅ Active

## Core Functionality
- Links students to classes and sections per schoolyear
- Stores year-specific roll numbers
- Tracks class and section names (denormalized)
- Enables student promotion across years

## Critical Table
- studentrelation: srID (PK), srstudentID, srclassesID, srclasses (denormalized), srsectionID, srsection (denormalized), srschoolyearID, srrollno, srschoolID

## Important Notes
- **No Controller**: Managed via Student controller
- **Multi-Year**: Same student can have different classes each year
- **Denormalization**: Stores class/section names for performance
- **Promotion**: Moving to next class creates new studentrelation record

## Dependencies
Links: student, classes, section, schoolyear tables
