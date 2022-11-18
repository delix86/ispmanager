<?php

namespace App\Http\Requests;

class TasksIndexRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'users_ids' => 'array',
            'users_ids.*' => 'required|int|min:1',
            'logins' => 'array',
            'logins.*' => 'min:1',
            'search' => 'max:1000',
            'date' => 'date_format:Y-m-d',
            'states' => 'array',
            'states_ids.*' => 'required|int|min:1',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
