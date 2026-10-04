<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KpiEvidence extends Model
{
    //
    public function kpis(): BelongsTo
    {
        return $this->belongsTo(Kpi::class,'kpi_id');
    }

    public function captureBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'captured_by');
    }


}
