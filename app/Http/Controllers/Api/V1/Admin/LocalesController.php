<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocalesController extends Controller
{
    public function languages(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'languages' => config('project.supported_languages'),
            'locale'    => app()->getLocale(),
        ]);
    }

    public function messages(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'auth'       => trans('auth'),
            'cruds'      => trans('cruds'),
            'global'     => trans('global'),
            'pagination' => trans('pagination'),
            'panel'      => trans('panel'),
            'passwords'  => trans('passwords'),
            'validation' => trans('validation'),
        ]);
    }
}
