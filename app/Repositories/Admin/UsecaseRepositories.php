<?php

namespace App\Repositories\Admin;

use Exception;
use Illuminate\Support\Facades\DB;

class UsecaseRepositories {

    public function getListUsecase($search, $perPage){
        $db = DB::table('usecase')
            ->select('id_usecase', 'name_usecase', 'type_dashboard', 'base_color1', 'base_color2', 'base_color3', 'base_color4');

        if($search){
            $db = $db->whereRaw("name_usecase LIKE ? ", ["%{$search}%"]);
        }

        $result = $db->paginate($perPage, $perPage);
        return $result;
    }

    public function getListNameUsecase(){
        $db = DB::table('usecase')
            ->select('id_usecase', 'name_usecase')
            ->get();

        return $db;
    }

    public function addUsecaseGovernment($data){
        $result = DB::table('usecase_government')->insert($data);

        if($result){
            return $result;
        } else {
            throw new Exception('Gagal Menambahkan Usecase Pemerintah Baru.');
        }
    }

    public function addUsecaseCustom($data){
        $result = DB::table('usecase_custom')->insert($data);

        if($result){
            return $result;
        } else {
            throw new Exception('Gagal Menambahkan Usecase Custom Baru.');
        }
    }

    public function addUsecase($data){
        $result = DB::table('usecase')->insertGetId($data);
        if($result){
            return $result;
        } else {
            throw new Exception('Gagal Menambahkan Usecase Baru.');
        }
    }

    public function updateUsecase($data, $id_usecase){
        $db = DB::table('usecase')
            ->where('id_usecase', $id_usecase)
            ->update($data);

        return $db;
    }

    public function updateUsecaseGovern($data, $id_usecase){
        $db = DB::table('usecase_government')
            ->where('id_usecase', $id_usecase)
            ->update($data);

        return $db;
    }

    public function updateUsecaseCustom($data, $id_usecase){
        $db = DB::table('usecase_custom')
            ->where('id_usecase', $id_usecase)
            ->update($data);

        return $db;
    }

    public function getUsecaseById($id_usecase){
        $db = DB::table('usecase as u')
            ->leftJoin('usecase_government as ug', 'u.id_usecase', '=', 'ug.id_usecase')
            ->leftJoin('usecase_custom as uc', 'u.id_usecase', '=', 'uc.id_usecase')
            ->selectRaw("u.*, ug.kode_provinsi, ug.kode_kab_kota, uc.deskripsi, u.pic_logo, ug.pic_leader, ug.pic_vice")
            ->where('u.id_usecase', $id_usecase)
            ->first();

        return $db;
    }

    public function deleteUsecase($id_usecase){
        $db = DB::table('usecase')
            ->where('id_usecase', $id_usecase)
            ->delete();

        return $db;
    }

    public function deleteUsecaseGovern($id_usecase){
        $db = DB::table('usecase_government')
            ->where('id_usecase', $id_usecase)
            ->delete();

        return $db;
    }

    public function deleteUsecaseCustom($id_usecase){
        $db = DB::table('usecase_custom')
            ->where('id_usecase', $id_usecase)
            ->delete();

        return $db;
    }

    public function addPeriode($data){
        $result = DB::table('periode')->insert($data);
        if($result){
            return $result;
        } else {
            throw new Exception('Gagal Menambahkan Periode.');
        }
    }

    public function getPeriodeById($id_periode) {
        $db = DB::table('periode')
            ->select('id', 'start_year', 'end_year', 'id_usecase')
            ->where('id', $id_periode)
            ->first();

        return $db;
    }

    public function addVisi($data) {
        $result = DB::table('visi')->insert($data);

        $getId = DB::table('visi')
                ->insertGetId($data);

        $getRow = DB::table('visi')
                ->where('id', $getId)
                ->first();

        if($result){
            return $getRow;
        } else {
            throw new Exception('Gagal Menambahkan Visi.');
        }
    }

    public function updateVisi($data, $id_visi) {
        $db = DB::table('visi')
            ->where('id', $id_visi)
            ->update($data);

        if ($db) {
            $updatedRow = DB::table('visi')->where('id', $id_visi)->first();
            return $updatedRow;
        } else {
            throw new Exception('Gagal memperbarui visi');
        }
    }

    public function deleteVisi($id_visi) {
        $db = DB::table('visi')
        ->where('id', $id_visi)
        ->delete();

        return $db;
    }

    public function getListVisi($id_usecase, $perPage)
    {
        $db = DB::table('visi')
            ->select('short_desc', 'description')
            ->where('id_usecase', $id_usecase)
            ->paginate($perPage);
    
        return $db;
    }

    public function addMisi($data) {
        $urutanTertinggi = DB::table('misi')
                        ->max('urutan') ?? 0;
        $data['urutan'] = min($data['urutan'], $urutanTertinggi + 1);
        
        $cekUrutan = DB::table('misi')
                    ->where('urutan', $data['urutan'])
                    ->exists();
    
        if ($cekUrutan) {
            DB::table('misi')
                ->where('urutan', '>=', $data['urutan'])
                ->increment('urutan');
        }

        $getId = DB::table('misi')
                ->insertGetId($data);

        $getRow = DB::table('misi')
                ->where('id', $getId)
                ->first();
    
        if ($getRow) {
            return $getRow;
        } else {
            throw new Exception('Gagal Menambahkan misi');
        }
    }

    public function updateMisi($id_misi, $data) {
        $oldUrutan = DB::table('misi')
                    ->where('id', $id_misi)
                    ->value('urutan');
        $urutanTertinggi = DB::table('misi')->max('urutan') ?? 0;
        $data['urutan'] = min($data['urutan'], $urutanTertinggi);
    
        $isIncreasing = $data['urutan'] > $oldUrutan;
    
        if ($isIncreasing) {
            DB::table('misi')
                ->where('urutan', '>', $oldUrutan)
                ->where('urutan', '<=', $data['urutan'])
                ->decrement('urutan');
        } else {
            DB::table('misi')
                ->where('urutan', '>=', $data['urutan'])
                ->where('urutan', '<', $oldUrutan)
                ->increment('urutan');
        }

        $result = DB::table('misi')
                ->where('id', $id_misi)
                ->update($data);
    
        if ($result) {
            $updatedRow = DB::table('misi')
                ->where('id', $id_misi)
                ->first();
    
            return $updatedRow;
        } else {
            throw new Exception('Gagal Memperbarui misi');
        }
    }
    
    

    public function deleteMisi($id_misi) {
        $misiToDelete = DB::table('misi')->where('id', $id_misi)->first();
        
        if (!$misiToDelete) {
            throw new Exception('Misi tidak ditemukan.');
        }
    
        $urutanToDelete = $misiToDelete->urutan;

        $result = DB::table('misi')->where('id', $id_misi)->delete();

        if ($result) {
            DB::table('misi')
                ->where('urutan', '>', $urutanToDelete)
                ->decrement('urutan');
            return $result;
        } else {
            throw new Exception('Gagal Menghapus misi.');
        }
    }

    public function getMisiById($id_misi) {
        $misi = DB::table('misi')->where('id', $id_misi)->first();

        if ($misi) {
            return $misi; // Return the retrieved record
        } else {
            throw new Exception('Misi tidak ditemukan'); // Throw exception if record not found
        }
    }

    public function getListMisi($id_Usecase, $perPage) {
        $db = DB::table('misi')
            ->select('urutan','short_desc', 'description')
            ->where('id_usecase', $id_Usecase)
            ->paginate($perPage);

        if ($db) {
            return $db;
        } else {
            throw new Exception('Misi tidak ditemukan');
        }
    }
}