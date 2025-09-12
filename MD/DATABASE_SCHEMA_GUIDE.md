# 🗄️ **DATABASE SCHEMA & RELATIONSHIPS GUIDE**

## **📋 OVERVIEW**

Panduan lengkap struktur database sistem Naeelah Firm, termasuk relationships, constraints, dan indexing strategies.

---

## **🏗️ CORE ARCHITECTURE TABLES**

### **1. Multi-Firm Foundation**

#### **Firms Table**
```sql
CREATE TABLE firms (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    registration_number VARCHAR(100) UNIQUE,
    address TEXT,
    phone_number VARCHAR(20),
    email VARCHAR(255),
    logo VARCHAR(255),
    website VARCHAR(255),
    tax_registration_number VARCHAR(100),
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    settings JSON,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    INDEX idx_firms_status (status),
    INDEX idx_firms_registration (registration_number)
);
```

#### **Users Table (Multi-Firm)**
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    username VARCHAR(255) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    department VARCHAR(100),
    notes TEXT,
    last_login_at TIMESTAMP NULL DEFAULT NULL,
    firm_id BIGINT UNSIGNED NOT NULL,
    remember_token VARCHAR(100),
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    FOREIGN KEY (firm_id) REFERENCES firms(id) ON DELETE CASCADE,
    INDEX idx_users_firm (firm_id),
    INDEX idx_users_email (email),
    INDEX idx_users_username (username)
);
```

### **2. Permission System (Spatie)**

#### **Roles Table**
```sql
CREATE TABLE roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    guard_name VARCHAR(255) NOT NULL,
    firm_id BIGINT UNSIGNED,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    FOREIGN KEY (firm_id) REFERENCES firms(id) ON DELETE CASCADE,
    UNIQUE KEY roles_name_guard_firm_unique (name, guard_name, firm_id),
    INDEX idx_roles_firm (firm_id)
);
```

#### **Model Has Roles (with Firm Context)**
```sql
CREATE TABLE model_has_roles (
    role_id BIGINT UNSIGNED NOT NULL,
    model_type VARCHAR(255) NOT NULL,
    model_id BIGINT UNSIGNED NOT NULL,
    firm_id BIGINT UNSIGNED,
    
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (firm_id) REFERENCES firms(id) ON DELETE CASCADE,
    PRIMARY KEY (role_id, model_id, model_type),
    INDEX idx_model_has_roles_firm (firm_id),
    INDEX idx_model_has_roles_model (model_id, model_type)
);
```

---

## **⚖️ CASE MANAGEMENT SCHEMA**

### **1. Core Case Tables**

#### **Cases Table**
```sql
CREATE TABLE cases (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    case_number VARCHAR(100) NOT NULL,
    title VARCHAR(500) NOT NULL,
    description TEXT,
    case_type_id BIGINT UNSIGNED NOT NULL,
    case_status_id BIGINT UNSIGNED NOT NULL,
    priority_level ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    judge_name VARCHAR(255),
    court_location VARCHAR(255),
    claim_amount DECIMAL(15,2) DEFAULT 0.00,
    person_in_charge VARCHAR(255),
    assigned_to BIGINT UNSIGNED,
    created_by BIGINT UNSIGNED NOT NULL,
    firm_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    FOREIGN KEY (case_type_id) REFERENCES case_types(id),
    FOREIGN KEY (case_status_id) REFERENCES case_statuses(id),
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (firm_id) REFERENCES firms(id) ON DELETE CASCADE,
    
    UNIQUE KEY cases_case_number_firm_unique (case_number, firm_id),
    INDEX idx_cases_firm (firm_id),
    INDEX idx_cases_status_firm (case_status_id, firm_id),
    INDEX idx_cases_type_firm (case_type_id, firm_id),
    INDEX idx_cases_created_at (created_at),
    INDEX idx_cases_assigned (assigned_to),
    FULLTEXT KEY idx_cases_search (case_number, title, description)
);
```

#### **Case Parties Table**
```sql
CREATE TABLE case_parties (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    case_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    ic_passport VARCHAR(50),
    phone VARCHAR(20),
    email VARCHAR(255),
    address TEXT,
    party_type ENUM('plaintiff', 'defendant', 'witness', 'other') NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    notes TEXT,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    FOREIGN KEY (case_id) REFERENCES cases(id) ON DELETE CASCADE,
    INDEX idx_case_parties_case (case_id),
    INDEX idx_case_parties_type (party_type),
    INDEX idx_case_parties_name (name)
);
```

#### **Case Partners Table (Lawyer Assignment)**
```sql
CREATE TABLE case_partners (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    case_id BIGINT UNSIGNED NOT NULL,
    partner_id BIGINT UNSIGNED NOT NULL,
    role ENUM('lead', 'associate', 'consultant') DEFAULT 'associate',
    hourly_rate DECIMAL(8,2),
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    FOREIGN KEY (case_id) REFERENCES cases(id) ON DELETE CASCADE,
    FOREIGN KEY (partner_id) REFERENCES partners(id) ON DELETE CASCADE,
    UNIQUE KEY case_partners_case_partner_unique (case_id, partner_id),
    INDEX idx_case_partners_case (case_id),
    INDEX idx_case_partners_partner (partner_id)
);
```

### **2. Case Timeline & Events**

#### **Case Timeline Table**
```sql
CREATE TABLE case_timelines (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    case_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    event_type ENUM('hearing', 'filing', 'meeting', 'deadline', 'milestone', 'other') NOT NULL,
    event_date DATE NOT NULL,
    event_time TIME,
    location VARCHAR(255),
    status ENUM('scheduled', 'completed', 'cancelled', 'postponed') DEFAULT 'scheduled',
    reminder_date DATE,
    notes TEXT,
    created_by BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    FOREIGN KEY (case_id) REFERENCES cases(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_case_timelines_case (case_id),
    INDEX idx_case_timelines_date (event_date),
    INDEX idx_case_timelines_type (event_type),
    INDEX idx_case_timelines_status (status)
);
```

#### **Calendar Events Table**
```sql
CREATE TABLE calendar_events (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    start_date DATETIME NOT NULL,
    end_date DATETIME,
    all_day BOOLEAN DEFAULT FALSE,
    location VARCHAR(255),
    color VARCHAR(7) DEFAULT '#3788d8',
    case_id BIGINT UNSIGNED,
    timeline_event_id BIGINT UNSIGNED,
    created_by BIGINT UNSIGNED NOT NULL,
    firm_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    FOREIGN KEY (case_id) REFERENCES cases(id) ON DELETE CASCADE,
    FOREIGN KEY (timeline_event_id) REFERENCES case_timelines(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (firm_id) REFERENCES firms(id) ON DELETE CASCADE,
    INDEX idx_calendar_events_firm (firm_id),
    INDEX idx_calendar_events_date (start_date),
    INDEX idx_calendar_events_case (case_id)
);
```

---

## **💰 FINANCIAL MANAGEMENT SCHEMA**

### **1. Document Flow Tables**

#### **Quotations Table**
```sql
CREATE TABLE quotations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    quotation_no VARCHAR(100) NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_address TEXT,
    customer_phone VARCHAR(20),
    customer_email VARCHAR(255),
    quotation_date DATE NOT NULL,
    valid_until DATE,
    payment_terms ENUM('immediate', 'net_7', 'net_15', 'net_30', 'net_60') DEFAULT 'net_30',
    subtotal DECIMAL(15,2) DEFAULT 0.00,
    tax_rate DECIMAL(5,2) DEFAULT 6.00,
    tax_total DECIMAL(15,2) DEFAULT 0.00,
    total DECIMAL(15,2) DEFAULT 0.00,
    total_words TEXT,
    status ENUM('draft', 'sent', 'accepted', 'rejected', 'expired') DEFAULT 'draft',
    notes TEXT,
    case_id BIGINT UNSIGNED,
    created_by BIGINT UNSIGNED NOT NULL,
    firm_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    FOREIGN KEY (case_id) REFERENCES cases(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (firm_id) REFERENCES firms(id) ON DELETE CASCADE,
    UNIQUE KEY quotations_quotation_no_firm_unique (quotation_no, firm_id),
    INDEX idx_quotations_firm (firm_id),
    INDEX idx_quotations_date (quotation_date),
    INDEX idx_quotations_status (status),
    INDEX idx_quotations_case (case_id)
);
```

#### **Tax Invoices Table**
```sql
CREATE TABLE tax_invoices (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    invoice_no VARCHAR(100) NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_address TEXT,
    customer_phone VARCHAR(20),
    customer_email VARCHAR(255),
    invoice_date DATE NOT NULL,
    due_date DATE,
    payment_terms ENUM('immediate', 'net_7', 'net_15', 'net_30', 'net_60') DEFAULT 'net_30',
    subtotal DECIMAL(15,2) DEFAULT 0.00,
    tax_rate DECIMAL(5,2) DEFAULT 6.00,
    tax_total DECIMAL(15,2) DEFAULT 0.00,
    total DECIMAL(15,2) DEFAULT 0.00,
    total_words TEXT,
    status ENUM('draft', 'sent', 'paid', 'overdue', 'cancelled') DEFAULT 'draft',
    notes TEXT,
    case_id BIGINT UNSIGNED,
    quotation_id BIGINT UNSIGNED,
    created_by BIGINT UNSIGNED NOT NULL,
    firm_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    FOREIGN KEY (case_id) REFERENCES cases(id) ON DELETE SET NULL,
    FOREIGN KEY (quotation_id) REFERENCES quotations(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (firm_id) REFERENCES firms(id) ON DELETE CASCADE,
    UNIQUE KEY tax_invoices_invoice_no_firm_unique (invoice_no, firm_id),
    INDEX idx_tax_invoices_firm (firm_id),
    INDEX idx_tax_invoices_date (invoice_date),
    INDEX idx_tax_invoices_due_date (due_date),
    INDEX idx_tax_invoices_status (status)
);
```

#### **Receipts Table**
```sql
CREATE TABLE receipts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    receipt_no VARCHAR(100) NOT NULL,
    receipt_date DATE NOT NULL,
    amount_paid DECIMAL(15,2) NOT NULL,
    payment_method ENUM('cash', 'cheque', 'bank_transfer', 'credit_card', 'online') DEFAULT 'cash',
    reference_no VARCHAR(100),
    notes TEXT,
    case_id BIGINT UNSIGNED,
    quotation_id BIGINT UNSIGNED,
    tax_invoice_id BIGINT UNSIGNED,
    created_by BIGINT UNSIGNED NOT NULL,
    firm_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    FOREIGN KEY (case_id) REFERENCES cases(id) ON DELETE SET NULL,
    FOREIGN KEY (quotation_id) REFERENCES quotations(id) ON DELETE SET NULL,
    FOREIGN KEY (tax_invoice_id) REFERENCES tax_invoices(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (firm_id) REFERENCES firms(id) ON DELETE CASCADE,
    UNIQUE KEY receipts_receipt_no_firm_unique (receipt_no, firm_id),
    INDEX idx_receipts_firm (firm_id),
    INDEX idx_receipts_date (receipt_date),
    INDEX idx_receipts_amount (amount_paid),
    INDEX idx_receipts_method (payment_method)
);
```

### **2. Expense Management**

#### **Bills Table**
```sql
CREATE TABLE bills (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    bill_no VARCHAR(100) NOT NULL,
    vendor_name VARCHAR(255) NOT NULL,
    vendor_address TEXT,
    vendor_phone VARCHAR(20),
    bill_date DATE NOT NULL,
    due_date DATE,
    subtotal DECIMAL(15,2) DEFAULT 0.00,
    tax_rate DECIMAL(5,2) DEFAULT 6.00,
    tax_total DECIMAL(15,2) DEFAULT 0.00,
    total_amount DECIMAL(15,2) DEFAULT 0.00,
    status ENUM('pending', 'approved', 'paid', 'overdue', 'cancelled') DEFAULT 'pending',
    notes TEXT,
    created_by BIGINT UNSIGNED NOT NULL,
    firm_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (firm_id) REFERENCES firms(id) ON DELETE CASCADE,
    UNIQUE KEY bills_bill_no_firm_unique (bill_no, firm_id),
    INDEX idx_bills_firm (firm_id),
    INDEX idx_bills_date (bill_date),
    INDEX idx_bills_status (status),
    INDEX idx_bills_vendor (vendor_name)
);
```

#### **Vouchers Table**
```sql
CREATE TABLE vouchers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    voucher_no VARCHAR(100) NOT NULL,
    payee_name VARCHAR(255) NOT NULL,
    payment_date DATE NOT NULL,
    payment_method ENUM('cash', 'cheque', 'bank_transfer', 'credit_card') DEFAULT 'cash',
    reference_no VARCHAR(100),
    total_amount DECIMAL(15,2) DEFAULT 0.00,
    description TEXT,
    status ENUM('draft', 'approved', 'paid', 'cancelled') DEFAULT 'draft',
    notes TEXT,
    created_by BIGINT UNSIGNED NOT NULL,
    firm_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (firm_id) REFERENCES firms(id) ON DELETE CASCADE,
    UNIQUE KEY vouchers_voucher_no_firm_unique (voucher_no, firm_id),
    INDEX idx_vouchers_firm (firm_id),
    INDEX idx_vouchers_date (payment_date),
    INDEX idx_vouchers_status (status),
    INDEX idx_vouchers_payee (payee_name)
);
```

### **3. Item Details Tables**

#### **Document Items Pattern**
```sql
-- Quotation Items
CREATE TABLE quotation_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    quotation_id BIGINT UNSIGNED NOT NULL,
    description TEXT NOT NULL,
    quantity DECIMAL(10,2) DEFAULT 1.00,
    unit_price DECIMAL(15,2) NOT NULL,
    total DECIMAL(15,2) NOT NULL,
    sort_order INT DEFAULT 0,
    
    FOREIGN KEY (quotation_id) REFERENCES quotations(id) ON DELETE CASCADE,
    INDEX idx_quotation_items_quotation (quotation_id)
);

-- Tax Invoice Items
CREATE TABLE tax_invoice_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tax_invoice_id BIGINT UNSIGNED NOT NULL,
    description TEXT NOT NULL,
    quantity DECIMAL(10,2) DEFAULT 1.00,
    unit_price DECIMAL(15,2) NOT NULL,
    total DECIMAL(15,2) NOT NULL,
    sort_order INT DEFAULT 0,
    
    FOREIGN KEY (tax_invoice_id) REFERENCES tax_invoices(id) ON DELETE CASCADE,
    INDEX idx_tax_invoice_items_invoice (tax_invoice_id)
);

