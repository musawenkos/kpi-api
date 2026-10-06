<?php

namespace App\Observers;

use App\Models\KpiEvidence;
use App\Services\AuditLogger;

class KpiEvidenceObserver
{
    public function __construct(private AuditLogger $audit) {}

    public function created(KpiEvidence $evidence): void
    {
        $this->audit->record('evidence.added', $evidence, null,
            $evidence->only(['kpi_id', 'evidence_name', 'sha256']));
    }
}