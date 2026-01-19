# Phase 6: Library & Inventory Features - Documentation Summary

**Date**: 2024-01-19  
**Total Features Documented**: 10  
**Categories**: Library Management (3), Inventory Management (7)

---

## Library Management System

### 1. Book (`Book.php`)
**Purpose**: Library book catalog and inventory management

**Key Features**:
- Book catalog with author, subject code, pricing
- Quantity tracking (total vs. issued/due)
- Rack/shelf location management
- School-specific collections

**Critical Fields**:
- `quantity`: Total book copies owned
- `due_quantity`: Currently issued copies (auto-managed by Issue controller)
- Available = `quantity - due_quantity`

**Database**: `book` table

**Relationships**:
- Used by: Issue (for book borrowing)
- Integrates with: Lmember (library members borrow books)

---

### 2. Issue (`Issue.php`)
**Purpose**: Book issuance and return tracking with fine management

**Key Features**:
- Book checkout to library members
- Due date tracking
- Return date recording
- Fine/penalty invoice generation
- Serial number tracking for book copies
- Multi-role access (Admin, Student, Parent views)
- PDF and email reporting

**Critical Logic**:
- On Issue: `book.due_quantity++`
- On Return: `book.due_quantity--`, set `return_date`
- Active issues: `return_date IS NULL`

**Database**: `issue` table

**Workflows**:
1. Issue: Validate book availability → Create issue → Update book quantity
2. Return: Set return_date → Decrement book quantity
3. Fine: Generate invoice for overdue books

**Note**: Fine amount manually entered by admin, not auto-calculated

---

### 3. Lmember (`Lmember.php`)
**Purpose**: Library membership management for students

**Key Features**:
- Member registration with unique library ID (lID)
- Membership fee tracking (lbalance)
- Auto-population from student records
- Member card generation (PDF/Email)
- Class-based organization

**Library ID Generation**:
- First member: `YEAR01` (e.g., 202401)
- Subsequent: Auto-increment from last ID
- Can be manually edited but must be unique

**Critical Fields**:
- `lID`: Unique library member ID
- `studentID`: Links to student record
- `lbalance`: Membership fee
- `student.library`: Flag (1=member, 0=not member)

**Database**: `lmember` table

**Relationships**:
- Depends on: Student, Classes, Section
- Used by: Issue (members borrow books)

---

## Inventory Management System

### 4. Product (`Product.php`)
**Purpose**: Product catalog management with pricing and categorization

**Key Features**:
- Product catalog with buying/selling prices
- Category organization
- Product descriptions
- Warehouse-specific stock viewing
- Transaction history (purchases, sales, adjustments, movements)
- Date range and monthly filtering

**Database**: `product` table

**Critical Fields**:
- `productbuyingprice`: Cost/purchase price
- `productsellingprice`: Selling price
- `productcategoryID`: Required category
- `create_date`, `modify_date`: Audit trail

**Relationships**:
- Depends on: Productcategory (required)
- Used by: Productpurchase, Productsale, Stock

---

### 5. Productcategory (`Productcategory.php`)
**Purpose**: Product categorization for inventory organization

**Key Features**:
- Simple name and description structure
- School-specific categories
- Timestamp tracking

**Database**: `productcategory` table

**Note**: Should validate no products exist before allowing deletion

---

### 6. Productsupplier (`Productsupplier.php`)
**Purpose**: Supplier/vendor information management

**Key Features**:
- Company and contact person tracking
- Contact information (email, phone, address)
- School-specific suppliers

**Database**: `productsupplier` table

**Critical Fields**:
- `productsuppliercompanyname`: Unique within school
- `productsuppliername`: Contact person
- Email, phone, address optional

**Used by**: Productpurchase (purchase orders)

---

### 7. Productwarehouse (`Productwarehouse.php`)
**Purpose**: Warehouse/storage location management

**Key Features**:
- Warehouse naming with unique codes
- Contact information per warehouse
- Physical address tracking
- Multi-location support

**Database**: `productwarehouse` table

**Critical Fields**:
- `productwarehousecode`: Unique business key
- `productwarehousename`: Warehouse name
- Dual uniqueness: code AND name+code combination

**Used by**: Product, Stock, Productpurchase, Productsale

---

### 8. Stock (`Stock.php`)
**Purpose**: Unified stock adjustment and transfer management

**Key Features**:
- Stock adjustments (single warehouse)
- Stock transfers (between warehouses)
- Batch product processing
- Approval workflow
- Transaction history

**Database**: `mainstock` (headers), `stock` (items)

**Transaction Types**:
- `adjustment`: Single warehouse, positive or negative quantities
- `movement`: Between warehouses, positive quantities only

**Approval**: Optional step, users cannot approve own transactions

---

### 9. Inventory_adjustment_memo (`Inventory_adjustment_memo.php`)
**Purpose**: Dedicated interface for stock adjustments

