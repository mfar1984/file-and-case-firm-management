<?php

namespace App\Models;

use App\Traits\HasFirmScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourtCase extends Model
{
    use HasFactory, LogsActivity, HasFirmScope;

    protected $table = 'cases';

    protected $fillable = [
        'case_number',
        'title',
        'description',
        'case_type_id',
        'case_status_id',
        'priority_level',
        'judge_name',
        'court_location',
        'claim_amount',
        'notes',
        'created_by',
        'assigned_to',
        // Newly persisted fields
        'person_in_charge',
        'court_ref',
        'jurisdiction',
        'section',
        'initiating_document',
        // Conveyancing specific fields
        'name_of_property',
        'others_document',
        'firm_id',
    ];

    protected $casts = [
        'filing_date' => 'date',
        'hearing_date' => 'date',
        'claim_amount' => 'decimal:2',
    ];

    /**
     * Boot method
     * Note: case_number generation is now handled in the controller
     * to have access to section and client information
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($case) {
            // Case number should be provided by controller
            // If not provided, generate a fallback
            if (empty($case->case_number)) {
                $case->case_number = self::generateCaseNumber(null, null, $case->firm_id);
            }
        });
    }

    /**
     * Generate unique case number with format: YEAR-SECTIONCODE-RUNNINGNUMBER-CLIENTABBR
     * Example: 2025-CA-1-MFBAR
     *
     * @param int|string|null $sectionTypeIdOrName The section type ID or name (for backward compatibility)
     * @param string|null $clientName The first plaintiff/applicant name
     * @param int|null $firmId The firm ID
     * @return string
     */
    public static function generateCaseNumber($sectionTypeIdOrName = null, $clientName = null, $firmId = null)
    {
        $year = date('Y');

        // Get section code
        $sectionCode = 'XX'; // Default if no section
        if ($sectionTypeIdOrName) {
            // Check if it's an ID (numeric) or name (string)
            if (is_numeric($sectionTypeIdOrName)) {
                // It's an ID
                $sectionType = \App\Models\SectionType::withoutGlobalScope(\App\Scopes\FirmScope::class)
                    ->find($sectionTypeIdOrName);
                if ($sectionType) {
                    $sectionCode = strtoupper($sectionType->code);
                }
            } else {
                // It's a name (backward compatibility for old "civil", "criminal" values)
                $sectionType = \App\Models\SectionType::withoutGlobalScope(\App\Scopes\FirmScope::class)
                    ->where('name', 'LIKE', $sectionTypeIdOrName . '%')
                    ->first();
                if ($sectionType) {
                    $sectionCode = strtoupper($sectionType->code);
                } else {
                    // Fallback: use first 2 letters of the name
                    $sectionCode = strtoupper(substr($sectionTypeIdOrName, 0, 2));
                }
            }
        }

        // Get firm ID from session or auth user if not provided
        if (!$firmId) {
            $firmId = session('current_firm_id') ?? auth()->user()->firm_id ?? null;
        }

        // Get running number (per year + section + firm)
        $runningNumber = self::getNextRunningNumber($year, $sectionCode, $firmId);

        // Get client abbreviation
        $clientAbbr = self::generateClientAbbreviation($clientName);

        // Format: YEAR-SECTIONCODE-RUNNINGNUMBER-CLIENTABBR
        $caseNumber = "{$year}-{$sectionCode}-{$runningNumber}-{$clientAbbr}";

        return $caseNumber;
    }

    /**
     * Get next running number for the year, section, and firm
     *
     * @param string $year
     * @param string $sectionCode
     * @param int|null $firmId
     * @return int
     */
    private static function getNextRunningNumber($year, $sectionCode, $firmId = null)
    {
        // Find the highest running number for this year + section + firm
        $query = self::withoutGlobalScope(\App\Scopes\FirmScope::class)
            ->where('case_number', 'LIKE', "{$year}-{$sectionCode}-%");

        if ($firmId) {
            $query->where('firm_id', $firmId);
        }

        $lastCase = $query->orderBy('id', 'desc')->first();

        if (!$lastCase) {
            return 1; // First case for this year + section + firm
        }

        // Extract running number from case_number (format: YEAR-SECTION-NUMBER-ABBR)
        $parts = explode('-', $lastCase->case_number);
        if (count($parts) >= 3) {
            $lastNumber = intval($parts[2]);
            return $lastNumber + 1;
        }

        return 1;
    }

    /**
     * Generate client abbreviation from name
     * Takes first letter of each word in the name
     * Example: "MOHAMAD FAIZAN BIN ABDUL RAHMAN" -> "MFBAR"
     *
     * @param string|null $name
     * @return string
     */
    public static function generateClientAbbreviation($name = null)
    {
        if (!$name) {
            return 'XXXX'; // Default if no name provided
        }

        // Remove common prefixes/titles
        $name = preg_replace('/^(MR\.?|MRS\.?|MS\.?|DR\.?|PROF\.?)\s+/i', '', $name);

        // Split by spaces and get first letter of each word
        $words = preg_split('/\s+/', strtoupper(trim($name)));
        $abbreviation = '';

        foreach ($words as $word) {
            if (!empty($word)) {
                $abbreviation .= substr($word, 0, 1);
            }
        }

        // Ensure minimum 2 characters, maximum 10 characters
        if (strlen($abbreviation) < 2) {
            $abbreviation = str_pad($abbreviation, 2, 'X');
        } elseif (strlen($abbreviation) > 10) {
            $abbreviation = substr($abbreviation, 0, 10);
        }

        return $abbreviation;
    }

    /**
     * Generate random alphanumeric string (uppercase)
     *
     * @param int $length
     * @return string
     */
    private static function generateUniqueId($length = 6)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public function parties()
    {
        return $this->hasMany(CaseParty::class, 'case_id');
    }

    public function plaintiffs()
    {
        return $this->parties()->where('party_type', 'plaintiff');
    }

    public function defendants()
    {
        return $this->parties()->where('party_type', 'defendant');
    }

    public function applicants()
    {
        return $this->parties()->where('party_type', 'applicant');
    }

    public function partners()
    {
        return $this->hasMany(CasePartner::class, 'case_id');
    }

    public function caseType()
    {
        return $this->belongsTo(CaseType::class, 'case_type_id');
    }

    public function caseStatus()
    {
        return $this->belongsTo(CaseStatus::class, 'case_status_id');
    }

    public function sectionType()
    {
        return $this->belongsTo(SectionType::class, 'section');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function files()
    {
        return $this->hasMany(CaseFile::class, 'case_ref', 'case_number');
    }
    
    // Accounting relations for totals
    public function taxInvoices()
    {
        return $this->hasMany(TaxInvoice::class, 'case_id');
    }
    
    public function receipts()
    {
        return $this->hasMany(Receipt::class, 'case_id');
    }
    
    public function timeline()
    {
        return $this->hasMany(CaseTimeline::class, 'case_id')->orderBy('event_date', 'asc');
    }

    public function calendarEvents()
    {
        return $this->hasMany(CalendarEvent::class, 'case_id')->orderBy('start_date', 'asc');
    }



    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['case_number', 'title', 'case_type_id', 'case_status_id', 'court_location', 'claim_amount'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
