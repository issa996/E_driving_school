<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreTestRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'body' => ['required','string'],
            'image' => ['nullable',File::types(['png', 'jpg'])
            ->max(5 * 1024),],
           // 'test_id' => ['required',Rule::exists('tests','id')],
            'answer' => ['array','size:4'],
            'answer.*.image' => ['nullable',File::types(['png', 'jpg'])
            ->max(5 * 1024),],
            'answer.*.is_true' => ['required','boolean'],
            'answer.*.answer' => ['nullable','required'],
          //  'answers.question_id' => ['required',Rule::exists('questions','id')]
            
        ];
    }
}
