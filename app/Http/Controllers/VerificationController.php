<?php

namespace App\Http\Controllers;


use App\Http\Requests\Verification\GenerateRequest;
use App\Http\Requests\Verification\VerifyRequest;
use App\Http\Services\VerificationService;
use App\Models\Verification;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * POST Verify User
     *
     * This endpoint provides the users to verify their email or phone number
     * @authenticated
     *
     * @responseFile 403 storage/responses/post.verificationcode.error.json
     * @response 204
     *
     * @bodyParam type string required email|sms
     * @bodyParam token string required
     *
     * @header Content-Type application/json
     * @header Accept application/json
     */
    public function verify(VerifyRequest $request, VerificationService $verificationService)
    {
        $type = $request->get(Verification::TYPE);
        $token = $request->get(Verification::TOKEN);

        return $verificationService->validate($request->user(), $token, $type);
    }

    /**
     * POST Regenerate Verification Token
     *
     * This endpoint provides the user regenerates validation codes of the specified type
     * @authenticated
     *
     * @responseFile 200 storage/responses/post.verificationgenerate.success.json
     * @responseFile 422 storage/responses/post.verificationgenerate.error.json
     *
     * @bodyParam type string required email|sms
     *
     * @header Content-Type application/json
     * @header Accept application/json
     */
    public function generate(GenerateRequest $request, VerificationService $verificationService)
    {
        $type = $request->get(Verification::TYPE);
        $token = $verificationService->generate($request->user(), $type);

        return response()->json([
            'token' => $token,
            'type'  => $type
        ]);
    }

    /**
     * GET Verification Codes
     *
     * For testing purposes only. Returns valid verification codes for the user's mail or phone numbers.
     * @authenticated
     *
     * @responseFile 200 storage/responses/get.verificationcodes.success.json
     *
     * @header Content-Type application/json
     * @header Accept application/json
     */
    public function getVerificationCodes(Request $request)
    {
        $user = $request->user();

        return response()->json($user->verification);
    }
}
