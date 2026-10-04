<?php

namespace App\Models;

use App\Enum\KpiStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kpi extends Model
{
    /** @use HasFactory<\Database\Factories\KpiFactory> */
    use HasFactory;
    protected $fillable = ['description','assigned_to','assigned_by','assigned_by', 'status', 'notes'];

    public function cast(): array
    {
        return ['status' => KpiStatus::class, 'assigned_date'=> 'date'];
    }
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function evidences(): HasMany
    {
        return $this->hasMany(KpiEvidence::class);
    }
}
