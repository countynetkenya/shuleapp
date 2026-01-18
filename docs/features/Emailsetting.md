# Feature: Emailsetting

## Overview
**Controller**: `mvc/controllers/Emailsetting.php`  
**Primary Purpose**: Configure SMTP email server settings for sending system emails.  
**User Roles**: Admin  
**Status**: ✅ Active

## Core Functionality
- SMTP server configuration (host, port, username, password)
- Email protocol selection (mail, sendmail, smtp)
- Test email functionality
- Encryption settings (SSL/TLS)

## Key Models
emailsetting_m

## Critical Tables
- emailsetting: emailsettingID, email_engine, smtp_host, smtp_port, smtp_username, smtp_password, smtp_secure

## Important Notes
- **Global Setting**: One email config per school
- **Test Feature**: Allows testing config before saving
- **Password Encryption**: SMTP password should be encrypted

## Validation
smtp_host/port/username required if engine is smtp

## Dependencies
Depends on: School; Used by: All email sending features (invoice, reports, notifications)
