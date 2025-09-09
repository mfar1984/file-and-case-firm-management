<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CourtCase extends Model
{
    use HasFactory, LogsActivity;

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
    ];

    protected $casts = [
        'filing_date' => 'date',
        'hearing_date' => 'date',
        'claim_amount' => 'decimal:2',
    ];

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
