<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RoleCreateUpdateRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 200)
        );
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $userId = request()->id ?? 0;
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[a-zA-Z0-9\s]+$/'
            ],

            'description' => [
                'nullable',
                'string',
                'min:3',
                'max:500'
            ],
            'permissions' => [
                'nullable',
                'array',
            ],
            'permissions.*' => [
                'integer',
                'exists:permissions,id',
            ],
        ];
    }

    public function messages()
    {
        return [
            // Name validation messages
            'name.required' => 'Please enter your name.',
            'name.string' => 'Name must be a string.',
            'name.min' => 'Name should be at least 2 characters.',
            'name.max' => 'Name should not exceed 50 characters.',
            'name.regex' => 'Name can only contain alphanumeric characters and spaces.',

            // Description validation messages
            'description.string' => 'Description must be a string.',
            'description.min' => 'Description should contain at least 3 characters.',
            'description.max' => 'Description should not exceed 500 characters.',

            // Permissions validation messages
            'permissions.array' => 'Permissions must be an array.',
            'permissions.*.integer' => 'Each permission ID must be an integer.',
            'permissions.*.exists' => 'One or more selected permissions do not exist.',
        ];
    }

    protected function prepareForValidation()
    {
        // Convert permissions to integers if they are provided as strings
        if ($this->has('permissions')) {
            $permissions = array_map('intval', $this->input('permissions', []));
            $this->merge(['permissions' => $permissions]);
        }
    }
}
