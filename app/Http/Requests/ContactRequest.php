<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required','string','max:120'],
            'email' => ['required','email','max:190'],
            'topic' => ['nullable','string','max:60'],
            'message' => ['required','string','min:10','max:4000'],
            'link' => ['nullable','url','max:500'],
            // honeypot
            'website' => ['nullable','size:0'],
        ];
    }

    public function messages(): array
    {
        return [ 'website.size' => 'Spam detected.' ];
    }
}
