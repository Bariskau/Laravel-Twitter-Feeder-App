<?php

namespace App\Http\Services;

use App\Enums\VerificationType;
use App\Exceptions\BaseException;
use App\Jobs\UserTweetsJob;
use App\Models\User;
use App\Notifications\WelcomeUser;
use Illuminate\Support\Facades\Session;

class UserService
{

    /**
     * @param array $req
     * @return mixed
     * @throws \Throwable
     */
    public function login(array $req)
    {
        $credentials = [
            User::EMAIL    => $req[User::EMAIL],
            User::PASSWORD => $req[User::PASSWORD]
        ];

        $sessionId = Session::getId();

        if (!$token = auth()->claims([User::SESSION_ID => $sessionId])->attempt($credentials)) {
            throw new BaseException(__('exceptions.credentials_wrong'), 401);
        }

        if (auth()->check()) {
            auth()->user()->update([User::SESSION_ID => $sessionId]);
        }

        return $this->respondWithToken($token);
    }

    /**
     * @param $data
     */
    public function register($data)
    {
        $user = new User();
        $user->{User::NAME} = $data[User::NAME];
        $user->{User::EMAIL} = $data[User::EMAIL];
        $user->{User::PASSWORD} = $data[User::PASSWORD];
        $user->{User::PHONE_NUMBER} = $data[User::PHONE_NUMBER];
        $user->{User::TWITTER_ADDRESS} = $data[User::TWITTER_ADDRESS];
        $user->save();

        UserTweetsJob::dispatchSync($user);

        $this->sendUserVerificationCodes($user);
    }

    /**
     * @param User $user
     */
    public function sendUserVerificationCodes(User $user)
    {
        $verificationService = new VerificationService();
        $types = VerificationType::getValues();

        $codes = array();

        foreach ($types as $type) {
            $codes[$type] = $verificationService->generate($user, $type);
        }

        $user->notify(new WelcomeUser($codes[VerificationType::email], $codes[VerificationType::sms]));
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60
        ])->header('Authorization', $token);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->guard('api')->logout();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

}
