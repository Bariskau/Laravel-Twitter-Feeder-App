<?php

namespace App\Http\Services;

use App\Enums\VerificationType;
use App\Exceptions\BaseException;
use App\Exceptions\Verification\VerificationCodeWrongException;
use App\Models\User;
use App\Models\Verification;
use Illuminate\Support\Str;

class VerificationService
{

    /**
     * @param User $user
     * @param string $type
     * @param int $digits
     * @param int $validity
     * @return string
     */
    public function generate(User $user, string $type, int $digits = 6, int $validity = 5): string
    {
        /**
         * If you have a valid token, delete it
         */
        Verification::query()
            ->whereBelongsTo($user)
            ->where(Verification::VALID, true)
            ->where(Verification::TYPE, $type)
            ->delete();

        // Generate random string
        $token = Str::random($digits);

        Verification::query()->create([
            Verification::USER_ID  => $user->id,
            Verification::TOKEN    => $token,
            Verification::TYPE     => $type,
            Verification::VALIDITY => $validity,
        ]);

        return $token;
    }

    /**
     * @param User $user
     * @param string $token
     * @param string $type
     * @return mixed
     * @throws \Throwable
     */
    public function validate(User $user, string $token, string $type): object
    {
        $verification = Verification::query()
            ->where(Verification::USER_ID, $user->id)
            ->where(Verification::TOKEN, $token)
            ->where(Verification::TYPE, $type)
            ->first();

        throw_if(!$verification, new VerificationCodeWrongException('Verification code not found'));

        // Current time
        $now = now();

        $validity = $verification
            ->created_at
            ->addMinutes($verification->validity);

        // Do not accept if the code has expired
        throw_if(strtotime($validity) < strtotime($now), new VerificationCodeWrongException('Verification code expired'));

        $verification->update([Verification::VALIDITY => false]);

        // Fill user if code is valid
        $field = $type === VerificationType::email ? User::EMAIL_VERIFIED_AT : User::SMS_VERIFIED_AT;
        $user->update([$field => $now]);

        return response()->noContent();
    }

    /**
     * @param User $user
     * @return bool
     * @throws BaseException
     */
    public static function checkUser(User $user): bool
    {
        if (!$user->{User::EMAIL_VERIFIED_AT}) {
            throw new BaseException('User email verification not done', 403);
        }

        if (!$user->{User::SMS_VERIFIED_AT}) {
            throw new BaseException('User sms verification not done', 403);
        }

        return true;
    }
}
