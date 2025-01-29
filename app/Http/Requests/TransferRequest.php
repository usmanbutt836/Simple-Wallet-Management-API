<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
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
    public function rules(): array {
        return [
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id|different:sender_id',
            'amount' => 'required|numeric|min:0.01',
        ];
    }

    public function messages(): array {
        return [
            'sender_id.required' => 'Sender user ID is required.',
            'sender_id.exists' => 'The sender does not exist.',
            'receiver_id.required' => 'Receiver user ID is required.',
            'receiver_id.exists' => 'The receiver does not exist.',
            'receiver_id.different' => 'Sender and receiver cannot be the same user.',
            'amount.required' => 'Amount to transfer is required.',
            'amount.numeric' => 'The transfer amount must be a number.',
            'amount.min' => 'The transfer amount must be at least 0.01.',
        ];
    }
}
