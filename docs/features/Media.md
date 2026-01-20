# Feature: Media

## Overview
**Controller**: `mvc/controllers/Media.php`  
**Primary Purpose**: Media file upload and management for various content types.  
**User Roles**: Admin  
**Status**: ✅ Active

## Core Functionality
- Image upload and storage
- Document upload
- File browser/selector
- Thumbnail generation
- File deletion

## Key Models
media_m

## Critical Tables
- media: mediaID, filename, file_path, file_type, uploaded_by, upload_date

## Important Notes
- **Upload Limits**: Max file size configurable
- **File Types**: Images, PDFs, documents
- **Storage**: uploads/ directory

## Validation
File type and size validation

## Dependencies
Depends on: School; Used by: Notice, Event, Pages for content
