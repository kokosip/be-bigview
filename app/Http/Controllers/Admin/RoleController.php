<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\MenuServices;
use App\Services\Admin\RoleServices;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    use ApiResponse;
    protected $roleService;
    protected $menuService;

    public function __construct(RoleServices $roleService, MenuServices $menuService)
    {
        $this->roleService = $roleService;
        $this->menuService = $menuService;
    }

    public function addRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_role' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->roleService->insertRole($validator->validate());
        return $this->successResponse($data);
    }

    public function listRole(Request $request)
    {
        $search = $request->input("search");
        $perPage = is_null($request->input('per_page')) ? 10 : $request->input('per_page');

        [$data, $metadata] = $this->roleService->getListRole($search, $perPage);
        return $this->successResponse(data: $data, metadata: $metadata);
    }

    public function listNamesRole()
    {
        $data = $this->roleService->getListNameRole();
        return $this->successResponse(data: $data);
    }

    public function getRoleById($id_role)
    {
        $data = $this->roleService->getRoleById($id_role);
        return $this->successResponse(data: $data);
    }

    public function deleteRole($id_role)
    {
        $this->roleService->deleteRole($id_role);
        return $this->successResponse(message: "Data Berhasil dihapus");
    }

    public function updateRole(Request $request, $id_role)
    {
        $validator = Validator::make($request->all(), [
            'nama_role' => 'required|string'
        ]);
        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $this->roleService->updateRole($validator->validate(), $id_role);
        return $this->successResponse(message: "Data Berhasil di Update");
    }

    public function listRoleMenu(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_role' => 'required',
            'id_parent' => 'nullable'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->menuService->listRoleMenu($validator->validate());
        return $this->successResponse(data: $data);
    }

    public function addRoleMenu(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_role' => 'required',
            'id_menu' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $this->menuService->addRoleMenu($validator->validate());
        return $this->successResponse(message: "Role Menu Berhasil ditambahkan");
    }

    public function deleteRoleMenu(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_role' => 'required',
            'id_menu' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $this->menuService->deleteRoleMenu($validator->validate());
        return $this->successResponse(message: "Role Menu Berhasil dihapus");
    }
}
