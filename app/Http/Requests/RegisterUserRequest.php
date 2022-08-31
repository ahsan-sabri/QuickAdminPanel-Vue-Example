<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class RegisterUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'email' => [
                'required',
                'email',
                'unique:users',
            ],
            'password' => [
                'required',
            ],
            'c_password' => [
                'required',
                'same:password'
            ],
            'roles' => [
                'required',
                'array',
            ],
            'roles.*.id' => [
                'integer',
                'exists:roles,id',
            ],
        ];
    }
}
