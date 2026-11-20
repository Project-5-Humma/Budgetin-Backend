<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSavingsDepositRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:1'],
            'deposit_date'=> ['required', 'date'],
            'notes' =>['nullable', 'string', 'max:255'],
            'wallet_id' => ['nullable', 'uuid', 'exists:wallets,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Nominal setoran wajib diisi.',
            'amount.numeric' => 'Nominal setoran harus berupa angka.',
            'amount.min' => 'Nominal setoran minimal 1.',
            'deposit_date.required' => 'Tanggal setoran wajib diisi.',
            'deposit_date.date' => 'Format tanggal setoran tidak valid.',
            'wallet_id.uuid' => 'Format wallet ID tidak valid.',
            'wallet_id.exists' => 'Wallet tidak ditemukan.',
            'notes.max' => 'Catatan maksimal 255 karakter.',
        ];
    }
}
