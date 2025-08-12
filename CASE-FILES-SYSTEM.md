# Case Files System Documentation

## Overview
The Case Files system manages legal documents and files with comprehensive tracking, status management, and rack location system. It supports various file types, checkout/check-in functionality, and detailed audit trails.

## Features

### 📁 File Management
- **File Upload & Storage**: Secure file storage with organized directory structure
- **File Types**: Support for multiple document formats (PDF, DOCX, XLSX, ZIP, etc.)
- **File Size Tracking**: Automatic file size calculation and display
- **MIME Type Detection**: Proper file type identification

### 🏷️ File Classification
- **Contract**: Legal agreements, contracts, and binding documents
- **Evidence**: Supporting documents, statements, and proof materials
- **Correspondence**: Letters, emails, and communication records
- **Court Document**: Official court filings and orders
- **Invoice**: Billing and financial documents
- **Other**: Miscellaneous documents and files

### 📊 Status Management
- **IN**: File is available in the system
- **OUT**: File has been checked out by someone
- **Rack Location**: Physical storage location when file is IN
- **Checkout Tracking**: Who took the file and when
- **Return Dates**: Expected and actual return dates

### 🔍 Search & Filter
- **Case Reference**: Filter by specific case (C-001, C-002, etc.)
- **File Type**: Filter by document category
- **Status**: Filter by availability (IN/OUT)
- **Date Range**: Filter by checkout/return dates

## Data Structure

### CaseFile Model Fields
```php
protected $fillable = [
    'case_ref',           // Case reference (C-001, C-002, etc.)
    'file_name',          // Original file name
    'file_path',          // Storage path
    'file_type',          // Document category
    'file_size',          // File size in bytes/format
    'mime_type',          // File MIME type
    'description',        // File description
    'status',             // IN or OUT
    'taken_by',          // Who checked out the file
    'purpose',            // Reason for checkout
    'expected_return',    // Expected return date
    'actual_return',      // Actual return date
    'rack_location',      // Physical rack location
];
```

### File Types & Colors
```php
'contract' => 'bg-blue-100 text-blue-800',
'evidence' => 'bg-green-100 text-green-800',
'correspondence' => 'bg-yellow-100 text-yellow-800',
'court_document' => 'bg-purple-100 text-purple-800',
'invoice' => 'bg-orange-100 text-orange-800',
'other' => 'bg-gray-100 text-gray-800',
```

### Status Badge Colors
```php
'IN' => 'bg-green-100 text-green-800',
'OUT' => 'bg-red-100 text-red-800',
```

## Dummy Data Created

### 📊 Summary Statistics
- **Total Files**: 14
- **Files IN**: 8
- **Files OUT**: 6
- **Case References**: 10 (C-001 to C-010)

### 📁 File Distribution by Type
- **Contract**: 2 files
- **Evidence**: 5 files
- **Correspondence**: 2 files
- **Court Document**: 2 files
- **Invoice**: 2 files
- **Other**: 1 file

### 🏢 Case References
- **C-001**: Property Sale Case
- **C-002**: Employment Contract Case
- **C-003**: Financial Investigation Case
- **C-004**: Traffic Accident Case
- **C-005**: Client Communication Case
- **C-006**: Legal Notice Case
- **C-007**: Court Injunction Case
- **C-008**: Affidavit Case
- **C-009**: Legal Fees Case
- **C-010**: Disbursement Case

### 📍 Rack Locations
- **Rack A-01**: Case C-001, C-004
- **Rack A-02**: Case C-003
- **Rack A-03**: Case C-005
- **Rack B-01**: Case C-007
- **Rack B-02**: Case C-009
- **Rack C-01**: Case C-001 (additional file)
- **Rack C-02**: Case C-002 (additional file)

## Sample Files

### Contract Files
1. **Sale_Purchase_Agreement_2025.pdf** (C-001)
   - Size: 2.5 MB
   - Status: IN
   - Location: Rack A-01
   - Description: Sale and Purchase Agreement for Commercial Property in Petaling Jaya

2. **Employment_Contract_Ali_Ahmad.docx** (C-002)
   - Size: 1.8 MB
   - Status: OUT
   - Taken by: Ahmad Firm
   - Purpose: Contract Review Meeting with HR Department
   - Expected Return: 14/08/2025

### Evidence Files
1. **Bank_Statements_2024.pdf** (C-003)
   - Size: 5.2 MB
   - Status: IN
   - Location: Rack A-02
   - Description: Bank Statements for Financial Investigation Case

