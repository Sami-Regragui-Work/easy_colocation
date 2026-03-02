<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'start_at' => 'required|date',
            'payer_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'category_id' => $this->route('category')->id,
        ]);
    }
}
