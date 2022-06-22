<?php

namespace App\Http\Requests\Verification;

use App\Enums\VerificationType;
use App\Models\Verification;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class VerifyRequest extends FormRequest
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
            Verification::TOKEN => 'required',
            Verification::TYPE  => ['required', new EnumValue(VerificationType::class)],
        ];
    }
}