-- Bill Items
CREATE TABLE bill_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    bill_id BIGINT UNSIGNED NOT NULL,
    expense_category_id BIGINT UNSIGNED,
    description TEXT NOT NULL,
    quantity DECIMAL(10,2) DEFAULT 1.00,
    unit_price DECIMAL(15,2) NOT NULL,
    total DECIMAL(15,2) NOT NULL,
    
    FOREIGN KEY (bill_id) REFERENCES bills(id) ON DELETE CASCADE,
    FOREIGN KEY (expense_category_id) REFERENCES expense_categories(id) ON DELETE SET NULL,
    INDEX idx_bill_items_bill (bill_id),
    INDEX idx_bill_items_category (expense_category_id)
);
```

---

## **📊 ACCOUNTING & REPORTING SCHEMA**

### **1. Chart of Accounts**

#### **Opening Balances Table**
```sql
CREATE TABLE opening_balances (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    bank_code VARCHAR(50) NOT NULL,
    bank_name VARCHAR(255) NOT NULL,
    account_type ENUM('asset', 'liability', 'equity', 'revenue', 'expense') NOT NULL,
    debit_myr DECIMAL(15,2) DEFAULT 0.00,
    credit_myr DECIMAL(15,2) DEFAULT 0.00,
    is_active BOOLEAN DEFAULT TRUE,
    notes TEXT,
    firm_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    FOREIGN KEY (firm_id) REFERENCES firms(id) ON DELETE CASCADE,
    UNIQUE KEY opening_balances_bank_code_firm_unique (bank_code, firm_id),
    INDEX idx_opening_balances_firm (firm_id),
    INDEX idx_opening_balances_type (account_type),
    INDEX idx_opening_balances_active (is_active)
);
```

#### **Expense Categories Table**
```sql
CREATE TABLE expense_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    category_type ENUM('operational', 'administrative', 'legal', 'marketing', 'other') DEFAULT 'operational',
    is_active BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0,
    firm_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    FOREIGN KEY (firm_id) REFERENCES firms(id) ON DELETE CASCADE,
    INDEX idx_expense_categories_firm (firm_id),
    INDEX idx_expense_categories_active (is_active),
    INDEX idx_expense_categories_type (category_type)
);
```

---

## **🔗 RELATIONSHIP MAPPING**

### **1. Core Relationships**

#### **User Relationships**
```php
// User Model Relationships
public function firm(): BelongsTo // users.firm_id -> firms.id
public function createdCases(): HasMany // cases.created_by -> users.id
public function assignedCases(): HasMany // cases.assigned_to -> users.id
public function createdQuotations(): HasMany // quotations.created_by -> users.id
public function createdReceipts(): HasMany // receipts.created_by -> users.id
public function activities(): HasMany // activity_log.causer_id -> users.id
```

#### **Case Relationships**
```php
// CourtCase Model Relationships
public function firm(): BelongsTo // cases.firm_id -> firms.id
public function caseType(): BelongsTo // cases.case_type_id -> case_types.id
public function caseStatus(): BelongsTo // cases.case_status_id -> case_statuses.id
public function createdBy(): BelongsTo // cases.created_by -> users.id
public function assignedTo(): BelongsTo // cases.assigned_to -> users.id
public function parties(): HasMany // case_parties.case_id -> cases.id
public function partners(): HasMany // case_partners.case_id -> cases.id
public function timeline(): HasMany // case_timelines.case_id -> cases.id
public function files(): HasMany // case_files.case_id -> cases.id
public function calendarEvents(): HasMany // calendar_events.case_id -> cases.id
public function quotations(): HasMany // quotations.case_id -> cases.id
public function taxInvoices(): HasMany // tax_invoices.case_id -> cases.id
public function receipts(): HasMany // receipts.case_id -> cases.id
```

#### **Financial Document Relationships**
```php
// Quotation Model Relationships
public function firm(): BelongsTo // quotations.firm_id -> firms.id
public function case(): BelongsTo // quotations.case_id -> cases.id
public function createdBy(): BelongsTo // quotations.created_by -> users.id
public function items(): HasMany // quotation_items.quotation_id -> quotations.id
public function taxInvoices(): HasMany // tax_invoices.quotation_id -> quotations.id
public function receipts(): HasMany // receipts.quotation_id -> quotations.id

