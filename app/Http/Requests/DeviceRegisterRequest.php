<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeviceRegisterRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'u_id' => ['required', 'integer'],
            'app_id' => ['required', 'integer'],
            'os_id' => ['required', 'integer'],
            'u_id' => ['required', 'integer'],
            'language' => ['required', 'string'],
        ];
    }
}
