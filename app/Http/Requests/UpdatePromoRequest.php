<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePromoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'value' => $this->value ? str_replace('.', '', $this->value) : null,
            'min_order' => $this->min_order ? str_replace('.', '', $this->min_order) : null,
            'max_discount' => $this->max_discount ? str_replace('.', '', $this->max_discount) : null,
            'code' => $this->code ? strtoupper($this->code) : null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $promoId = $this->route('promo')->id;

        return [
            'code' => 'required|string|max:50|alpha_dash:ascii|unique:promos,code,' . $promoId,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:1',
            'min_order' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'service_id' => 'nullable|exists:services,id',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Kode promo wajib diisi.',
            'code.unique' => 'Kode promo sudah digunakan.',
            'code.alpha_dash' => 'Kode promo hanya boleh huruf, angka, dan underscore.',
            'name.required' => 'Nama promo wajib diisi.',
            'type.required' => 'Tipe diskon wajib dipilih.',
            'type.in' => 'Tipe diskon tidak valid.',
            'value.required' => 'Nilai diskon wajib diisi.',
            'value.min' => 'Nilai diskon minimal 1.',
            'end_date.after_or_equal' => 'Tanggal berakhir harus setelah atau sama dengan tanggal mulai.',
        ];
    }

    /**
     * Get validated data with is_active properly set.
     */
    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated($key, $default);
        $validated['is_active'] = $this->boolean('is_active', true);
        return $validated;
    }
}
