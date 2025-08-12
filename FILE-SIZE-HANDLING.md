# File Size Handling System

## Overview
The file size handling system in the CaseFile model provides robust methods for managing and displaying file sizes in various formats. It handles both numeric and formatted string inputs, ensuring consistent display and accurate calculations.

## Problem Solved
The original system encountered errors when trying to perform mathematical operations on formatted file size strings (e.g., "2.5 MB"). The new system handles both numeric and formatted string inputs gracefully.

## Features

### 🔢 File Size Attributes

#### 1. `getFormattedSizeAttribute()`
Returns a human-readable file size string.

**Input Types:**
- Numeric values (e.g., `2621440`)
- Formatted strings (e.g., `"2.5 MB"`)
- Mixed formats

**Output:**
- Always returns formatted string (e.g., `"2.5 MB"`)
- Handles edge cases gracefully

**Example:**
```php
$file = CaseFile::first();
echo $file->formatted_size; // "2.5 MB"
```

#### 2. `getSizeInBytesAttribute()`
Converts any file size format to bytes for calculations.

**Input Types:**
- Numeric values
- Formatted strings (`"2.5 MB"`, `"1.8 KB"`, etc.)

**Output:**
- Integer representing bytes
- Returns 0 for invalid formats

**Example:**
```php
$file = CaseFile::first();
echo $file->size_in_bytes; // 2621440
```

### 📊 Database Scopes

#### 1. `scopeOrderBySize($query, $direction = 'desc')`
Orders files by size in the database query.

**Parameters:**
- `$direction`: 'asc' or 'desc' (default: 'desc')

**Example:**
```php
// Get largest files first
$largeFiles = CaseFile::orderBySize('desc')->take(5)->get();

// Get smallest files first
$smallFiles = CaseFile::orderBySize('asc')->take(5)->get();
```

#### 2. `scopeSizeBetween($query, $minSize, $maxSize)`
Filters files by size range.

**Parameters:**
- `$minSize`: Minimum size in bytes
- `$maxSize`: Maximum size in bytes

**Example:**
```php
// Get files between 1MB and 5MB
$mediumFiles = CaseFile::sizeBetween(1024*1024, 5*1024*1024)->get();
```

## Implementation Details

### File Size Format Detection
```php
// Detects formatted strings like "2.5 MB", "1.8 KB", etc.
preg_match('/^\d+(\.\d+)?\s*(B|KB|MB|GB)$/i', $fileSize)
```

### Size Conversion Logic
```php
switch ($unit) {
    case 'B':
        return (int) $size;
    case 'KB':
        return (int) ($size * 1024);
    case 'MB':
        return (int) ($size * 1024 * 1024);
    case 'GB':
        return (int) ($size * 1024 * 1024 * 1024);
}
```

### Database Query Optimization
Uses MySQL's `REGEXP` and `CASE` statements for efficient sorting and filtering without loading all records into PHP memory.

## Usage Examples

### Basic Usage
```php
// Get formatted size for display
$file = CaseFile::find(1);
echo $file->formatted_size; // "2.5 MB"

// Get size in bytes for calculations
$bytes = $file->size_in_bytes; // 2621440

// Compare file sizes
if ($file1->size_in_bytes > $file2->size_in_bytes) {
    echo "File 1 is larger";
}
```

### Advanced Queries
```php
// Get largest files
$largestFiles = CaseFile::orderBySize('desc')->take(10)->get();

// Get files under 1MB
$smallFiles = CaseFile::sizeBetween(0, 1024*1024)->get();

// Get files between 1MB and 10MB
$mediumFiles = CaseFile::sizeBetween(1024*1024, 10*1024*1024)->get();

// Get files by type and size
$largePDFs = CaseFile::where('file_type', 'contract')
    ->orderBySize('desc')
    ->take(5)
    ->get();
```

### In Views
```blade
<!-- Display formatted size -->
<td>{{ $file->formatted_size }}</td>

<!-- Conditional styling based on size -->
<td class="{{ $file->size_in_bytes > 5*1024*1024 ? 'text-red-500' : 'text-green-500' }}">
    {{ $file->formatted_size }}
</td>

<!-- Size-based filtering -->
@if($file->size_in_bytes < 1024*1024)
    <span class="badge badge-success">Small File</span>
@elseif($file->size_in_bytes < 10*1024*1024)
    <span class="badge badge-warning">Medium File</span>
@else
    <span class="badge badge-danger">Large File</span>
@endif
```

