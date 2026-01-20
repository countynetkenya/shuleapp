# Feature: Studentextend

## Overview
**Model**: `mvc/models/Studentextend_m.php`  
**Primary Purpose**: Extended student attributes not in core student table (blood group, state, country, etc.).  
**User Roles**: N/A (data model, no controller)  
**Status**: ✅ Active

## Core Functionality
- Stores supplementary student information
- Optional fields not required for basic enrollment
- Enables custom student attributes

## Critical Table
- studentextend: studentextendID (PK), studentID, blood_group, state, country, nationality, transport_route, hostel_name, library_card_no

## Important Notes
- **No Controller**: Managed via Student controller
- **Optional Data**: All fields optional
- **Extensions**: Can add custom fields here without affecting core student table

## Dependencies
Links to: student table (studentID FK)