// Receipt Model Relationships (Smart Linking)
public function firm(): BelongsTo // receipts.firm_id -> firms.id
public function case(): BelongsTo // receipts.case_id -> cases.id
public function quotation(): BelongsTo // receipts.quotation_id -> quotations.id
public function taxInvoice(): BelongsTo // receipts.tax_invoice_id -> tax_invoices.id
public function createdBy(): BelongsTo // receipts.created_by -> users.id
```

### **2. Polymorphic Relationships**

#### **Activity Log (Polymorphic)**
```php
// Activity Log Relationships
public function subject(): MorphTo // subject_type + subject_id
public function causer(): MorphTo // causer_type + causer_id

// Models with Activity Logging
CourtCase::class => 'case'
User::class => 'user'
Quotation::class => 'quotation'
Receipt::class => 'receipt'
// etc...
```

---

## **📈 INDEXING STRATEGY**

### **1. Performance Indexes**
```sql
-- Multi-firm queries
CREATE INDEX idx_firm_context ON cases(firm_id, case_status_id, created_at);
CREATE INDEX idx_firm_receipts ON receipts(firm_id, receipt_date, amount_paid);
CREATE INDEX idx_firm_bills ON bills(firm_id, bill_date, total_amount);

-- Search indexes
CREATE FULLTEXT INDEX idx_case_search ON cases(case_number, title, description);
CREATE INDEX idx_party_search ON case_parties(name, ic_passport);
CREATE INDEX idx_client_search ON clients(name, ic_passport, phone);

