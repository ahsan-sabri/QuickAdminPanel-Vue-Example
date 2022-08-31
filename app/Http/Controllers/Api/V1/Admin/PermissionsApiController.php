<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Resources\Admin\PermissionResource;
use App\Models\Permission;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PermissionsApiController extends Controller
{
    public function index(): PermissionResource
    {
        abort_if(Gate::denies('permission_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PermissionResource(Permission::all());
    }

    public function store(StorePermissionRequest $request): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try {
            $permission = Permission::create($request->validated());

            DB::commit();
            return response()->json([
                'success' => true,
                'data'    => new PermissionResource($permission),
                'message' => "Permission created successfully!",
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

    public function create(Permission $permission): Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        abort_if(Gate::denies('permission_create'), ResponseAlias::HTTP_FORBIDDEN, '403 Forbidden');

        return response([
            'meta' => [],
        ]);
    }

    public function show(Permission $permission): PermissionResource
    {
        abort_if(Gate::denies('permission_show'), ResponseAlias::HTTP_FORBIDDEN, '403 Forbidden');

        return new PermissionResource($permission);
    }

    public function update(UpdatePermissionRequest $request, Permission $permission): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try {
            $permission->update($request->validated());

            DB::commit();
            return response()->json([
                'success' => true,
                'data'    => new PermissionResource($permission),
                'message' => "Permission updated successfully!",
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

    public function edit(Permission $permission): Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        abort_if(Gate::denies('permission_edit'), ResponseAlias::HTTP_FORBIDDEN, '403 Forbidden');

        return response([
            'data' => new PermissionResource($permission),
            'meta' => [],
        ]);
    }

    public function destroy(Permission $permission): \Illuminate\Http\JsonResponse
    {
        abort_if(Gate::denies('permission_delete'), ResponseAlias::HTTP_FORBIDDEN, '403 Forbidden');

        DB::beginTransaction();
        try {
            $permission->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Permission deleted successfully!",
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
