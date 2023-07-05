<?php

namespace App\Http\Requests;

use App\Models\OrderMovie;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderMovieRequest extends BaseApiRequest
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
            'user_id' => 'required|exists:users,id',
            'coupon_id' => 'nullable|exists:coupons,id',
            'time_id' => 'required|exists:time_movies,id',
            'status' => [
                'required',
                Rule::in(OrderMovie::STATUS)
            ],
            'no_chair' => 'required|array',
            'no_chair.*' => 'required|integer|distinct|min:1',
        ];
    }

    public function attributes() {
        return [
            'user_id' => 'user',
            'coupon_id' => 'coupon',
            'time_id' => 'time',
            'no_chair' => 'no chair',
            'no_chair.*' =>'no chair',
        ];
    }
}
