<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\Admin\UserResource;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UsersApiController extends Controller
{
    public function index(): UserResource
    {
        abort_if(Gate::denies('user_access'), ResponseAlias::HTTP_FORBIDDEN, '403 Forbidden');

        return new UserResource(User::with(['roles'])->get());
    }

    public function store(StoreUserRequest $request): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = User::create($request->validated());
            $user->roles()->sync($request->input('roles.*.id', []));

            DB::commit();
            return response()->json([
                    'success' => true,
                    'data'    => new UserResource($user),
                    'message' => "User created successfully!",
                ])
                ->setStatusCode(ResponseAlias::HTTP_CREATED);
        }
        catch(Exception $e) {
            DB::rollback();
            return response()->json([
                    'success' => false,
                    'issue' => $e->getMessage(),
                    'message' => "Something went wrong!"
                ])
                ->setStatusCode(ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function create(User $user): Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        abort_if(Gate::denies('user_create'), ResponseAlias::HTTP_FORBIDDEN, '403 Forbidden');

        return response([
            'meta' => [
                'roles' => Role::get(['id', 'title']),
            ],
        ]);
    }

    public function show(User $user): UserResource
    {
        abort_if(Gate::denies('user_show'), ResponseAlias::HTTP_FORBIDDEN, '403 Forbidden');

        return new UserResource($user->load(['roles']));
    }

    public function update(UpdateUserRequest $request, User $user): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try {
            $user->update($request->validated());
            $user->roles()->sync($request->input('roles.*.id', []));

            DB::commit();
            return response()->json([
                'success' => true,
                'data'    => new UserResource($user),
                'message' => "User updated successfully!",
            ])
            ->setStatusCode(ResponseAlias::HTTP_ACCEPTED);
        }
        catch(Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'issue' => $e->getMessage(),
                'message' => "Something went wrong!"
            ])
            ->setStatusCode(ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function edit(User $user): Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        abort_if(Gate::denies('user_edit'), ResponseAlias::HTTP_FORBIDDEN, '403 Forbidden');

        return response([
            'data' => new UserResource($user->load(['roles'])),
            'meta' => [
                'roles' => Role::get(['id', 'title']),
            ],
        ]);
    }

    public function destroy(User $user): \Illuminate\Http\JsonResponse
    {
        abort_if(Gate::denies('user_delete'), ResponseAlias::HTTP_FORBIDDEN, '403 Forbidden');

        DB::beginTransaction();
        try {
            $user->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "User deleted successfully!",
            ])
            ->setStatusCode(ResponseAlias::HTTP_OK);
        }
        catch(Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'issue' => $e->getMessage(),
                'message' => "Something went wrong!"
            ])
            ->setStatusCode(ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
