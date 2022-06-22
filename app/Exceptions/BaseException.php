<?php

namespace App\Exceptions;

use Exception;

class BaseException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status'  => 'error',
            'code'    => $this->getCode(),
            'message' => $this->getMessage()
        ], $this->getCode());
    }
}
