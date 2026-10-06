<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditLogger
{
    public function record(string $action, Model $subject, ?array $old = null, ?array $new = null): void
    {
        AuditLog::create([
            'user_id'        => request()->user()->id,
            'action'         => $action,
            'auditable_type' => $subject::class,
            'auditable_id'   => $subject->getKey(),
            'old_values'     => $old,
            'new_values'     => $new,
            'ip_address'     => request()->ip(),
        ]);
    }
}