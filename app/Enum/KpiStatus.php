<?php
namespace App\Enum;

enum KpiStatus: string
{
    case Assigned = 'assigned';
    case Submitted = 'submitted';
    case Approved = 'approved';
    case Rejected = 'rejected';
}


?>