**Key Features**:
- Focused UI for adjustments only
- Memo/reason tracking
- Available stock display
- Batch product entry

**Similar to**: Stock controller adjust() method, but dedicated UI

**Quantity Convention**:
- Positive = stock increase (found items, corrections up)
- Negative = stock decrease (loss, damage, theft, corrections down)

**Database**: Same as Stock (mainstock, stock tables)

**Type Filter**: Shows only `type='adjustment'` records

---

### 10. Inventory_transfer_memo (`Inventory_transfer_memo.php`)
**Purpose**: Dedicated interface for inter-warehouse transfers

**Key Features**:
- Focused UI for transfers only
- Source and destination warehouse tracking
- Memo/reason tracking
- Available stock display at source
- Batch product entry

**Similar to**: Stock controller move() method, but dedicated UI

**Validation**:
- Source ≠ Destination warehouse
- Positive quantities only (direction via from/to)

**Database**: Same as Stock (mainstock, stock tables)

**Type Filter**: Shows only `type='movement'` records

**Impact**: Decreases source, increases destination (net inventory unchanged)

---

## Common Patterns Across Features

### Security & Data Isolation
- All features filter by `schoolID` from session
- School-based data isolation strictly enforced
- User audit trails (creator tracking)

### Validation Standards
- XSS clean on all inputs
- Uniqueness checks at school level
- Numeric validation with max lengths
- Email format validation where applicable

### School Year Restrictions
- Library: Add/Edit/Delete restricted to current year (except superadmin)
- Inventory: No year restrictions (operational data)

### Timestamp Tracking
- `create_date`: Record creation timestamp
- `modify_date`: Last modification timestamp
- User tracking: `create_userID`, `create_usertypeID`

### Approval Workflows
- Stock/Adjustment/Transfer: Optional approval
- Self-approval prevented
- No enforcement (transactions effective immediately)

---

## Integration Points & Data Flow

### Library System Flow
```
Student → Lmember (registration) → Issue (borrow) → Book (quantity tracking) → Invoice (fines)
```

### Inventory System Flow
```
Supplier → Productpurchase → Productwarehouse (storage)
Product → Stock/Adjustments/Transfers → Productsale
```

### Key Relationships
1. **Book ↔ Issue**: Quantity synchronization critical
2. **Student ↔ Lmember**: Library flag management
3. **Product ↔ Warehouse**: Multi-warehouse inventory
4. **Stock ↔ Product**: Stock level calculations

---

## Critical Notes for AI Agents

### Library System
1. **Book Quantities**: `due_quantity` is auto-managed, never edit directly
2. **Uniqueness**: Book unique on (book+author+subject_code) combination
3. **Return Logic**: NULL return_date = active issue
4. **Fine Generation**: Manual process, not automatic
5. **Parent View**: Has disabled code (`&& 0 == 1`) - may not work fully

### Inventory System
1. **Stock Validation**: No checks for negative inventory or insufficient stock
2. **Duplicate Controllers**: Stock has adjust/move, AND separate Inventory_adjustment_memo/Inventory_transfer_memo exist
3. **Warehouse Code**: Primary business key, must be unique
4. **Approval Optional**: Transactions effective immediately, approval is audit-only
5. **JSON Submission**: Product items submitted as JSON arrays via AJAX

### Delete Safety Concerns
- Book: Check for active issues before deletion
- Lmember: Check for active issues before deletion
- Product: Check for inventory transactions before deletion
- Productcategory: Check for products using category
- Productsupplier: Check for purchase orders
- Productwarehouse: Check for inventory before deletion

---

## Documentation Files Created

| Feature | File | Lines |
|---------|------|-------|
| Book | `docs/features/Book.md` | 106 |
| Issue | `docs/features/Issue.md` | 147 |
| Lmember | `docs/features/Lmember.md` | 133 |
| Stock | `docs/features/Stock.md` | 136 |
| Product | `docs/features/Product.md` | 141 |
| Productcategory | `docs/features/Productcategory.md` | 83 |
| Productsupplier | `docs/features/Productsupplier.md` | 93 |
| Productwarehouse | `docs/features/Productwarehouse.md` | 104 |
| Inventory_adjustment_memo | `docs/features/Inventory_adjustment_memo.md` | 141 |
| Inventory_transfer_memo | `docs/features/Inventory_transfer_memo.md` | 148 |

**Total**: 1,232 lines of comprehensive documentation

---

## Completion Status

✅ **All Phase 6 features documented**
- 3 Library features
- 7 Inventory features
- Comprehensive analysis of controllers
- Detailed data flow documentation
- Integration points identified
- Critical warnings for AI agents included

**Previous Phases Completed**:
- Phase 1: 30 features
- Phase 2: 23 features
- Phase 3: 25 features
- Phase 4: 14 features
- **Phase 6: 10 features** ✅

**Total Features Documented**: 102 features
