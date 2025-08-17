# Partner Status System Documentation

## Overview
Sistem partner menggunakan dua jenis status yang berbeza untuk menguruskan partner dengan lebih efektif:

## 1. Operational Status (Status Operasi)
**Field:** `status` dalam database

### Tujuan
- Menguruskan status operasi perniagaan partner
- Menentukan sama ada partner boleh menjalankan aktiviti perniagaan

### Pilihan Status
- **Active** - Partner boleh menjalankan aktiviti perniagaan secara normal
- **Inactive** - Partner tidak aktif dalam sistem (tidak boleh menjalankan aktiviti)
- **Suspended** - Partner digantung sementara (tidak boleh menjalankan aktiviti)

### Di mana digunakan
- Dropdown dalam form create/edit partner
- Column dalam table partner list
- Display dalam partner show page

## 2. Ban Status (Status Larangan)
**Field:** `is_banned` dalam database (boolean)

### Tujuan
- Menguruskan akses keselamatan partner
- Menentukan sama ada partner dilarang akses ke sistem

### Pilihan Status
- **Not Banned** (false) - Partner boleh akses sistem
- **Banned** (true) - Partner dilarang akses sistem

### Di mana digunakan
- Button Ban/Unban dalam action table
- Display dalam partner show page
- Security control untuk akses sistem

## Perbezaan Utama

| Aspek | Operational Status | Ban Status |
|-------|-------------------|------------|
| **Tujuan** | Operasi perniagaan | Keselamatan akses |
| **Scope** | Aktiviti perniagaan | Akses sistem |
| **Pilihan** | Active/Inactive/Suspended | Banned/Not Banned |
| **Field** | `status` (string) | `is_banned` (boolean) |
| **Contoh** | Partner boleh aktif tapi dilarang akses | Partner boleh akses tapi tidak aktif |

## Contoh Penggunaan

### Senario 1: Partner Aktif Tapi Dilarang
- **Operational Status:** Active
- **Ban Status:** Banned
- **Kesannya:** Partner boleh menjalankan perniagaan tapi tidak boleh akses sistem

### Senario 2: Partner Tidak Aktif Tapi Boleh Akses
- **Operational Status:** Inactive
- **Ban Status:** Not Banned
- **Kesannya:** Partner tidak boleh menjalankan perniagaan tapi boleh akses sistem

### Senario 3: Partner Normal
- **Operational Status:** Active
- **Ban Status:** Not Banned
- **Kesannya:** Partner boleh menjalankan perniagaan dan akses sistem

## Implementation Notes

### Model Partner
```php
protected $fillable = [
    'status',        // Operational status
    'is_banned',     // Ban status
    // ... other fields
];

protected $casts = [
    'is_banned' => 'boolean',
    // ... other casts
];
```

### Status Badge Colors
```php
public function getStatusBadgeColorAttribute(): string
{
    return match ($this->status) {
        'active' => 'bg-green-100 text-green-800',
        'inactive' => 'bg-red-100 text-red-800',
        'suspended' => 'bg-yellow-100 text-yellow-800',
        default => 'bg-gray-100 text-gray-800',
    };
}
```

### Ban Status Colors
```php
// In views
{{ $partner->is_banned ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}
```

## UI/UX Improvements

### Form Labels
- **Operational Status** - untuk clarify bahawa ini adalah status operasi
- **Ban Status** - untuk clarify bahawa ini adalah status keselamatan

### Help Text
- Ditambah help text dalam form untuk menjelaskan perbezaan
- "Operational status for business activities"

### Table Headers
- **Operational Status** column dalam table
- **Ban/Unban** button dalam action column

## Best Practices

1. **Jangan kelirukan kedua-dua status**
2. **Gunakan Operational Status untuk business logic**
3. **Gunakan Ban Status untuk security control**
4. **Sentiasa display kedua-dua status dalam show page**
5. **Gunakan warna yang berbeza untuk kedua-dua status** 