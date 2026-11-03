<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
                function ($attribute, $value, $fail) {
                    $allowedDomains = ['mhs.udb.ac.id'];
                    $emailDomain = strtolower(substr(strrchr($value, '@'), 1));
                    if (!in_array($emailDomain, $allowedDomains)) {
                        $fail('Hanya email resmi kampus (@mhs.udb.ac.id) yang diizinkan.');
                    }
                },
            ],
            'whatsapp_number' => ['nullable', 'string', 'max:20'],
            'major' => ['nullable', 'string', Rule::in(User::MAJORS)],
            'location' => ['nullable', 'string', 'max:150'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'remove_photo' => ['nullable', 'boolean'],
        ];
    }
}
