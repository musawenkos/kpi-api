<?php

namespace App\Policies;

use App\Models\Kpi;
use App\Models\User;
use App\Enum\KpiStatus;

class KpiPolicy
{
    public function view(User $user, Kpi $kpi): bool
    {
        return $this->isAssignee($user, $kpi) || $this->isLeaderOfAssignee($user, $kpi);
    }

    public function submit(User $user, Kpi $kpi): bool
    {
        return $this->isAssignee($user, $kpi);
    }

    public function review(User $user, Kpi $kpi): bool
    {
        return $this->isLeaderOfAssignee($user, $kpi);
    }

    private function isAssignee(User $user, Kpi $kpi): bool
    {
        return (int) $kpi->assigned_to === $user->id;
    }

    private function isLeaderOfAssignee(User $user, Kpi $kpi): bool
    {
        $kpi->loadMissing('assignee');   // explicit eager load, so lazy-loading protection stays happy

        return (int) $kpi->assignee->leader_id === $user->id;
    }

    public function uploadEvidence(User $user, Kpi $kpi): bool
    {
        return $this->isAssignee($user, $kpi)
            && in_array($kpi->status, [KpiStatus::Assigned, KpiStatus::Rejected], true);
    }
}