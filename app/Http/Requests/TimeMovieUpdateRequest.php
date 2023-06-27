<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TimeMovieUpdateRequest extends TimeMovieRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'movie_id' => 'bail|required|integer|exists:movies,id',
            'room_id' => 'bail|required|integer|exists:rooms,id',
            'time_start' => 'bail|required|date_format:Y-m-d H:i:s|after:today',
            'time_end' => 'bail|required|date_format:Y-m-d H:i:s|after:time_start',
            'status' => ['nullable', Rule::in([0, 1])],
            'price' => 'bail|required|integer|min:0|max:100000000',
        ];
    }
}
