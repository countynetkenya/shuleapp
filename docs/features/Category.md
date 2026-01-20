# Feature: Category

## Overview
**Controller**: `mvc/controllers/Category.php`  
**Primary Purpose**: General categorization system for various entities across the application.  
**User Roles**: Admin  
**Status**: ✅ Active

## Core Functionality
- CRUD for category definitions
- Hierarchical category support
- Used for: expense categories, income categories, asset categories, etc.

## Key Models
category_m

## Critical Tables
- category: categoryID (PK), category_name, parentID, type, schoolID

## Important Notes
- **Multi-Purpose**: Shared across different modules (expenses, income, assets)
- **Hierarchical**: Supports parent-child relationships
- **Type Field**: Differentiates category types (expense vs income vs asset)

## Validation
category_name: required, unique per type and school

## Dependencies
Depends on: School; Used by: Expense, Income, Asset modules
