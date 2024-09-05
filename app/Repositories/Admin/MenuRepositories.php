<?php

namespace App\Repositories\Admin;

use Exception;
use App\Exceptions\ErrorResponse;
use App\Traits\CheckDatabase;
use Illuminate\Support\Facades\DB;

class MenuRepositories
{

    use CheckDatabase;

    public function insertMenu($data)
    {
        DB::table('menu')->insert($data);

        return $data;
    }

    public function getLatestSort($data)
    {
        $result = DB::table('menu')->selectRaw('max(sort) as sort')
            ->where('id_parent', $data['id_parent'])->first();
        $latesSort = is_null($result->sort) ? 1 : $result->sort + 1;

        return $latesSort;
    }

    public function getMenuUtama($isSubmenu = null)
    {
        try {
            $db = DB::table('menu as a')
                ->leftJoin('menu as b', 'b.id_parent', '=', 'a.id_menu')
                ->selectRaw('DISTINCT a.id_menu, a.name_menu')
                ->where('a.id_parent', 0);

            if (!is_null($isSubmenu)) $db = $db->whereRaw('b.id_menu is not null');
            $db = $db->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan menu utama.');
        }
    }

    public function getListMenu($search, $perPage)
    {
        try {
            $db = DB::table('menu as a')
                ->selectRaw("a.id_menu, a.name_menu, a.id_parent, IFNULL(b.name_menu, '') as parent, a.sort")
                ->leftJoin('menu as b', 'a.id_parent', '=', 'b.id_menu');

            if ($search) {
                $db = $db->whereRaw("a.name_menu like ? OR b.name_menu like ?", ["%{$search}%", "%{$search}%"]);
            }
            $result = $db->paginate($perPage, $perPage);
            return $result;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan list menu.');
        }
    }

    public function getMenuById($id_menu)
    {
        try {
            $db = DB::table('menu')
                ->select('id_menu', 'name_menu', 'icon', 'link', 'id_parent', 'sort')
                ->where('id_menu', $id_menu)
                ->first();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan menu.');
        }
    }

    public function deleteMenu($id_menu)
    {
        try {
            $db = DB::table('menu')
                ->where('id_menu', $id_menu)
                ->delete();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal menghapus menu.');
        }
    }

    public function updateMenu($data, $id_menu)
    {
        try {
            $db = DB::table('menu')
                ->where('id_menu', $id_menu)
                ->update($data);

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal memperbarui menu.');
        }
    }

    public function listRoleMenu($data)
    {
        try {
            $id_parent = 0;
            if (isset($data['id_parent'])) {
                $id_parent = $data['id_parent'];
            }

            $db = DB::table('menu')
                ->select('id_menu', 'name_menu')
                ->where('id_parent', $id_parent)
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil list role menu.');
        }
    }

    public function getMenuByRole($data)
    {
        try {
            $db = DB::table('user_menu')
                ->where('id_role', $data['id_role'])
                ->pluck('id_menu')->toArray();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil menu.');
        }
    }

    public function addRoleMenu($data)
    {
        try {
            $highestOrder = DB::table('user_menu')
                ->where('id_role', $data['id_role'])
                ->max('order');

            $data['order'] = $highestOrder + 1;

            $result = DB::table('user_menu')->insert($data);
            return $result;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal menambahkan role menu.');
        }
    }

    public function deleteRoleMenu($data)
    {
        try {
            $deletedOrder = DB::table('user_menu')
                ->where('id_menu', $data['id_menu'])
                ->where('id_role', $data['id_role'])
                ->value('order');

            $result = DB::table('user_menu')
                ->where('id_menu', $data['id_menu'])
                ->where('id_role', $data['id_role'])
                ->delete();

            DB::table('user_menu')
                ->where('id_role', $data['id_role'])
                ->where('order', '>', $deletedOrder)
                ->decrement('order');
            return $result;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal menghapus role menu.');
        }
    }

    public function checkRoleMenuExist($data)
    {
        try {
            $result = DB::table('user_menu')
                ->select('id_role', 'id_menu')
                ->where('id_role', $data['id_role'])
                ->where('id_menu', $data['id_menu'])
                ->first();

            return $result ? True : False;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil data role menu.');
        }
    }

