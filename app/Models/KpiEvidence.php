<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Observers\KpiEvidenceObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;


#[ObservedBy([KpiEvidenceObserver::class])]
class KpiEvidence extends Model
{
    protected $table = 'kpi_evidence';
    protected $fillable = [
        'kpi_id', 'evidence_name', 'captured_by', 'captured_date',
        'path', 'mime_type', 'size_bytes', 'sha256',
    ];

    
    public function casts() : array
    {
        return ['captured_date'=> 'datetime'];    
    }

    public function kpi(): BelongsTo
    {
        return $this->belongsTo(Kpi::class,'kpi_id');
    }

    public function captureBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'captured_by');
    }


}