-- Date range queries
CREATE INDEX idx_timeline_dates ON case_timelines(event_date, event_time);
CREATE INDEX idx_calendar_dates ON calendar_events(start_date, end_date);
CREATE INDEX idx_financial_dates ON receipts(receipt_date, amount_paid);

-- Status and type filters
CREATE INDEX idx_case_status_type ON cases(case_status_id, case_type_id, firm_id);
CREATE INDEX idx_document_status ON quotations(status, quotation_date, firm_id);
```

### **2. Foreign Key Constraints**
```sql
-- Cascade deletes for firm isolation
ALTER TABLE users ADD CONSTRAINT fk_users_firm 
    FOREIGN KEY (firm_id) REFERENCES firms(id) ON DELETE CASCADE;

ALTER TABLE cases ADD CONSTRAINT fk_cases_firm 
    FOREIGN KEY (firm_id) REFERENCES firms(id) ON DELETE CASCADE;

-- Protect referential integrity
ALTER TABLE case_parties ADD CONSTRAINT fk_case_parties_case 
    FOREIGN KEY (case_id) REFERENCES cases(id) ON DELETE CASCADE;

ALTER TABLE quotation_items ADD CONSTRAINT fk_quotation_items_quotation 
    FOREIGN KEY (quotation_id) REFERENCES quotations(id) ON DELETE CASCADE;
