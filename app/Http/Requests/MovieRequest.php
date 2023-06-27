<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class MovieRequest extends BaseApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'category_id' => 'bail|required|integer|exists:categories,id',
            'name' => 'bail|required|string',
            'description' => 'bail|required|string',
            'author' => 'bail|required|string',
            'time' => 'bail|required|integer|min:10|max:1000',
            'images' => 'bail|array|',
            'images.*' => [
                File::image()->min(1024)->max(12 * 1024)
            ]
        ];
    }

    public function attributes()
    {
        return [
            'category_id' => 'category',
            'images' => 'images',
            'images.*' => 'image',
        ];
    }
}
