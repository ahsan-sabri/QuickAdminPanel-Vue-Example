<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\Admin\RoleResource;
use App\Models\Permission;
use App\Models\Role;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class RolesApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('role_access'), ResponseAlias::HTTP_FORBIDDEN, '403 Forbidden');

        return new RoleResource(Role::with(['permissions'])->get());
    }

    public function store(StoreRoleRequest $request): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try {
            $role = Role::create($request->validated());
            $role->permissions()->sync($request->input('permissions.*.id', []));

            DB::commit();
            return response()->json([
                'success' => true,
                'data'    => new RoleResource($role),
                'message' => "Role created successfully!",
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

    public function create(Role $role): Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        abort_if(Gate::denies('role_create'), ResponseAlias::HTTP_FORBIDDEN, '403 Forbidden');

        return response([
            'meta' => [
                'permissions' => Permission::get(['id', 'title']),
            ],
        ]);
    }

    public function show(Role $role): RoleResource
    {
        abort_if(Gate::denies('role_show'), ResponseAlias::HTTP_FORBIDDEN, '403 Forbidden');

        return new RoleResource($role->load(['permissions']));
    }

    public function update(UpdateRoleRequest $request, Role $role): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try {
            $role->update($request->validated());
            $role->permissions()->sync($request->input('permissions.*.id', []));

            DB::commit();
            return response()->json([
                'success' => true,
                'data'    => new RoleResource($role),
                'message' => "Role updated successfully!",
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

    public function edit(Role $role): Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        abort_if(Gate::denies('role_edit'), ResponseAlias::HTTP_FORBIDDEN, '403 Forbidden');

        return response([
            'data' => new RoleResource($role->load(['permissions'])),
            'meta' => [
                'permissions' => Permission::get(['id', 'title']),
            ],
        ]);
    }

    public function destroy(Role $role): \Illuminate\Http\JsonResponse
    {
        abort_if(Gate::denies('role_delete'), ResponseAlias::HTTP_FORBIDDEN, '403 Forbidden');

        DB::beginTransaction();
        try {
            $role->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Role deleted successfully!",
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
