# Feature: Migration

## Overview
**Controller**: `mvc/controllers/Migration.php`  
**Primary Purpose**: Database migration and schema update management.  
**User Roles**: Systemadmin/Developer  
**Status**: ✅ Active

## Core Functionality
- Run database migrations
- Track migration versions
- Roll back migrations
- Schema updates

## Key Models
migration_m

## Critical Tables
- migrations: id, version, name, executed_at

## Important Notes
- **Version Control**: Tracks which migrations have run
- **Idempotent**: Safe to run multiple times
- **Developer Tool**: Not for end-users

## Validation
Migration version uniqueness

## Dependencies
Depends on: Database; Used by: System updates
