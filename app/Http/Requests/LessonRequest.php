<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LessonRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'audio.*' => 'file|max:1024,mimes:mp3',
            'source.*' => 'required',
            'destination.*' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Lesson name',
            'audio.*' => 'Audio #:position',
            'source.*' => 'Text source #:position',
            'destination.*' => 'Text destination #:position',
        ];
    }

    public function messages()
    {
        return [
            'audio.*.max' => 'The Audio #:position field must not be greater than 1MB.',
        ];
    }
}
