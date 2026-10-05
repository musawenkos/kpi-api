<?php
namespace App\Exception;

use App\Enum\KpiStatus;
use DomainException;
use Illuminate\Http\JsonResponse;
use Throwable;
use Override;

class InvalidKpiTransition extends DomainException
{
    
    public function __construct(public KpiStatus $from, public KpiStatus $to)
    {
        return parent::__construct("A KPI cannot move from {$from->value} to {$to->value}.");
    }

    public function render(): JsonResponse
    {
        return response()->json(['message' => $this->getMessage()], 409);
    }

}


?>