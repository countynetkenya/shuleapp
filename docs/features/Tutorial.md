# Feature: Tutorial

## Overview
**Controller**: `mvc/controllers/Tutorial.php`  
**Primary Purpose**: E-learning module for uploading and managing video tutorials and lesson materials organized by class, subject, and lessons.  
**User Roles**: Admin, Teacher (create), Student (view)  
**Status**: ✅ Active

## Functionality
- **Tutorial CRUD**: Create, edit, delete video tutorials
- **Organization**: By class, section, subject, lesson
- **Video Upload**: Support for video files or external links (YouTube, Vimeo)
- **Public/Private**: Control visibility (public vs. class-specific)
- **File Management**: Upload tutorial materials, PDFs, documents

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | tutorial/index | tutorial | List all tutorials |
| `add()` | tutorial/add | tutorial_add | Create tutorial |
| `edit()` | tutorial/edit/{id}` | tutorial_edit | Update tutorial |
| `view()` | tutorial/view/{id}` | tutorial_view | View tutorial |
| `delete()` | tutorial/delete/{id}` | tutorial_delete | Delete tutorial |

## Data Layer
- `tutorial` - Tutorial records (title, classesID, sectionID, subjectID, lessonID, is_public, video_url, schoolyearID)
- `lesson` - Lesson organization

## Validation Rules
- **title**: Required, max 255 chars
- **classesID**: Required, valid class
- **subjectID**: Required, valid subject
- **video_url**: Optional URL or file upload
- **is_public**: 0=class-only, 1=all students

## Configuration
- Memory limit: 1024M
- Max upload: 20480M (20GB for large video files)

## Notes for AI Agents
- Supports both uploaded videos and external links (YouTube/Vimeo)
- Large file upload configuration for video content
- Can be scoped to specific class/section or made public
- Integrates with Lesson module for curriculum organization

