<?php

namespace App\Observers;

use App\Models\Kpi;
use App\Services\AuditLogger;

class KpiObserver
{
    public function __construct(private AuditLogger $audit) {}

    public function created(Kpi $kpi): void
    {
        $this->audit->record('kpi.assigned', $kpi, null,
            $kpi->only(['assigned_to', 'assigned_by', 'description']));
    }

    public function updated(Kpi $kpi): void
    {
        $changes = $kpi->getChanges();
        unset($changes['updated_at']);

        if (! $changes) {
            return;
        }

        $old = [];
        foreach (array_keys($changes) as $field) {
            $old[$field] = $kpi->getRawOriginal($field);
        }

        $action = isset($changes['status']) ? "kpi.{$changes['status']}" : 'kpi.updated';

        $this->audit->record($action, $kpi, $old, $changes);
    }
}