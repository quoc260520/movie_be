<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoomRequest extends BaseApiRequest
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
            'name' => [
                'required',
                 Rule::unique('rooms')->ignore($id)->where('deleted_at', null),
            ],
            'chair_number' => 'required|integer|max:100|min:0',
        ];
    }
    public function attributes() {
        return [
            'name' => 'name room',
            'chair_number' => 'chair number'
        ];
    }
}