## Error Handling

### Graceful Degradation
- Invalid formats return fallback values
- Non-numeric strings are handled safely
- Database queries don't fail on malformed data

### Validation
```php
// Check if size is valid
if ($file->size_in_bytes > 0) {
    // Valid size
} else {
    // Invalid or unknown size
}
```

## Performance Considerations

### Database Optimization
- Uses MySQL's built-in functions for sorting
- Avoids loading all records for size calculations
- Efficient regex patterns for format detection

### Memory Usage
- Minimal memory overhead
- Calculations done at database level when possible
- Lazy loading of size attributes

## Testing

### Unit Tests
```php
// Test formatted size
$file = new CaseFile(['file_size' => '2.5 MB']);
$this->assertEquals('2.5 MB', $file->formatted_size);

// Test size in bytes
$this->assertEquals(2621440, $file->size_in_bytes);

// Test numeric input
$file = new CaseFile(['file_size' => 2621440]);
$this->assertEquals('2.5 MB', $file->formatted_size);
```

### Integration Tests
```php
// Test database queries
$files = CaseFile::orderBySize('desc')->get();
$this->assertTrue($files->first()->size_in_bytes >= $files->last()->size_in_bytes);

// Test size filtering
$mediumFiles = CaseFile::sizeBetween(1024*1024, 5*1024*1024)->get();
foreach ($mediumFiles as $file) {
    $this->assertGreaterThanOrEqual(1024*1024, $file->size_in_bytes);
    $this->assertLessThanOrEqual(5*1024*1024, $file->size_in_bytes);
}
```

## Migration Guide

### From Old System
If migrating from a system that stored only numeric bytes:

1. **Update existing records:**
```sql
UPDATE case_files 
SET file_size = CONCAT(ROUND(file_size / (1024*1024), 1), ' MB')
WHERE file_size > 1024*1024;
```

2. **Update new uploads:**
```php
// In file upload handler
$fileSize = $uploadedFile->getSize();
$formattedSize = $this->formatFileSize($fileSize);

$caseFile = CaseFile::create([
    'file_size' => $formattedSize,
    // ... other fields
]);
```

### Helper Method
```php
private function formatFileSize($bytes)
{
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    
    $bytes /= pow(1024, $pow);
    
    return round($bytes, 1) . ' ' . $units[$pow];
}
```

## Best Practices

### Data Storage
- Store formatted strings for display consistency
- Use numeric values for calculations when needed
- Validate file sizes before storage

### Performance
- Use database scopes for large datasets
- Cache frequently accessed size calculations
- Avoid repeated format conversions

### User Experience
- Display formatted sizes to users
- Use appropriate units (B, KB, MB, GB)
- Provide size-based filtering options

## Troubleshooting

### Common Issues

1. **"A non-numeric value encountered" Error**
   - **Cause**: Trying to perform math on formatted strings
   - **Solution**: Use `size_in_bytes` attribute for calculations

2. **Incorrect Size Display**
   - **Cause**: Mixed format storage
   - **Solution**: Standardize on formatted strings

3. **Slow Queries**
   - **Cause**: Loading all records for sorting
   - **Solution**: Use database scopes

### Debug Information
```php
// Debug file size information
$file = CaseFile::find(1);
echo "Original: " . $file->file_size . "\n";
echo "Formatted: " . $file->formatted_size . "\n";
echo "Bytes: " . $file->size_in_bytes . "\n";
echo "Is numeric: " . (is_numeric($file->file_size) ? 'Yes' : 'No') . "\n";
```

## Future Enhancements

### Planned Features
- **Binary vs Decimal**: Support for both 1024 and 1000 base units
- **Custom Units**: Support for TB, PB, etc.
- **Size Categories**: Automatic categorization (Small, Medium, Large)
- **Compression Detection**: Detect compressed file sizes
- **Storage Quotas**: Track storage usage per user/case

### Integration Plans
- **File Upload Validation**: Size limits and validation
- **Storage Monitoring**: Track disk usage
- **Backup Size Calculation**: Estimate backup sizes
- **Cost Calculation**: Calculate storage costs 