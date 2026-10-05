<?php

namespace App\Services;

use App\Enum\KpiStatus;
use App\Exception\InvalidKpiTransition;
use App\Models\Kpi;
use Illuminate\Support\Facades\DB;

class KpiWorkflow
{
    public function transition(Kpi $kpi, KpiStatus $to, ?string $notes = null): Kpi
    {
        return DB::transaction(function () use ($kpi, $to, $notes) {
            // Re-read the row and lock it, so two requests cannot both pass the check.
            $locked = Kpi::whereKey($kpi->id)->lockForUpdate()->firstOrFail();


            if (! $locked->status->canTransitionTo($to)) {
                throw new InvalidKpiTransition($locked->status, $to);
            }

            $locked->update([
                'status' => $to,
                'notes'  => $notes ?? $locked->notes,
            ]);

            return $locked;
        });
    }
}