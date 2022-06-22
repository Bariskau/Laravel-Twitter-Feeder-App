<?php

namespace App\Exceptions;

use Exception;

class InvalidUuidException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => 'The given data was invalid.',
            'errors'  => [
                'uuid' => [
                    $this->message
                ]
            ]
        ], $this->getCode());
    }
}
