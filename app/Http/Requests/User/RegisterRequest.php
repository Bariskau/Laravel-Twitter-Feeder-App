<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            User::NAME            => 'required|string|max:100',
            User::EMAIL           => 'required|email|unique:users,' . User::EMAIL,
            User::PASSWORD        => 'required|min:6|max:50',
            User::PHONE_NUMBER    => 'required|digits:10|unique:users,' . User::PHONE_NUMBER,
            User::TWITTER_ADDRESS => 'required|string|unique:users,' . User::TWITTER_ADDRESS,
        ];
    }
}
