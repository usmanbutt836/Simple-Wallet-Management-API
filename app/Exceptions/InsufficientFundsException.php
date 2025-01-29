<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
class InsufficientFundsException extends Exception
{
    public function render(): JsonResponse {
        return response()->json([
            'error' => 'Insufficient funds',
            'message' => 'You do not have enough balance to complete this transaction.'
        ], 400);
    }
}