2. **Witness_Statement_Siti_Binti_Ahmad.pdf** (C-004)
   - Size: 3.1 MB
   - Status: OUT
   - Taken by: Siti Partner
   - Purpose: Court Hearing Preparation
   - Expected Return: 12/08/2025

### Correspondence Files
1. **Client_Email_Correspondence.zip** (C-005)
   - Size: 8.7 MB
   - Status: IN
   - Location: Rack A-03
   - Description: Compressed Email Correspondence with Client

2. **Legal_Notice_Response.pdf** (C-006)
   - Size: 2.9 MB
   - Status: OUT
   - Taken by: Fatimah Staff
   - Purpose: Client Consultation Meeting
   - Expected Return: 16/08/2025

### Court Documents
1. **Court_Order_Interim_Injunction.pdf** (C-007)
   - Size: 1.5 MB
   - Status: IN
   - Location: Rack B-01
   - Description: Interim Injunction Order from High Court

2. **Affidavit_in_Support.pdf** (C-008)
   - Size: 4.2 MB
   - Status: OUT
   - Taken by: Omar Staff
   - Purpose: Document Filing at Court Registry
   - Expected Return: 13/08/2025

### Invoice Files
1. **Legal_Fees_Invoice_Q1_2025.pdf** (C-009)
   - Size: 1.2 MB
   - Status: IN
   - Location: Rack B-02
   - Description: Quarterly Legal Fees Invoice for Client

2. **Disbursement_Expenses.xlsx** (C-010)
   - Size: 856 KB
   - Status: OUT
   - Taken by: Zainab Firm
   - Purpose: Accounting Department Review
   - Expected Return: 18/08/2025

## File Management Operations

### Checkout Process
1. **Select File**: Choose file from available files (Status: IN)
2. **Enter Details**: 
   - Taken by (staff name)
   - Purpose (reason for checkout)
   - Expected return date
3. **Update Status**: File status changes to OUT
4. **Clear Location**: Rack location is cleared

### Check-in Process
1. **Return File**: File is returned to system
2. **Update Status**: File status changes to IN
3. **Assign Location**: Assign rack location
4. **Record Return**: Update actual return date

### File Search
- **By Case**: Search files for specific case reference
- **By Type**: Filter by document category
- **By Status**: Find available or checked-out files
- **By Date**: Search by checkout/return dates

## Security Features

### Access Control
- Role-based permissions for file access
- Audit trail for all file operations
- Secure file storage with proper paths

### Data Validation
- File type validation
- Size limit enforcement
- MIME type verification
- Required field validation

## Performance Features

### File Size Formatting
- Automatic conversion (B, KB, MB, GB)
- Human-readable display
- Efficient storage calculation

### Icon System
- File type-specific icons
- Color-coded file types
- Visual file identification

### Status Tracking
- Real-time status updates
- Overdue file detection
- Return date monitoring

## API Endpoints

### File Management
- `GET /file-management` - List all files
- `POST /file-management` - Upload new file
- `PUT /file-management/{id}` - Update file
- `DELETE /file-management/{id}` - Delete file

### File Operations
- `POST /file-management/{id}/checkout` - Checkout file
- `POST /file-management/{id}/checkin` - Check-in file
- `GET /file-management/{id}/history` - File history

## Testing

### View Dummy Data
Visit: `http://localhost:8000/file-management`

### Database Verification
```bash
# Check total files
php artisan tinker --execute="echo 'Total: ' . \App\Models\CaseFile::count();"

# Check files by status
php artisan tinker --execute="echo 'IN: ' . \App\Models\CaseFile::where('status', 'IN')->count(); echo 'OUT: ' . \App\Models\CaseFile::where('status', 'OUT')->count();"

# Check files by type
php artisan tinker --execute="\App\Models\CaseFile::selectRaw('file_type, count(*) as count')->groupBy('file_type')->get()->each(function(\$f) { echo \$f->file_type . ': ' . \$f->count . PHP_EOL; });"
```

## Maintenance

### Regular Tasks
- Monitor overdue files
- Update rack locations
- Archive old files
- Clean up temporary files

### Data Backup
- Regular database backups
- File storage backups
- Audit trail preservation

## Future Enhancements

### Planned Features
- Barcode/QR code integration
- Mobile app support
- Advanced search filters
- File versioning
- Digital signatures
- Cloud storage integration 