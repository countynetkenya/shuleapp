# Feature: Test

## Overview
**Controller**: `mvc/controllers/Test.php`  
**Primary Purpose**: Developer testing playground for debugging and experimenting with code.  
**User Roles**: Developer only  
**Status**: 🚧 Development/Debug Tool

## Functionality
- Quick code testing
- Debugging helpers (uses `dd()` dump-and-die)
- API exploration
- Should be disabled in production

## Notes for AI Agents
- **Production**: Should redirect to dashboard or return 404 in production
- **Security**: Remove or protect this controller before deployment
- **Usage**: For developer testing only, not a production feature
- Currently tests payment gateway integration (mpesa)

