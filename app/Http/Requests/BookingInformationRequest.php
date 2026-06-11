<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingInformationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'phone'          => [
                'required',
                'string',
                'regex:/^\+?\d{9,15}$/',
            ],
            'event_class_id' => 'required|exists:event_classes,id',
            'jersey_size'    => 'required|string|in:XS,S,M,L,XL,XXL',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required'           => 'Nama lengkap wajib diisi.',
            'email.required'          => 'Email wajib diisi.',
            'email.email'             => 'Format email tidak valid.',
            'phone.required'          => 'Nomor telepon wajib diisi.',
            'phone.regex'             => 'Format nomor telepon tidak valid. Contoh: +6281234567890',
            'event_class_id.required' => 'Kategori kelas wajib dipilih.',
            'event_class_id.exists'   => 'Kategori kelas tidak valid.',
            'jersey_size.required'    => 'Ukuran jersey wajib dipilih.',
            'jersey_size.in'          => 'Ukuran jersey tidak valid.',
        ];
    }
}
