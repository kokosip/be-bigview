<?php

namespace App\Repositories\Admin;

use Exception;
use App\Exceptions\ErrorResponse;
use Illuminate\Support\Facades\DB;

class UsecaseRepositories {

    public function getListUsecase($search, $perPage){
        try {
            $db = DB::table('usecase')
                ->select('id_usecase', 'name_usecase', 'type_dashboard', 'base_color1', 'base_color2', 'base_color3', 'base_color4');

            if($search){
                $db = $db->whereRaw("name_usecase LIKE ? ", ["%{$search}%"]);
            }

            $result = $db->paginate($perPage, $perPage);
            return $result;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: $e->getMessage());
        }
    }

    public function getListNameUsecase(){
        try {
            $db = DB::table('usecase')
                ->select('id_usecase', 'name_usecase')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: $e->getMessage());
        }
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
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal Menambahkan Usecase Custom Baru.');
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
        try {
            $db = DB::table('usecase')
            ->where('id_usecase', $id_usecase)
            ->update($data);

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal Memperbarui Usecase.');
        }
    }

    public function updateUsecaseGovern($data, $id_usecase){
        try {
            $db = DB::table('usecase_government')
                ->where('id_usecase', $id_usecase)
                ->update($data);

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal Memperbarui Usecase Government.');
        }
    }

    public function updateUsecaseCustom($data, $id_usecase){
        try {
            $db = DB::table('usecase_custom')
            ->where('id_usecase', $id_usecase)
            ->update($data);

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal Memperbarui Usecase Custom.');
        }
    }

    public function getUsecaseById($id_usecase){
        try {
            $db = DB::table('usecase as u')
                ->leftJoin('usecase_government as ug', 'u.id_usecase', '=', 'ug.id_usecase')
                ->leftJoin('usecase_custom as uc', 'u.id_usecase', '=', 'uc.id_usecase')
                ->selectRaw("u.*, ug.kode_provinsi, ug.kode_kab_kota, uc.deskripsi, u.pic_logo, ug.pic_leader, ug.pic_vice")
                ->where('u.id_usecase', $id_usecase)
                ->first();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil usecase');
        }
    }

    public function deleteUsecase($id_usecase){
        try {
            $db = DB::table('usecase')
            ->where('id_usecase', $id_usecase)
            ->delete();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: $e->getMessage());
        }
    }

    public function deleteUsecaseGovern($id_usecase){
        try {
            $db = DB::table('usecase_government')
            ->where('id_usecase', $id_usecase)
            ->delete();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: $e->getMessage());
        }
    }

    public function deleteUsecaseCustom($id_usecase){
        try {
            $db = DB::table('usecase_custom')
            ->where('id_usecase', $id_usecase)
            ->delete();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message: $e->getMessage());
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
        try {
            $getId = DB::table('visi')
                    ->insertGetId($data);

            $getRow = DB::table('visi')
                    ->where('id', $getId)
                    ->first();
            return $getRow;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message: $e->getMessage());
        }
    }

    public function getVisiById($id_visi) {
        try {
            $db = DB::table('visi')
                ->where('id', $id_visi)
                ->first();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message: $e->getMessage(), statusCode: 500);
        }        
    }

    public function updateVisi($data, $id_visi) {
        try {
            DB::table('visi')
            ->where('id', $id_visi)
            ->update($data);
            $updatedRow = DB::table('visi')
                        ->where('id', $id_visi)
                        ->first();
            return $updatedRow;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message: $e->getMessage(), statusCode: 500);
        }
    }

    public function deleteVisi($id_visi) {
        try{
            $db = DB::table('visi')
            ->where('id', $id_visi)
            ->delete();

            return $db;
        }catch(Exception $e){
            throw new ErrorResponse(type: 'Internal Server Error', message: $e->getMessage(), statusCode:500);
        }
    }

    public function getListVisi($id_usecase, $perPage)
    {
        try {
            $db = DB::table('visi')
                ->select('short_desc', 'description')
                ->where('id_usecase', $id_usecase)
                ->paginate($perPage);
        
            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: $e->getMessage(), statusCode:500);
        }
    }

    public function addMisi($data) {
        try {
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

            return $getRow;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: $e->getMessage(), statusCode:500);
        }
    }

    public function updateMisi($id_misi, $data) {
        try {
            $oldUrutan = DB::table('misi')
                        ->where('id', $id_misi)
                        ->value('urutan');
            $urutanTertinggi = DB::table('misi')
                            ->max('urutan') ?? 0;
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

            DB::table('misi')
                ->where('id', $id_misi)
                ->update($data);

            $updatedRow = DB::table('misi')
                ->where('id', $id_misi)
                ->first();

            return $updatedRow;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: $e->getMessage(), statusCode:500);
        }
    }
    
    

    public function deleteMisi($id_misi) {
        try {
            $misiToDelete = DB::table('misi')->where('id', $id_misi)->first();
        
            $urutanToDelete = $misiToDelete->urutan;

            $result = DB::table('misi')->where('id', $id_misi)->delete();
            DB::table('misi')
                ->where('urutan', '>', $urutanToDelete)
                ->decrement('urutan');

            return $result;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: $e->getMessage(), statusCode:500);
        }
    }

    public function getMisiById($id_misi) {
        try {
            $misi = DB::table('misi')->where('id', $id_misi)->first();
            return $misi;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: $e->getMessage(), statusCode:500);
        }
    }

    public function getListMisi($id_Usecase, $perPage) {
        try {
            $db = DB::table('misi')
                ->select('urutan','short_desc', 'description')
                ->where('id_usecase', $id_Usecase)
                ->paginate($perPage);

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: $e->getMessage(), statusCode:500);
        }
    }

    public function addSektor($data) {
        try {
            $getId = DB::table('sektor')->insertGetId($data);
            $getRow = DB::table('sektor')
                        ->where('id', $getId)
                        ->first();
        
            return $getRow;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: $e->getMessage(), statusCode:500);
        }
    }
    
    public function getListSektor($id_sektor) {
        try {
            $db = DB::table('master_sector_cms')
                ->where('id_usecase', $id_sektor)
                ->orderBy('sector')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: $e->getMessage(), statusCode:500);
        }
    }

    public function getListDataSektor($id_usecase, $sektor) {
        try {
            $db = DB::table('iku_indikator_bigbox as iib')
                ->select('id', 'urusan AS sektor', 'opd', 'indikator', 'satuan', 'tahun', 'nilai', 'flag_public')
                ->where('id_usecase', $id_usecase)
                ->where('urusan', $sektor)
                ->orderBy('indikator')
                ->orderBy('tahun')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: $e->getMessage(), statusCode:500);
        }
    }

    public function getListIndikator($id_usecase, $sektor) {
        try {
            $db = DB::table('mart_detail_ikk_cms as mdic')
                ->select('indikatorkinerja')
                ->whereNotNull('sektor')
                ->where('sektor', $sektor)
                ->orderBy('indikatorkinerja')
                ->distinct()
                ->get();
            
            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: $e->getMessage(), statusCode:500);
        }
    }

    public function getListSatuan() {
        try {
            $db = DB::table('master_satuan_cms')
                ->orderBy('satuan', 'asc')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: $e->getMessage(), statusCode:500);
        }
    }

    public function getListOpd($id_usecase, $sektor) {
        try {
            $db = DB::table('master_opd')
                ->selectRaw('id_opd, opd')
                ->where('id_usecase', $id_usecase)
                ->where('sector', $sektor)
                ->orderBy('opd', 'asc')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: $e->getMessage(), statusCode:500);
        }
    }

    public function getProvinsiKotaByIdUsecase($id_usecase) {
        try {
            $db = DB::table('usecase_government as u')
                ->select(
                    'gpk.kode_provinsi_bps',
                    'gpk.kode_provinsi',
                    'gpk.kode_kab_kota_bps',
                    'gpk.kode_kab_kota',
                    'gpk.nama_provinsi',
                    'gpk.nama_kab_kota'
                )
                ->selectRaw("CASE
                    WHEN RIGHT(gpk.kode_kab_kota_bps, 2) = '00' THEN 'Provinsi'
                    WHEN LEFT(RIGHT(gpk.kode_kab_kota_bps, 2), 1) = '7' THEN 'Kota'
                    ELSE 'Kabupaten'
                    END as kategori")
                ->leftJoin('geo_provinsi_kota as gpk', function ($join) {
                    $join
                        ->on('gpk.kode_provinsi_bps', '=', 'u.kode_provinsi')
                        ->on('gpk.kode_kab_kota_bps', '=', 'u.kode_kab_kota');
                })
                ->where('u.id_usecase', '=', $id_usecase)
                ->first();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: $e->getMessage(), statusCode:500);
        }
    }

    public function addSektorIku($data) {
        try {
            $getId = DB::table('iku_indikator_bigbox')->insertGetId($data);
    
            $getRow = DB::table('iku_indikator_bigbox')
                    ->where('id', $getId)
                    ->first();
        
            return $getRow;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: $e->getMessage(), statusCode:500);
        }
    }

    public function updateSektorIku($data, $id_sektor) {
        try {
            DB::table('iku_indikator_bigbox')
                ->where('id', $id_sektor)
                ->update($data);

            $getRow = DB::table('iku_indikator_bigbox')
                    ->where('id', $id_sektor)
                    ->first();
                    
            return $getRow;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: $e->getMessage(), statusCode:500);
        }
    }

    public function deleteSektorIku($id_sektor) {
        try {
            $sektorToDelete = $this->getSektorIkuById($id_sektor);

            if (!$sektorToDelete) {
                throw new Exception('Sektor IKU tidak ditemukan.');
            }

            $result = DB::table('iku_indikator_bigbox')
                    ->where('id', $id_sektor)
                    ->delete();

            return $result;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: $e->getMessage(), statusCode:500);
        }
    }

    public function getSektorIkuById($id_sektor) {
        try {
            $result = DB::table('iku_indikator_bigbox')
                    ->where('id', $id_sektor)
                    ->first();

            return $result;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: $e->getMessage(), statusCode:500);
        }
    }

    public function getMaxIkk($sektor) {
        try {
            $result = DB::table('mart_detail_ikk_cms')
                    ->where('sektor', $sektor)
                    ->orderBy('no_urut', 'desc')
                    ->first();

            return $result;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: $e->getMessage(), statusCode:500);
        }
    } 

    public function addIndikator($data) {
        try {
            $getId = DB::table('mart_detail_ikk_cms')->insertGetId($data);
    
            $getRow = DB::table('mart_detail_ikk_cms')
                    ->where('id', $getId)
                    ->first();
        
            return $getRow;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: $e->getMessage(), statusCode:500);
        }
    }
}


