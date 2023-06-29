<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CouponRequest extends BaseApiRequest
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
        $id = $this->route('id');
        return [
            'code' => [
                'required', 'string','max:8',
                Rule::unique('coupons')->ignore($id)->where('deleted_at', null),
            ],
            'discount' => 'required|integer|min:0|max:100',
            'max_discount' => 'integer|min:0',
            'salary' => 'integer|min:0',
            'time_start' => 'required|date_format:Y-m-d H:i:s|after:today',
            'time_end' => 'required|date_format:Y-m-d H:i:s|after:time_start',
        ];
    }

    public function attributes()
    {
        return [
            'code' => 'code',
            'discount' => 'discount',
            'max_discount' => 'max discount',
            'salary' => 'salary',
            'time_start' => 'start time',
            'time_end' => 'end time',
        ];
    }
}
