<?php
namespace App\Enum;

enum KpiStatus: string
{
    case Assigned = 'assigned';
    case Submitted = 'submitted';
    case Approved = 'approved';
    case Rejected = 'rejected';

    public function canTransitionTo(self $to) : bool
    {
        $allowed = match($this){
            self::Assigned => [self::Submitted],
            self::Submitted => [self::Approved, self::Rejected],
            self::Rejected => [self::Submitted],
            self::Approved =>[]
        };
        return in_array($to, $allowed, true);
    }
}



?>