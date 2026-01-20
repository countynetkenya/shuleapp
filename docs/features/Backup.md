# Feature: Backup

## Overview
**Controller**: `mvc/controllers/Backup.php`  
**Primary Purpose**: Database backup utility to export complete database as downloadable SQL file.  
**User Roles**: Superadmin only (highly sensitive operation)  
**Status**: ✅ Active

## Functionality
- **Database Export**: Creates full database dump in ZIP format
- **Download**: Forces browser download of backup file
- **Memory Management**: Sets high memory limit for large databases

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | backup/index | backup | Generate and download database backup |

## Data Layer
- Uses CodeIgniter's `dbutil` library to dump all tables
- No database writes - read-only operation

## Configuration
- Memory limit: 1024M for large database exports
- Output format: ZIP containing SQL file
- Filename: mybackup.zip/mybackup.sql
- Demo mode: Disabled in demo environments

## Notes for AI Agents
- **Security Critical**: Only superadmin should access this
- **Memory Intensive**: Large databases may timeout or exhaust memory
- **No Restore**: This controller only backs up, restore must be done manually via phpMyAdmin or CLI
- **Production Use**: Recommend cron job for automated backups instead of manual
- **File Storage**: Backup not saved on server, only downloaded to user's machine
- **Demo Protection**: Disabled when `config_item('demo') == TRUE`

