# Feature: Install

## Overview
**Controller**: `mvc/controllers/Install.php`  
**Primary Purpose**: Initial system installation wizard for fresh deployments.  
**User Roles**: Public (first-time setup)  
**Status**: ✅ Active

## Core Functionality
- Database connection setup
- Initial admin account creation
- Default data seeding
- Configuration file generation
- System requirements check

## Key Models
Various setup models

## Critical Tables
Creates all initial tables and default data

## Important Notes
- **One-Time Use**: Should be disabled after installation
- **Security**: Delete or rename after setup
- **Default Data**: Creates initial usertypes, permissions, settings

## Validation
Database credentials, admin credentials

## Dependencies
Depends on: None (fresh install); Used by: First-time setup only
