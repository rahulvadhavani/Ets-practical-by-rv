<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserCreateUpdateRequest extends FormRequest
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
        $userId = request()->id??0;
        return [
            'first_name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[a-zA-Z0-9\s]+$/'
            ],
            'last_name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[a-zA-Z0-9\s]+$/'
            ],
            'email' => [
                'required',
                'email',
                'max:255',
               "unique:users,email,$userId" 
            ],
            'contact_number' => [
                'nullable',
                'digits_between:10,15'
            ],
            'postcode' => [
                'nullable',
                'digits_between:5,10'
            ],
            'hobbies' => [
                'nullable',
                'string',
                'min:2',
                'max:3000'
            ],
            'gender' => [
                'required',
                'in:Male,Female,Other'
            ],
            'state_id' => [
                'nullable',
                'exists:states,id'
            ],
            'city_id' => [
                'nullable',
                'exists:cities,id'
            ],
            'roles' => [
                'required',
                'array',
                'min:1'
            ],
            'roles.*' => [
                'exists:roles,id'
            ],
            'password' => [
                'nullable',
               'required_if:id,0',
                'string',
                'min:6',
                'max:50'
            ],
            'password_confirmation' => [
                'nullable',
                'required_if:id,0',
                'same:password'
            ],
            'image' => [
                'nullable',
                'mimes:jpg,jpeg,png',
                'max:2048' // Max file size of 2MB
            ],
            'files.*' => [
                'nullable',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048' // Max file size of 2MB for each file
            ]
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

            'postcode.digits_between' => 'Postcode must be between 5 and 10 digits.',

            'hobbies.required' => 'Please enter your hobbies.',
            'hobbies.string' => 'Hobbies must be a string.',
            'hobbies.min' => 'Hobbies should contain at least 2 characters.',
            'hobbies.max' => 'Hobbies should not exceed hobbies characters.',

            'gender.required' => 'Please select a gender.',
            'gender.in' => 'Invalid gender selected.',

            'state_id.exists' => 'Selected state is invalid.',
            'city_id.exists' => 'Selected city is invalid.',

            'roles.required' => 'Please select at least one role.',
            'roles.array' => 'Roles must be an array.',
            'roles.min' => 'Please select at least one role.',
            'roles.*.exists' => 'Invalid role selected.',

            'password.required_if' => 'Please enter a password.',
            'password.string' => 'Password must be a string.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.max' => 'Password should not exceed 50 characters.',

            'password_confirmation.required_if' => 'Please confirm your password.',
            'password_confirmation.same' => 'Password and confirmation do not match.',

            'image.mimes' => 'Only image files (jpg, jpeg, png) are allowed.',
            'image.max' => 'Image size must not exceed 2MB.',

            'files.*.mimes' => 'Only jpg, jpeg, png, and pdf files are allowed.',
            'files.*.max' => 'Each file size must not exceed 2MB.'
        ];
    }
}
