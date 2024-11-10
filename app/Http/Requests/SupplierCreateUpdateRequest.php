<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SupplierCreateUpdateRequest extends FormRequest
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
            'first_name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[a-zA-Z0-9\s]+$/'
            ],
            'last_name' => [
                'nullable',
                'string',
                'min:2',
                'max:50',
                'regex:/^[a-zA-Z0-9\s]+$/'
            ],
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'contact_number' => [
                'nullable',
                'digits_between:10,15'
            ],
            'address' => [
                'nullable',
                'string',
                'min:3',
                'max:500'
            ],
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'Please enter your firstname.',
            'first_name.string' => 'First name must be a string.',
            'first_name.min' => 'First name should be at least 2 characters.',
            'first_name.max' => 'First name should not exceed 50 characters.',
            'first_name.regex' => 'First name can only contain alphanumeric characters and spaces.',

            'last_name.required' => 'Please enter your lastname.',
            'last_name.string' => 'Last name must be a string.',
            'last_name.min' => 'Last name should be at least 2 characters.',
            'last_name.max' => 'Last name should not exceed 50 characters.',
            'last_name.regex' => 'Last name can only contain alphanumeric characters and spaces.',

            'email.required' => 'Please enter an email.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email should not exceed 255 characters.',
            'email.unique' => 'This email is already registered.',

            'contact_number.digits_between' => 'Contact number must be between 10 and 15 digits.',

            'address.required' => 'Please enter your address.',
            'address.string' => 'Address must be a string.',
            'address.min' => 'Address should contain at least 3 characters.',
            'address.max' => 'Address should not exceed 500 characters.',
        ];
    }
}
