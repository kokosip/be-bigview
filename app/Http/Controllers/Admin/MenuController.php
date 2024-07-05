<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\MenuServices;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    use ApiResponse;
    protected $menuService;

    public function __construct(MenuServices $menuService)
    {
        $this->menuService = $menuService;
    }

    public function addMenu(Request $request) {
        $validator = Validator::make($request->all(), [
            'name_menu' => 'required|string',
            'icon' => 'nullable',
            'link' => 'required',
            'id_parent' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $this->menuService->insertMenu($validator->validate());
        return $this->successResponse($data);
    }

    public function menuUtama(Request $request){
        $isSubmenu = $request->boolean('submenu') ? true : null;

        $data = $this->menuService->getMenuUtama($isSubmenu);
        return $this->successResponse(data: $data);
    }

    public function listMenu(Request $request){
        $search = $request->input("search");
        $perPage = is_null($request->input('per_page')) ? 10 : $request->input('per_page');

        [$data, $metadata] = $this->menuService->getListMenu($search, $perPage);
        return $this->successResponse(data: $data, metadata: $metadata);
    }

    public function getMenuById($id_menu){
        $data = $this->menuService->getMenuById($id_menu);
        return $this->successResponse(data: $data);
    }

    public function deleteMenu($id_menu){
        $this->menuService->deleteMenu($id_menu);
        return $this->successResponse(message: "Data Berhasil dihapus");
    }

    public function updateMenu(Request $request, $id_menu){
        $validator = Validator::make($request->all(), [
            'name_menu' => 'required|string',
            'icon' => 'required',
            'link' => 'required',
            'id_parent' => 'required',
            'sort' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $this->menuService->updateMenu($validator->validate(), $id_menu);
        return $this->successResponse(message: "Data Berhasil di Update");
    }
}