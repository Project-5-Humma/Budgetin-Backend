<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSavingsGoalRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:150'],
            'target_amount' => ['required', 'numeric', 'min:1'],
            'target_date' => ['nullable', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'name. required' => 'Nama tabungan wajib diisi',
            'target_amount.required' => 'Target tabungan wajib diisi',
            'target_amount.numeric' => 'Target tabungan harus berupa angka.',
            'target_amount.min'=> 'Target tabungan minimal 1.',
            'target_date.after_or_equal'=> 'Tanggal target tidak boleh sebelum hari ini.',
        ];
    }
}
