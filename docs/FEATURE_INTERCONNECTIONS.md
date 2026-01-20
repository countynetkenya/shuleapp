# Feature Interconnection Map

**Shuleapp School Management System**  
**Last Updated**: January 2025  
**Total Features Documented**: 199  
**Purpose**: Guide for AI agents and developers to understand system architecture, dependencies, and feature relationships.

---

## Table of Contents
1. [Dependency Layers](#dependency-layers)
2. [Feature Clusters](#feature-clusters)
3. [Cross-Cluster Dependencies](#cross-cluster-dependencies)
4. [Critical Dependency Chains](#critical-dependency-chains)
5. [Integration Points](#integration-points)
6. [Notes for AI Agents](#notes-for-ai-agents)

---

## Dependency Layers

The system is organized into 5 architectural layers from foundation to specialized features:

### Layer 0: Foundation (System Setup)
**Purpose**: Initial installation, database setup, and system updates.  
**Features**:
- **Install** - Database creation, initial admin setup
- **Migration** - Schema version control and updates
- **Update** - System version upgrades
- **Backup** - Database backup/restore

**Critical Notes**:
- Must run BEFORE any other features
- Install creates initial admin (usertypeID=1, systemadmin)
- Migration system tracks schema versions
- All other features depend on this layer

---

### Layer 1: Core Infrastructure (Identity & Access)
**Purpose**: Multi-tenant structure, user management, and permissions.  
**Features**:

#### School Management
- **School** - Multi-tenant school instances
- **Schoolyear** - Academic year periods
- **Schoolterm** - Term/semester divisions within years

#### User Identity
- **User** - Staff/admin users (usertypeID >= 5)
- **Teacher** - Teaching staff (usertypeID=2)
- **Student** - Students (usertypeID=3)
- **Parents** - Parent/guardian accounts (usertypeID=4)
- **Admin** - System administrators (usertypeID=1)

#### Access Control
- **Usertype** - Role definitions (1=Admin, 2=Teacher, 3=Student, 4=Parent, 5+=Staff)
- **Permission** - Granular permission assignments per usertype
- **Permissionlog** - Audit trail of permission changes

#### Authentication
- **Signin** - Login system
- **Reset/Resetpassword** - Password recovery
- **Register** - New user registration
- **Profile** - User profile management

**Critical Dependencies**:
- **School** is referenced by almost ALL features via `schoolID`
- **Schoolyear** provides temporal context (current year filter)
- **Permission** system controls ALL feature access
- **Usertype** defines role capabilities

**Notes for AI Agents**:
- NEVER modify Layer 1 features without understanding full impact
- Schoolyear changes affect ALL student/class/exam/invoice data
- Permission changes affect system-wide access control

---

### Layer 2: Primary Entities (Core Academic Structure)
**Purpose**: Core academic and organizational entities.  
**Features**:

#### Academic Structure
- **Classes** - Grade levels/forms (e.g., Form 1, Grade 5)
- **Section** - Class divisions (e.g., Form 1A, Form 1B)
- **Subject** - Taught subjects
- **Divisions** - Academic divisions/departments
- **Syllabus** - Curriculum content
- **Routine** - Class schedules/timetables

#### Relationships
- **Studentrelation** - Student-to-class-to-year mapping (multi-year enrollment)
- **Studentgroup** - Student groupings
- **Studentextend** - Extended student attributes

#### Communication
- **Notice** - School announcements
- **Event** - Calendar events
- **Message/Conversation** - Internal messaging
- **Mailandsms** - Bulk messaging
- **Mailandsmstemplate** - Message templates

#### Administrative
- **Complain** - Complaint management
- **Visitorinfo** - Visitor tracking
- **Holiday** - Holiday calendar

**Critical Dependencies**:
- **Classes** depends on: Teacher (class teacher), Divisions
- **Section** depends on: Classes
- **Subject** depends on: Classes, Teacher (subject teacher)
- **Studentrelation** depends on: Student, Classes, Section, Schoolyear
- **Routine** depends on: Classes, Section, Subject, Teacher

**Notes**:
- Student enrollment is MULTI-YEAR via studentrelation table
- Same student can be in different classes each schoolyear
- Classes/Section/Subject form the academic hierarchy

---

### Layer 3: Operations (Daily Activities & Transactions)
**Purpose**: Day-to-day school operations and transactions.  
**Features**:

#### Financial Operations
- **Invoice** - Student fee invoicing
- **Payment** - Fee payment processing
- **Make_payment** - Online payment gateway
- **Creditmemo** - Credit adjustments
- **Feetypes** - Fee type definitions
- **Bundlefeetypes** - Bundled fee packages
- **Fees_balance_tier** - Fee balance tracking
- **Credittypes** - Credit type definitions
- **Paymenttypes** - Payment method types
- **Income** - General income tracking
- **Expense** - Expense tracking

#### Academic Operations
- **Exam** - Exam definitions
- **Exam_type** - Exam categories (midterm, final, etc.)
- **Mark** - Individual student marks
- **Grade** - Grading scales
- **Markpercentage** - Mark percentage calculations
- **Marksetting** - Mark entry settings
- **Examschedule** - Exam timetables
- **Examcompilation** - Exam result compilation
- **Examranking** - Student ranking
- **Assignment** - Homework/assignments
- **Take_exam** - Student exam-taking interface
- **Online_exam** - Online exam system
- **Question_bank** - Exam question repository
- **Question_group** - Question organization
- **Question_level** - Question difficulty levels
- **Question_type** - Question type definitions

#### Attendance Tracking
- **Sattendance** - Student attendance (daily)
- **Tattendance** - Teacher attendance
- **Eattendance** - Exam attendance
- **Uattendance** - User attendance (staff)

#### HR Operations
- **Leaveapplication** - Leave requests (teachers/staff)
- **Leaveapply** - Leave application workflow
- **Leaveassign** - Leave quota assignment
- **Leavecategory** - Leave type definitions
- **Overtime** - Overtime tracking
- **Manage_salary** - Salary management
- **Salary_template** - Salary structure templates
- **Hourly_template** - Hourly rate templates

#### Library Operations
- **Book** - Library book catalog
- **Issue** - Book issue/return management
- **Lmember** - Library member management

#### Hostel Operations
- **Hostel** - Hostel/dormitory management
- **Hmember** - Hostel member assignments
- **Category** - Hostel room categories

#### Inventory Operations
- **Product** - Product catalog
- **Productcategory** - Product categorization
- **Productwarehouse** - Warehouse management
- **Productpurchase** - Purchase orders
- **Productsale** - Product sales
- **Productsupplier** - Supplier management
- **Stock** - Inventory stock levels
- **Inventory_adjustment_memo** - Stock adjustments
- **Inventory_transfer_memo** - Stock transfers
- **Inventoryinvoice** - Inventory invoicing
- **Purchase** - General purchases
- **Vendor** - Vendor management

#### Asset Management
- **Asset** - Fixed asset tracking
- **Asset_category** - Asset categorization
- **Asset_assignment** - Asset assignment to users

#### Transport
- **Transport** - Transport route management
- **Tmember** - Transport member assignments

#### Childcare
- **Childcare** - Childcare service management

#### Activities
- **Activities** - School activities/events
- **Activitiescategory** - Activity categorization

#### Admissions
- **Onlineadmission** - Online admission system
- **Fonlineadmission** - Frontend admission portal
- **Candidate** - Admission candidates

#### Sponsorship
- **Sponsor** - Sponsor management
- **Sponsorship** - Sponsorship assignments

**Critical Dependencies**:
- **Invoice** depends on: Student, Classes, Section, Feetypes, Schoolterm
- **Payment** depends on: Invoice, Student
- **Exam** depends on: Classes, Subject, Schoolyear
- **Mark** depends on: Exam, Student, Subject
- **Sattendance** depends on: Student, Classes, Section, Subject
- **Leaveapplication** depends on: Teacher/User, Leavecategory

**Notes**:
- Financial features heavily integrated with QuickBooks and Mpesa
- Exam system has complex dependency chain: Exam → Mark → Grade → Reports
- Attendance tracking spans students, teachers, and staff

---

### Layer 4: Reporting & Integrations (Analysis & External Systems)
**Purpose**: Data reporting, analytics, and third-party integrations.  
**Features**:

#### Student Reports
- **Studentreport** - General student reports
- **Studentexamreport** - Individual student exam results
- **Studentmeritlistreport** - Merit list/honor roll
- **Studentfinereport** - Fine/penalty reports
- **Studentsessionreport** - Session-wise reports
- **Studentmasterrecord** - Comprehensive student record
- **Student_statement** - Financial statement
- **Progresscardreport** - Progress cards/report cards
- **Idcardreport** - Student ID cards
- **Admitcardreport** - Exam admit cards
- **Certificatereport** - Certificates

#### Teacher Reports
- **Teacherexamreport** - Teacher-specific exam reports
- **Teachermeritlistreport** - Teacher class merit lists

#### Class Reports
- **Classesreport** - Class-level reports
- **Classmeritlistreport** - Class merit rankings
- **Tabulationsheetreport** - Exam tabulation sheets
- **Meritstagereport** - Merit stage analysis

#### Financial Reports
- **Feesreport** - Fee collection reports
- **Balancefeesreport** - Outstanding fee balances
- **Duefeesreport** - Due fees analysis
- **Searchpaymentfeesreport** - Payment search
- **Accountledgerreport** - Accounting ledger
- **Transactionreport** - Transaction history
- **Transactionsummary** - Transaction summaries
- **Salaryreport** - Salary disbursement reports
- **Salereport** - Sales reports
- **Purchasereport** - Purchase reports
- **Productpurchasereport** - Product purchase analysis
- **Productsalereport** - Product sales analysis
- **Stockreport** - Inventory stock reports
- **Variancereport** - Variance analysis
- **Terminalreport** - Terminal/POS reports

#### Attendance Reports
- **Attendancereport** - Attendance analysis
- **Attendanceoverviewreport** - Attendance overview

#### Exam Reports
- **Examschedulereport** - Exam schedule reports
- **Marksheetreport** - Mark sheets
- **Onlineexamreport** - Online exam reports
- **Onlineexamquestionreport** - Online exam questions
- **Onlineexamquestionanswerreport** - Exam answers

#### HR Reports
- **Leaveapplicationreport** - Leave application tracking
- **Overtimereport** - Overtime analysis

#### Library Reports
- **Librarybooksreport** - Book catalog reports
- **Librarybookissuereport** - Book issue/return tracking
- **Librarycardreport** - Library card generation

#### Admission Reports
- **Onlineadmissionreport** - Admission statistics

#### Sponsorship Reports
- **Sponsorshipreport** - Sponsorship tracking

#### Routine Reports
- **Routinereport** - Timetable reports

#### External Integrations
- **Quickbooks** - QuickBooks Online integration
- **Quickbookssettings** - QB configuration
- **Mpesa** - M-Pesa mobile money integration
- **Safaricom** - Safaricom API integration
- **Paymentsettings** - Payment gateway configuration

#### System Reports
- **Dashboard** - Main dashboard with KPIs

**Critical Dependencies**:
- ALL reports depend on their respective operational features
- Reports are READ-ONLY views of operational data
- QuickBooks sync affects: Invoice, Payment, Student (as customers)
- Mpesa integration affects: Payment, Invoice

**Notes**:
- Reports should NEVER modify underlying data
- QuickBooks integration creates QB customers from students
- Mpesa provides mobile payment processing

---

## Feature Clusters

Features are organized into functional clusters based on business domains:

### 1. Academic Cluster
**Primary Flow**: Exam System

```
┌─────────────────────────────────────────────────────────┐
│                   ACADEMIC CLUSTER                       │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  Classes → Section → Subject → Routine                  │
│     ↓         ↓         ↓                                │
│  Student ─────┴─────────┴──→ Studentrelation            │
│     ↓                             ↓                      │
│     └────→ Exam_type → Exam → Examschedule              │
│                 ↓                                        │
│              Mark ──→ Grade                              │
│                 ↓                                        │
│           Markpercentage → Examcompilation              │
│                 ↓                                        │
│            Examranking                                   │
│                 ↓                                        │
│          ┌──────┴──────┐                                │
│          ↓             ↓                                 │
│    Studentexamreport  Marksheetreport                   │
│    Progresscardreport Tabulationsheetreport             │
│    Certificatereport  Admitcardreport                   │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

**Key Features**:
- Classes, Section, Subject, Routine
- Student, Studentrelation, Studentgroup, Studentextend
- Exam, Exam_type, Mark, Grade, Markpercentage, Marksetting
- Examschedule, Examcompilation, Examranking
- Assignment, Syllabus
- Online_exam, Take_exam, Question_bank, Question_group, Question_level, Question_type

**Dependencies**:
- Requires: Schoolyear (context), Teacher (subject teachers, class teachers)
- Used by: All exam-related reports

---

### 2. Financial Cluster
**Primary Flow**: Invoice → Payment

```
┌─────────────────────────────────────────────────────────┐
│                  FINANCIAL CLUSTER                       │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  Feetypes → Bundlefeetypes                              │
│     ↓              ↓                                     │
│  Student ─────────┴──→ Invoice ←─ Schoolterm            │
│     ↓                     ↓                              │
│     └──────→ Payment ←────┘                             │
│                ↓                                         │
│          Make_payment (Gateway)                          │
│                ↓                                         │
│     ┌──────────┴──────────┐                             │
│     ↓                     ↓                              │
│  Mpesa/Safaricom    Quickbooks                          │
│     ↓                     ↓                              │
│  Creditmemo         Paymenthistory                       │
│     ↓                     ↓                              │
│  Student_statement  Fees_balance_tier                    │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

**Key Features**:
- Invoice, Payment, Make_payment
- Feetypes, Bundlefeetypes, Fees_balance_tier
- Creditmemo, Credittypes, Paymenttypes
- Student_statement, Paymenthistory
- Income, Expense
- Quickbooks, Quickbookssettings (integration)
- Mpesa, Safaricom (integration)
- Paymentsettings (gateway config)

**Reports**:
- Feesreport, Balancefeesreport, Duefeesreport
- Searchpaymentfeesreport, Accountledgerreport
- Transactionreport, Transactionsummary

**Dependencies**:
- Requires: Student, Classes, Section, Schoolterm
- Integrates with: QuickBooks Online, M-Pesa, Payment gateways

---

### 3. HR Cluster
**Primary Flow**: Leave & Salary Management

```
┌─────────────────────────────────────────────────────────┐
│                     HR CLUSTER                           │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  Teacher/User                                            │
│       ↓                                                  │
│  Tattendance / Uattendance                              │
│       ↓                                                  │
│  Leavecategory → Leaveassign                            │
│       ↓                ↓                                 │
│  Leaveapply ──→ Leaveapplication                        │
│                       ↓                                  │
│                Leaveapplicationreport                    │
│                                                          │
│  Salary_template / Hourly_template                       │
│       ↓                                                  │
│  Manage_salary ──→ Salaryreport                         │
│                                                          │
│  Overtime ──→ Overtimereport                            │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

**Key Features**:
- Tattendance, Uattendance
- Leaveapplication, Leaveapply, Leaveassign, Leavecategory
- Manage_salary, Salary_template, Hourly_template
- Overtime

**Reports**:
- Leaveapplicationreport, Overtimereport, Salaryreport

**Dependencies**:
- Requires: Teacher, User, Schoolyear

---

### 4. Library Cluster
**Primary Flow**: Book → Issue

```
┌─────────────────────────────────────────────────────────┐
│                   LIBRARY CLUSTER                        │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  Book (Catalog)                                          │
│    ↓                                                     │
│  Lmember (Student/Teacher/User)                         │
│    ↓                                                     │
│  Issue (Book checkout/return)                           │
│    ↓                                                     │
│  Librarybookissuereport                                 │
│  Librarybooksreport                                     │
│  Librarycardreport                                      │
│                                                          │
│  Ebooks (Digital library)                               │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

**Key Features**:
- Book, Issue, Lmember, Ebooks

**Reports**:
- Librarybooksreport, Librarybookissuereport, Librarycardreport

**Dependencies**:
- Requires: Student, Teacher, User (library members)

---

### 5. Inventory Cluster
**Primary Flow**: Product → Purchase/Sale → Stock

```
┌─────────────────────────────────────────────────────────┐
│                  INVENTORY CLUSTER                       │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  Productcategory → Product                              │
│                      ↓                                   │
│  Productsupplier → Productpurchase                      │
│                      ↓                                   │
│  Productwarehouse → Stock                               │
│                      ↓                                   │
│                 Productsale                              │
│                      ↓                                   │
│       Inventory_adjustment_memo                          │
│       Inventory_transfer_memo                            │
│                      ↓                                   │
│                Inventoryinvoice                          │
│                      ↓                                   │
│     ┌───────────────┴───────────────┐                   │
│     ↓                               ↓                    │
│  Stockreport              Productpurchasereport          │
│  Productsalereport        Purchasereport                 │
│  Salereport               Variancereport                 │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

**Key Features**:
- Product, Productcategory, Productwarehouse
- Productpurchase, Productsale, Productsupplier
- Stock, Inventory_adjustment_memo, Inventory_transfer_memo
- Inventoryinvoice, Purchase, Vendor

**Reports**:
- Stockreport, Productpurchasereport, Productsalereport
- Purchasereport, Salereport, Variancereport

**Dependencies**:
- Requires: Schoolyear, User (for transactions)

---

### 6. Asset Cluster

```
┌─────────────────────────────────────────────────────────┐
│                    ASSET CLUSTER                         │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  Asset_category → Asset                                 │
│                     ↓                                    │
│              Asset_assignment                            │
│                  (to User)                               │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

---

### 7. Hostel Cluster

```
┌─────────────────────────────────────────────────────────┐
│                   HOSTEL CLUSTER                         │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  Category (Room types)                                   │
│      ↓                                                   │
│  Hostel (Dormitory)                                     │
│      ↓                                                   │
│  Hmember (Student assignment)                           │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

---

### 8. Transport Cluster

```
┌─────────────────────────────────────────────────────────┐
│                  TRANSPORT CLUSTER                       │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  Transport (Routes)                                      │
│      ↓                                                   │
│  Tmember (Student assignment)                           │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

---

### 9. Communication Cluster

```
┌─────────────────────────────────────────────────────────┐
│                COMMUNICATION CLUSTER                     │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  Notice, Event, Alert                                    │
│      ↓                                                   │
│  Mailandsmstemplate → Mailandsms                        │
│      ↓                                                   │
│  Conversation (Internal messaging)                       │
│      ↓                                                   │
│  Emailsetting, Smssettings                              │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

---

### 10. Frontend/Portal Cluster

```
┌─────────────────────────────────────────────────────────┐
│                 FRONTEND CLUSTER                         │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  Frontend, Frontend_setting                              │
│      ↓                                                   │
│  Frontendmenu, Pages, Posts, Posts_categories           │
│      ↓                                                   │
│  Fonlineadmission (Public admission)                    │
│  Onlineadmission (Admin side)                           │
│      ↓                                                   │
│  Sociallink, Media                                       │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

---

### 11. System Administration Cluster

```
┌─────────────────────────────────────────────────────────┐
│              SYSTEM ADMIN CLUSTER                        │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  Systemadmin, Setting                                    │
│      ↓                                                   │
│  Backup, Update, Migration                              │
│      ↓                                                   │
│  Menu, Language, Location                               │
│      ↓                                                   │
│  License, Addons                                         │
│      ↓                                                   │
│  Certificate_template, Instruction                       │
│      ↓                                                   │
│  Bulkimport, Media                                       │
│      ↓                                                   │
│  Tutorial, Privacy                                       │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

---

## Cross-Cluster Dependencies

### Student as Central Hub
**Student** is the most interconnected entity in the system:

```
                    ┌─────────────┐
                    │   Student   │
                    └──────┬──────┘
                           │
        ┌──────────────────┼──────────────────┐
        ↓                  ↓                  ↓
   ACADEMIC            FINANCIAL              HR
   - Classes           - Invoice          - Sattendance
   - Section           - Payment          - Leaveapply
   - Exam              - Make_payment
   - Mark              - Creditmemo        LIBRARY
   - Assignment        - Student_statement - Lmember
   - Routine                               - Issue
   - Online_exam       HOSTEL
                       - Hmember            TRANSPORT
   REPORTS                                  - Tmember
   - 20+ student reports  CHILDCARE
                       - Childcare
```

**Impact of Student Changes**:
- Deleting a student affects: invoices, payments, marks, attendance, library records, hostel assignments, transport assignments
- Modifying student class affects: all class-based reports and data queries
- Student register_no is unique identifier across the system

---

### Schoolyear as Context Provider
**Schoolyear** provides temporal context for almost all data:

```
                  ┌──────────────┐
                  │  Schoolyear  │
                  └──────┬───────┘
                         │
       ┌─────────────────┼─────────────────┐
       ↓                 ↓                 ↓
  ENROLLMENT        ACADEMIC           FINANCIAL
  - Studentrelation  - Exam            - Invoice
  - Classes          - Mark            - Payment
  - Section          - Examschedule    - Schoolterm
                     - Assignment
  ATTENDANCE                            HR
  - Sattendance      REPORTS           - Leaveapplication
  - Tattendance      - ALL reports     - Overtime
  - Uattendance        filter by year
```

**Impact of Schoolyear Changes**:
- Switching current schoolyear changes ALL data views
- Reports MUST filter by schoolyear for accuracy
- Student enrollment is year-specific via studentrelation

---

### Permission System (Universal Access Control)

```
┌────────────────────────────────────────────────────────┐
│                   Permission System                     │
├────────────────────────────────────────────────────────┤
│                                                         │
│  Usertype ──→ Permission ──→ Feature Access           │
│     ↓                            ↓                      │
│  User/Teacher/Student/Parent  ┌──┴──┐                  │
│                              ALL Controllers            │
│                              check permissions          │
│                              via $this->               │
│                              permissionChecker()        │
│                                                         │
└────────────────────────────────────────────────────────┘
```

**Permission Granularity**:
- Each controller action has permission key (e.g., `student_add`, `invoice_edit`)
- Permissions assigned per usertype per school
- System checks permissions on EVERY controller method
- Bypass available for superadmin (usertypeID=1)

**Critical Features with Permission Dependencies**:
- ALL features depend on Permission for access control
- Modifying Permission affects user capability across entire system

---

### Integration Points

#### QuickBooks Integration
**Affected Features**: Invoice, Payment, Student

```
┌──────────────────────────────────────────────────────┐
│           QuickBooks Integration Flow                 │
├──────────────────────────────────────────────────────┤
│                                                       │
│  Student (add/edit)                                   │
│       ↓                                               │
│  Create/Update QB Customer                           │
│       ↓                                               │
│  Quickbookslog (record sync)                         │
│                                                       │
│  Invoice (add/edit)                                   │
│       ↓                                               │
│  Create/Update QB Invoice                            │
│       ↓                                               │
│  Quickbookslog (record sync)                         │
│                                                       │
│  Payment (add)                                        │
│       ↓                                               │
│  Create QB Payment                                    │
│       ↓                                               │
│  Quickbookslog (record sync)                         │
│                                                       │
└──────────────────────────────────────────────────────┘
```

**Configuration**: Quickbookssettings (OAuth, company ID)  
**Dependencies**: Student, Invoice, Payment

---

#### M-Pesa Integration
**Affected Features**: Payment, Make_payment

```
┌──────────────────────────────────────────────────────┐
│              M-Pesa Integration Flow                  │
├──────────────────────────────────────────────────────┤
│                                                       │
│  Student selects invoice                              │
│       ↓                                               │
│  Make_payment (gateway selection)                    │
│       ↓                                               │
│  Mpesa (STK Push request)                            │
│       ↓                                               │
│  Safaricom API                                        │
│       ↓                                               │
│  Callback validation                                  │
│       ↓                                               │
│  Payment record created                               │
│       ↓                                               │
│  Invoice marked paid                                  │
│                                                       │
└──────────────────────────────────────────────────────┘
```

**Configuration**: Paymentsettings (consumer key, secret)  
**Dependencies**: Invoice, Payment, Make_payment

---

#### Multi-School Architecture
**School** feature enables multi-tenancy:

```
┌──────────────────────────────────────────────────────┐
│            Multi-School Architecture                  │
├──────────────────────────────────────────────────────┤
│                                                       │
│  School (multiple instances)                          │
│       ↓                                               │
│  ALL Features filtered by schoolID                    │
│       ↓                                               │
│  Session variable: defaultschoolID                    │
│       ↓                                               │
│  Data isolation per school                            │
│                                                       │
│  Admin with multiple schools:                         │
│  - Can switch between schools                         │
│  - School selector in header                          │
│  - Separate data per school                           │
│                                                       │
└──────────────────────────────────────────────────────┘
```

**Impact**: Almost ALL tables have `schoolID` foreign key

---

## Critical Dependency Chains

### Chain 1: Student Enrollment
```
School → Schoolyear → Classes → Section
   ↓         ↓          ↓         ↓
Student ─────┴──────────┴─────────┴──→ Studentrelation
```

**Cannot create studentrelation without**:
- Valid student
- Valid class
- Valid section
- Valid schoolyear
- Valid school

---

### Chain 2: Exam to Report
```
Exam_type → Exam → Mark → Grade
               ↓       ↓
        Examschedule  Markpercentage
                          ↓
                   Examcompilation
                          ↓
                     Examranking
                          ↓
                   Studentexamreport
                   Marksheetreport
                   Progresscardreport
                   Certificatereport
```

**Cannot generate exam reports without**:
- Defined exam
- Entered marks
- Configured grades
- Completed compilation

---

### Chain 3: Invoice to Payment
```
Feetypes → Invoice → Payment → Quickbooks
              ↓         ↓
        Schoolterm   Make_payment
              ↓         ↓
          Student    Mpesa/Gateway
                       ↓
                  Student_statement
```

**Cannot process payment without**:
- Valid invoice
- Valid student
- Payment method configured

---

### Chain 4: Class Schedule
```
Teacher → Subject → Routine
   ↓         ↓        ↓
Classes → Section    Daily Schedule
```

**Cannot create routine without**:
- Valid teacher
- Valid subject
- Valid class/section mapping

---

## Notes for AI Agents

### When Modifying Features

#### 1. Always Check Layer
- **Layer 0-1**: Extreme caution. Changes affect entire system.
- **Layer 2**: Affects multiple operational features.
- **Layer 3**: Affects related reports and operations.
- **Layer 4**: Generally safe, read-only reports.

#### 2. Multi-Tenant Awareness
- ALWAYS filter queries by `schoolID` from session
- NEVER hardcode schoolID values
- Test with multiple schools when modifying School-related features

#### 3. Schoolyear Context
- Most queries need `schoolyearID` filter
- Current year stored in session: `defaultschoolYear`
- Historical data queries may need year-range filters

#### 4. Permission Checks
- Every controller method should have permission check
- Permission key format: `{feature}_{action}` (e.g., `student_add`)
- Superadmin (usertypeID=1) bypasses most checks

#### 5. Student Multi-Year Design
- Student record is PERSISTENT across years
- Enrollment is YEAR-SPECIFIC via studentrelation
- Same student can have different classes each year
- Use `studentrelation` to get year-specific class/section

#### 6. Integration Points
- **QuickBooks**: Syncs on add/edit of Student, Invoice, Payment
- **M-Pesa**: Webhook callbacks require validation
- **Payment Gateways**: Multiple gateways supported (Stripe, PayPal, etc.)

#### 7. Soft Deletes
- Many features use `deleted_at` flag instead of hard delete
- Check for `deleted_at IS NULL` in queries
- Restore functionality may exist

#### 8. Common Patterns

**CRUD Pattern**:
```
index()       - List with permission check + schoolID filter + schoolyear filter
add()         - Form + validation + create + redirect
edit(id)      - Load + form + validation + update + redirect
delete(id)    - Soft delete (set deleted_at) + redirect
view(id)      - Display details with related data
```

**AJAX Pattern**:
```
getData()     - Returns JSON for dropdowns/datatables
getRelated()  - Returns related entity data
validate()    - Server-side validation for forms
```

**Report Pattern**:
```
index()       - Filter form
print_preview() - PDF generation
send_pdf_to_mail() - Email PDF
```

#### 9. Database Transactions
- Financial operations (Invoice, Payment) use transactions
- Mark entry uses transactions
- Rollback on failure

#### 10. File Uploads
- Student photos: `uploads/student/`
- Teacher photos: `uploads/teacher/`
- Documents: `uploads/document/`
- Always validate file types and sizes

#### 11. Email/SMS
- Uses CodeIgniter email library
- Templates stored in `mailandsmstemplate`
- Bulk sending via `mailandsms`
- SMS uses configured SMS gateway

#### 12. PDF Generation
- Uses mPDF or TCPDF library
- Templates in `mvc/views/report/`
- School logo/header from settings

---

### Common Pitfalls to Avoid

1. **Breaking Multi-Year Logic**: Don't assume student has one class. Use `studentrelation` with `schoolyearID`.

2. **Ignoring schoolID Filter**: Always filter by `schoolID` from session to maintain multi-tenant isolation.

3. **Hardcoding Usertypes**: Use constants or check against `usertype` table. Don't hardcode IDs.

4. **Bypassing Permissions**: Always use `permissionChecker()` on controller methods.

5. **Forgetting Soft Deletes**: Check `deleted_at IS NULL` when querying.

6. **Integration Side Effects**: Adding students/invoices triggers QuickBooks sync. Test accordingly.

7. **Session Dependencies**: Many features rely on session variables (`defaultschoolYear`, `defaultschoolID`, `usertypeID`). Ensure these are set.

8. **Report Performance**: Large reports may timeout. Implement pagination or background jobs.

9. **Circular Dependencies**: Be careful when modifying core features (School, Schoolyear, User, Permission).

10. **Foreign Key Constraints**: Deleting parent records may fail due to child records. Use soft delete or cascading.

---

### Modification Impact Analysis Template

Before modifying a feature, ask:

1. **What layer is this feature?** (Higher layers = less risk)
2. **What features depend on this?** (Check "Used By" in feature docs)
3. **What does this feature depend on?** (Check "Depends On" in feature docs)
4. **Does this affect multi-school logic?** (schoolID filter needed?)
5. **Does this affect multi-year logic?** (schoolyearID filter needed?)
6. **Does this affect permissions?** (Permission checks in place?)
7. **Does this trigger integrations?** (QuickBooks, M-Pesa side effects?)
8. **Are there reports dependent on this?** (Report data accuracy maintained?)
9. **Does this involve financial data?** (Transaction safety needed?)
10. **Does this affect student records?** (Multi-year enrollment intact?)

---

### Quick Reference: Critical Features

**Never modify without extensive testing**:
- Install, Migration, Update (Layer 0)
- School, Schoolyear, User, Usertype, Permission (Layer 1)
- Student, Studentrelation (Layer 2)
- Invoice, Payment (Layer 3 - financial)

**Safe to modify with caution**:
- Reports (Layer 4 - read-only)
- Frontend features
- Non-core operational features

**Relatively safe**:
- Individual report templates
- Email templates
- Frontend pages/posts
- Settings adjustments

---

## Document Maintenance

**Last Updated**: January 2025  
**Maintained By**: AI Agent Documentation Team  
**Update Frequency**: After significant architectural changes or new feature additions  

**Related Documents**:
- [FEATURE_CATALOG.md](FEATURE_CATALOG.md) - Index of all features with links
- [FEATURE_INDEX.md](FEATURE_INDEX.md) - Auto-generated feature list
- [FEATURE_DOCUMENTATION_LOG.md](FEATURE_DOCUMENTATION_LOG.md) - Documentation progress tracker
- [Individual Feature Docs](features/) - Detailed per-feature documentation (199 files)

**Version History**:
- v1.0 (Jan 2025): Initial comprehensive interconnection map covering 199 features

---

## Summary Statistics

- **Total Features**: 199
- **Dependency Layers**: 5 (Layer 0-4)
- **Feature Clusters**: 11 (Academic, Financial, HR, Library, Inventory, Asset, Hostel, Transport, Communication, Frontend, SysAdmin)
- **Core Dependencies**: Student (hub), Schoolyear (context), Permission (access), School (multi-tenant)
- **External Integrations**: 2 (QuickBooks, M-Pesa/Safaricom)
- **Report Features**: 48+ specialized reports
- **User Types**: 5+ (Systemadmin, Admin, Teacher, Student, Parent, Staff roles)

---

**End of Feature Interconnection Map**
