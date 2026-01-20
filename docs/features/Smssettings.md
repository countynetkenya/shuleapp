# Feature: Smssettings

## Overview
**Controller**: `mvc/controllers/Smssettings.php`  
**Primary Purpose**: Configure SMS gateway settings for sending notifications and alerts.  
**User Roles**: Admin  
**Status**: ✅ Active

## Core Functionality
- SMS gateway provider selection
- API credentials configuration
- Sender ID/From number
- Test SMS functionality

## Key Models
smssettings_m

## Critical Tables
- smssettings: smssettingsID, sms_engine, sms_api_key, sms_from, sms_url

## Important Notes
- **Multiple Providers**: Supports different SMS gateways
- **Kenya Context**: Likely includes Africa's Talking, Safaricom M-Pesa integrations
- **Cost**: SMS sending has cost implications

## Validation
API key and sender ID required

## Dependencies
Depends on: School; Used by: SMS notifications, fee reminders, alerts