```

---

## **🔧 MAINTENANCE QUERIES**

### **1. Data Integrity Checks**
```sql
-- Check orphaned records
SELECT 'cases' as table_name, COUNT(*) as orphaned_count
FROM cases c LEFT JOIN firms f ON c.firm_id = f.id 
WHERE f.id IS NULL

UNION ALL

SELECT 'users' as table_name, COUNT(*) as orphaned_count
FROM users u LEFT JOIN firms f ON u.firm_id = f.id 
WHERE f.id IS NULL;

-- Check financial document consistency
SELECT 
    q.quotation_no,
    q.total as quotation_total,
    COALESCE(SUM(r.amount_paid), 0) as total_received,
    (q.total - COALESCE(SUM(r.amount_paid), 0)) as outstanding
FROM quotations q
LEFT JOIN receipts r ON r.quotation_id = q.id
WHERE q.status = 'accepted'
GROUP BY q.id, q.quotation_no, q.total
HAVING outstanding > 0;
```

### **2. Performance Monitoring**
```sql
-- Slow query analysis
SELECT 
    table_name,
    index_name,
    cardinality,
    pages,
    filtered
FROM information_schema.statistics 
WHERE table_schema = 'naeelah_firm'
ORDER BY cardinality DESC;

-- Table size analysis
SELECT 
    table_name,
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Size (MB)',
    table_rows
FROM information_schema.tables 
WHERE table_schema = 'naeelah_firm'
ORDER BY (data_length + index_length) DESC;
```

---

**Database schema ini direka untuk menyokong multi-firm tenancy dengan performance yang optimal dan data integrity yang terjamin.**
