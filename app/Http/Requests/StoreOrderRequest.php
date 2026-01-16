<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
            'service_id' => 'required|exists:services,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|regex:/^[0-9]+$/',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'city' => 'nullable|string|max:100',
            'ac_type' => 'required|in:split,cassette,standing,central,window',
            'ac_capacity' => 'required|in:0.5pk,0.75pk,1pk,1.5pk,2pk,2.5pk,3pk,5pk',
            'ac_quantity' => 'required|integer|min:1|max:10',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'scheduled_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
            'promo_code' => 'nullable|string|exists:promos,code',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'service_id.required' => 'Silakan pilih layanan terlebih dahulu.',
            'service_id.exists' => 'Layanan yang dipilih tidak valid.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'phone.regex' => 'Nomor WhatsApp hanya boleh berisi angka.',
            'address.required' => 'Alamat wajib diisi.',
            'ac_type.required' => 'Tipe AC wajib dipilih.',
            'ac_capacity.required' => 'Kapasitas AC wajib dipilih.',
            'scheduled_date.required' => 'Tanggal layanan wajib diisi.',
            'scheduled_date.after_or_equal' => 'Tanggal layanan tidak boleh di masa lalu.',
            'scheduled_time.required' => 'Waktu layanan wajib dipilih.',
            'promo_code.exists' => 'Kode promo tidak valid.',
        ];
    }
}
