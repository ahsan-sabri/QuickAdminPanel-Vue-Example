<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Requests\RegisterUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\Auth\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'roles' => 'required|array',
            'roles.*.id' => 'integer|exists:roles,id',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', (array)$validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $user->roles()->sync($request->input('roles.*.id', []));
        $success['token'] =  $user->createToken('auth_token')->plainTextToken;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * Login api
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', (array)$validator->errors());
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['token'] = $user->createToken('auth_token')->plainTextToken;
            $success['name'] = $user->name;

            return $this->sendResponse($success, 'User login successfully.');
        }

        return $this->sendError('Unauthorized.', ['error'=>'Unauthorised']);
    }

    public function profile(): JsonResponse
    {
        if (auth()->user()) {
            $auth_id = auth()->user()->id;
            $data = User::with('roles')->find($auth_id);
            return $this->sendResponse($data, 'Data fetched successfully!');
        }

        return $this->sendError('Unauthorized.', ['error'=>'Unauthorized']);
    }

    public function profileUpdate(Request $request): JsonResponse
    {
        $this->updateRequestValidate($request);
        $user = User::with(['roles'])->find($request->id);
        $input = $request->all();

        if (Hash::check($request->password, $user->getAuthPassword())) {
            $input['password'] = bcrypt($input['password']);
            if (isset($input['new_password']) && $input['new_password'] !== '') {
                $input['password'] = bcrypt($input['new_password']);
            }
            $user->update($input);

            return $this->sendResponse($user, 'Profile updated successfully.');
        }
        return $this->sendError('Password is incorrect!', [], 200);
    }

    public function updateRequestValidate($request): JsonResponse|bool
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'email' => 'email',
            'password' => 'required|string',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', (array)$validator->errors());
        }
        return true;
    }
}
