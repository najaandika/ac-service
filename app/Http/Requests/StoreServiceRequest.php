<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
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
        // Clean price format (remove dots used as thousand separator)
        if ($this->has('prices')) {
            $cleanedPrices = [];
            foreach ($this->input('prices') as $key => $value) {
                $cleanedPrices[$key] = (int) str_replace(['.', ','], '', $value);
            }
            $this->merge(['prices' => $cleanedPrices]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:services',
            'description' => 'required|string',
            'duration_minutes' => 'required|integer|min:15',
            'icon' => 'required|string|max:50',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'prices' => 'required|array',
            'prices.*' => 'required|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama layanan wajib diisi.',
            'name.unique' => 'Nama layanan sudah digunakan.',
            'description.required' => 'Deskripsi layanan wajib diisi.',
            'duration_minutes.required' => 'Durasi layanan wajib diisi.',
            'duration_minutes.min' => 'Durasi minimal 15 menit.',
            'icon.required' => 'Icon layanan wajib dipilih.',
            'prices.required' => 'Harga layanan wajib diisi.',
            'prices.*.required' => 'Semua kapasitas harus memiliki harga.',
            'prices.*.min' => 'Harga tidak boleh negatif.',
        ];
    }

    /**
     * Get validated data with proper is_active handling.
     */
    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated($key, $default);
        $validated['is_active'] = $this->boolean('is_active', true);
        $validated['features'] = array_filter($validated['features'] ?? []);
        return $validated;
    }
}