    public function editSubadminMenu($data, $id_sub, $id_role_admin)
    {
        try {
            DB::table('user_menu')
                ->where('id_role', $id_sub)
                ->whereNotIn('id_menu', $data)
                ->delete();

            $adminAccess = DB::table('user_menu')
                ->where('id_role', $id_role_admin)
                ->orderBy('order', 'asc')
                ->get();

            $sortedData = [];
            foreach ($adminAccess as $adminRow) {
                if (in_array($adminRow->id_menu, $data)) {
                    $sortedData[] = $adminRow->id_menu;
                }
            }

            $accessRows = [];
            $order = 1;
            foreach ($sortedData as $id_menu) {
                $checkAccess = DB::table('user_menu')
                    ->where('id_role', $id_sub)
                    ->where('id_menu', $id_menu)
                    ->first();

                if ($checkAccess) {
                    DB::table('user_menu')
                        ->where('id', $checkAccess->id)
                        ->update(['order' => $order]);

                    $accessRows[] = [
                        'id' => $checkAccess->id,
                        'id_role' => $id_sub,
                        'id_menu' => $id_menu,
                        'order' => $order,
                        'action' => 'updated'
                    ];
                } else {
                    $newId = DB::table('user_menu')->insertGetId([
                        'id_role' => $id_sub,
                        'id_menu' => $id_menu,
                        'order' => $order
                    ]);

                    $accessRows[] = [
                        'id' => $newId,
                        'id_role' => $id_sub,
                        'id_menu' => $id_menu,
                        'order' => $order,
                        'action' => 'inserted'
                    ];
                }
                $order++;
            }
            return $accessRows;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal memperbarui menu subadmin.');
        }
    }

    public function sortMenu($data, $id_role, array $subadmin)
    {
        try {

            DB::table('user_menu')
                ->where('id_role', $id_role)
                ->whereNotIn('id_menu', $data)
                ->delete();

            $existingMenus = DB::table('user_menu')
                ->where('id_role', $id_role)
                ->orderBy('order', 'asc')
                ->get();

            $adminRows = [];
            $orderAdmin = 1;
            foreach ($data as $order => $id_menu) {
                $existingMenu = $existingMenus->firstWhere('id_menu', $id_menu);

                if ($existingMenu) {
                    DB::table('user_menu')
                        ->where('id', $existingMenu->id)
                        ->update(['order' => $orderAdmin]);

                    $adminRows[] = [
                        'id' => $existingMenu->id,
                        'id_role' => $id_role,
                        'id_menu' => $id_menu,
                        'order' => $orderAdmin,
                        'action' => 'updated'
                    ];
                } else {
                    $newId = DB::table('user_menu')->insertGetId([
                        'id_role' => $id_role,
                        'id_menu' => $id_menu,
                        'order' => $orderAdmin
                    ]);

                    $adminRows[] = [
                        'id' => $newId,
                        'id_role' => $id_role,
                        'id_menu' => $id_menu,
                        'order' => $orderAdmin,
                        'action' => 'inserted'
                    ];
                }
                $orderAdmin++;
            }

            foreach ($subadmin as $id_sub) {
                DB::table('user_menu')
                    ->where('id_role', $id_sub)
                    ->whereNotIn('id_menu', $data)
                    ->delete();

                $subAccess = DB::table('user_menu')
                    ->where('id_role', $id_sub)
                    ->get();

                $order = 1;
                foreach ($data as $id_menu) {
                    $existingMenu = $subAccess->firstWhere('id_menu', $id_menu);
                    if ($existingMenu) {
                        DB::table('user_menu')
                            ->where('id_role', $existingMenu->id_role)
                            ->where('id_menu', $id_menu)
                            ->update(['order' => $order]);
                    }
                    $order++;
                }
            }
            return $adminRows;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengurutkan menu.');
        }
    }
    public function getSubMenu($id_menu)
    {
        $menus = DB::table('menu')->where('id_parent', $id_menu)->get();

        $subMenus = [];
        if ($menus != null) {
            foreach ($menus as $menu) {
                $menu->sub_menus = $this->getSubMenu($menu->id_menu);
                $subMenus[] = $menu;
            }
        }

        return $subMenus;
    }

    public function getUserMenu($id_role)
    {
        try {
            $listMenu = DB::table('user_menu')
                ->where('id_role', $id_role)
                ->pluck('id_menu')->toArray();
            $result = [];
            foreach ($listMenu as $id_menu) {
                $menu = DB::table('menu')->where('id_menu', $id_menu)->first();

                if ($menu) {
                    $menu->sub_menus = $this->getSubMenu($menu->id_menu);
                    $result[] = $menu;
                }
            }
            return $result;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan user menu.');
        }
    }

    public function checkSubMenu($id_menu, $check_menu)
    {
        $menus = DB::table('menu')->where('id_parent', $id_menu)->get();

        if ($menus != null) {
            foreach ($menus as $menu) {
                if ($menu->id_menu == $check_menu) {
                    return true;
                }
                if ($this->checkSubMenu($menu->id_menu, $check_menu)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function checkUserAccess($check_menu, $id_role)
    {
        try {
            $listMenu = DB::table('user_menu')
                ->where('id_role', $id_role)
                ->pluck('id_menu')->toArray();

            foreach ($listMenu as $id_menu) {
                if ($check_menu == $id_menu) {
                    return true;
                }

                $menu = DB::table('menu')->where('id_menu', $id_menu)->first();

                if ($menu) {
                    if ($this->checkSubMenu($menu->id_menu, $check_menu)) {
                        return true;
                    }
                }
            }

            return false;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil user access.');
        }
    }
}
