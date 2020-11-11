<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class WithdrawalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'gt:0']
        ];
    }

    /**
     * Configure the validator instance, to check if user has a valid id.
     * and if request amount it not greater than wallet amount
     *
     * @param Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        $user = Auth::user();

        $validator->after(function ($validator) use ($user){
            if ($this->amount > $user->wallet()){
                $validator->errors()->add('amount', 'Insufficient wallet!');
            }

            if ($this->amount >= config('referral.withdrawal_limit') && ! $user->identity) {
                $validator->errors()->add('amount', 'You cannot withdraw above '.number_format(config('referral.withdrawal_limit')).',
                     please upload your ID to enable you withdraw above '.number_format(config('referral.withdrawal_limit')));
            }
        });
    }
}
