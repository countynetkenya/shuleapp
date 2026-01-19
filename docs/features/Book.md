# Feature: Book

## Overview
**Controller**: `mvc/controllers/Book.php`  
**Primary Purpose**: Manages the library book catalog including inventory tracking, pricing, and storage location management  
**User Roles**: Admin, Librarian  
**Status**: ✅ Active

## Functionality
### Core Features
- Book catalog management (add, edit, view, delete)
- Book inventory tracking with quantity and due quantity (issued books)
- Price management for books
- Rack/shelf location tracking
- Author and subject code organization
- School-specific book collections

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | `book/index` | List all books for current school | `book` |
| `add()` | `book/add` | Add new book to catalog | `book_add` |
| `edit()` | `book/edit/{id}` | Edit existing book details | `book_edit` |
| `delete()` | `book/delete/{id}` | Delete book from catalog | `book_delete` |
| `unique_book()` | Callback | Validate unique book combination | - |
| `valid_number()` | Callback | Validate price is non-negative | - |
| `valid_number_for_quantity()` | Callback | Validate quantity is non-negative | - |

## Data Layer
### Models Used
- `book_m` - Book catalog operations

### Database Tables
- `book` - Book records with fields:
  - `bookID` (PK)
  - `book` - Book name/title
  - `author` - Book author
  - `subject_code` - Subject classification code
  - `price` - Book price
  - `quantity` - Total book copies
  - `due_quantity` - Currently issued copies (initialized to 0)
  - `rack` - Rack/shelf location
  - `schoolID` - School identifier

## Validation Rules
1. **book**: Required, max 60 chars, XSS clean, unique combination (book+author+subject_code)
2. **author**: Required, max 100 chars, XSS clean, unique combination
3. **subject_code**: Required, max 20 chars, XSS clean, unique combination
4. **price**: Required, numeric, max 10 chars, must be >= 0
5. **quantity**: Required, numeric, max 10 chars, must be >= 0
6. **rack**: Required, max 60 chars, XSS clean

## Dependencies & Interconnections
### Depends On (Upstream)
- School system (schoolID from session)
- User authentication system

### Used By (Downstream)
- `Issue` - Books are issued to library members
- `Lmember` - Library members borrow books

### Related Features
- **Issue**: Book issuance tracking
- **Lmember**: Library membership management

## User Flows
### Primary Flow: Add New Book
1. Admin navigates to `book/add`
2. Enters book details (name, author, subject code, price, quantity, rack)
3. System validates uniqueness (book+author+subject_code combination)
4. System validates numeric fields (price, quantity >= 0)
5. System sets `due_quantity` to 0 automatically
6. Book saved with schoolID from session
7. Redirect to book index with success message

### Secondary Flow: Edit Book
1. Admin clicks edit on book from index
2. System loads book data (validates schoolID match)
3. Admin updates fields (can change all except due_quantity)
4. System validates updates
5. Book updated, redirect to index

### Quantity Tracking Flow
1. Initial book entry: `quantity` set, `due_quantity` = 0
2. When book issued (via Issue controller): `due_quantity` incremented
3. When book returned: `due_quantity` decremented
4. Available copies = `quantity` - `due_quantity`

## Edge Cases & Limitations
1. **Duplicate Prevention**: Uniqueness checked on combination of book+author+subject_code, not just book name
2. **Quantity Management**: `due_quantity` managed by Issue controller, not directly editable
3. **Negative Values**: Validation prevents negative price/quantity
4. **School Isolation**: Books are strictly filtered by schoolID
5. **Deletion**: No cascade handling mentioned - deleting books with active issues may cause data issues

## Configuration
- Language file: `mvc/language/{lang}/book_lang.php`
- No special configuration needed

## Notes for AI Agents
- **Stock Tracking**: `due_quantity` field is critical - it's automatically managed by the Issue controller when books are issued/returned
- **Uniqueness**: The unique constraint is on THREE fields combined (book+author+subject_code), allowing same title by different authors
- **Price Field**: Present but not used for late fees or fines (those are in Invoice system)
- **Delete Safety**: Consider checking if book has active issues before allowing deletion
- **Rack Management**: Simple text field, no separate rack/shelf master table

