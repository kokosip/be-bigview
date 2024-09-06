<?php

namespace App\Repositories\Admin;

use Exception;
use App\Exceptions\ErrorResponse;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\error;

class RoleRepositories
{

    public function insertRole($data)
    {
        try {
            DB::table('role')->insert($data);
            return $data;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal menambahkan role.', errors: $e->getMessage());
        }
    }

    public function insertGetRoleId($data)
    {
        try {
            return DB::table('role')->insertGetId($data);
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal menambahkan roles.', errors: $e->getMessage());
        }
    }

    public function getListRole($search, $perPage)
    {
        try {
            $db = DB::table('role')
                ->select('id', 'nama_role');

            if ($search) {
                $db = $db->where('nama_role', 'like', "%{$search}%");
            }
            $result = $db->paginate($perPage, $perPage);
            return $result;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil list role.', errors: $e->getMessage());
        }
    }

    public function getListNameRole()
    {
        try {
            $db = DB::table('role')
                ->select('id', 'nama_role')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil list nama role.', errors: $e->getMessage());
        }
    }

    public function getRoleById($id_role)
    {
        try {
            $db = DB::table('role')
                ->select('id', 'nama_role')
                ->where('id', $id_role)
                ->first();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil role', errors: $e->getMessage());
        }
    }

    public function deleteRole($id_role)
    {
        try {
            $db = DB::table('role')
                ->where('id', $id_role)
                ->delete();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil role.', errors: $e->getMessage());
        }
    }

    public function assignedRoleMenu($data, $id_role)
    {
        try {
            $db = DB::table('role')
                ->where('id', $id_role)
                ->update($data);

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal memperbarui role.', errors: $e->getMessage());
        }
    }

    public function updateRole($data, $id_role)
    {
        try {
            $db = DB::table('role')
                ->where('id', $id_role)
                ->update($data);

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal memperbarui role.');
        }
    }
}
