<?php

namespace App\Exceptions\Verification;


use Exception;

class VerificationCodeWrongException extends Exception
{
    protected $code = 403;
    protected $message = 'VerificationCodeWrong';

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
