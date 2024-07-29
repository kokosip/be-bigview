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
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil list usecase.');
        }
    }

    public function getListNameUsecase(){
        try {
            $db = DB::table('usecase')
                ->select('id_usecase', 'name_usecase')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil list nama usecase.');
        }
    }

    public function addUsecaseGovernment($data){
        try {
            $result = DB::table('usecase_government')->insert($data);
            return $result;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal menambahkan usecase government.');
        }
    }

    public function addUsecaseCustom($data){
        try {
            $result = DB::table('usecase_custom')->insert($data);
            return $result;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal menambahkan usecase custom.');
        }
    }

    public function addUsecase($data){
        try {
            $result = DB::table('usecase')->insertGetId($data);
            return $result;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal menambahkan usecase.');
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
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil usecase.');
        }
    }

    public function deleteUsecase($id_usecase){
        try {
            $db = DB::table('usecase')
            ->where('id_usecase', $id_usecase)
            ->delete();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal menghapus usecase.');
        }
    }

    public function deleteUsecaseGovern($id_usecase){
        try {
            $db = DB::table('usecase_government')
            ->where('id_usecase', $id_usecase)
            ->delete();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal menghapuse usecase government.');
        }
    }

    public function deleteUsecaseCustom($id_usecase){
        try {
            $db = DB::table('usecase_custom')
            ->where('id_usecase', $id_usecase)
            ->delete();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message: 'Gagal menghapus usecase custom.');
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
            throw new ErrorResponse(type:'Internal Server Error', message: 'Gagal menambahkan visi.');
        }
    }

    public function getVisiById($id_visi) {
        try {
            $db = DB::table('visi')
                ->where('id', $id_visi)
                ->first();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message: 'Gagal mendapatkan visi.');
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
            throw new ErrorResponse(type:'Internal Server Error', message: 'Gagal memperbarui visi');
        }
    }

    public function deleteVisi($id_visi) {
        try{
            $db = DB::table('visi')
            ->where('id', $id_visi)
            ->delete();

            return $db;
        }catch(Exception $e){
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal menghapus visi.');
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
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan list visi.');
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
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan list visi.');
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
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal memperbarui visi.');
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
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal menghapus visi.');
        }
    }

    public function getMisiById($id_misi) {
        try {
            $misi = DB::table('misi')->where('id', $id_misi)->first();
            return $misi;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan misi.');
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
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan list misi.');
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
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil list sektor.');
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
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil list data sektor.');
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
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil list indikator');
        }
    }

    public function getListSatuan() {
        try {
            $db = DB::table('master_satuan_cms')
                ->orderBy('satuan', 'asc')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil list satuan.');
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
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil list OPD');
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
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan provinsi.');
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
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan sektor IKU.');
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
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal memperbarui sektor IKU.');
        }
    }

    public function deleteSektorIku($id_sektor) {
        try {
            $result = DB::table('iku_indikator_bigbox')
                    ->where('id', $id_sektor)
                    ->delete();
            return $result;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal menghapus sektor IKU.');
        }
    }

    public function getSektorIkuById($id_sektor) {
        try {
            $result = DB::table('iku_indikator_bigbox')
                    ->where('id', $id_sektor)
                    ->first();

            return $result;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan sektor IKU.');
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
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan max IKK.');
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
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal menambahkan indikator.');
        }
    }

    public function findUseCase($id_usecase,$select=['*']){
		$data = DB::table('usecase')
                ->where('usecase.id_usecase', $id_usecase)
                ->select($select)
                ->leftJoin('usecase_government', 'usecase.id_usecase', '=', 'usecase_government.id_usecase')
                ->select('usecase.*', 'usecase_government.address as alamat', 'usecase_government.phone as telepon', 'usecase_government.link_map')
                ->first();

		$nama_daerah='';

		if($select[0]!='*' && in_array('kode_provinsi',$select) || $select[0]=='*'){
			$prefix='Gubernur';
            $data_government = DB::table('usecase_government')->where('id_usecase',$id_usecase)->first();
			$daerah=DB::table('geo_provinsi_kota')->where('kode_kab_kota',$data_government->kode_kab_kota)->first();

			// $nama_daerah='Provinsi '.$daerah->nama_provinsi;
			$nama_daerah=$daerah->nama_provinsi ?? '';
			$nama_daerah_title=$daerah->nama_provinsi ?? '';

			$data->level_usecase=1;
			$data->center_lat=$daerah->lat ?? 0;
			$data->center_lon=$daerah->lon ?? 0;

			if($data_government->kode_kab_kota!=$data_government->kode_provinsi.'00'){
				$data->level_usecase=2;
				$data->center_lat=(float)$daerah->lat;
				$data->center_lon=(float)$daerah->lon;

				$kako=DB::table('geo_provinsi_kota')->where('kode_kab_kota',$data_government->kode_kab_kota)->first();
				$nama_daerah=$kako->nama_kab_kota ?? '';
				$nama_kab_kota=explode(' ', $kako->nama_kab_kota);
				$prefix='Bupati';
				if($nama_kab_kota[0]=='Kota'){
					$prefix='Walikota';
				}
				$nama_daerah_title='';
				for ($i=1; $i < count($nama_kab_kota); $i++) { 
					$nama_daerah_title.=' '.$nama_kab_kota[$i];
				}
				
			}

			$data->nama_daerah=$nama_daerah;
			
			$data->title_gubernur=$prefix.' '.$nama_daerah_title;
			$data->title_wakil='Wakil '.$prefix.' '.$nama_daerah_title;
		}
		
		if(($select[0]!='*' && in_array('pic_logo',$select)) || $select[0]=='*'){
			$pic_logo=$data->pic_logo;
			$data->pic_logo=url('/').'/storage/'.$pic_logo;

			if(!is_file(storage_path($pic_logo))){
				$data->pic_logo=url('/images/default/usecase.png');
			}
		}

		if(($select[0]!='*' && in_array('pic_gubernur',$select)) || $select[0]=='*'){
			$pic_gubernur=$data_government->pic_leader;
			$data_government->pic_leader=url('/').'/storage/'.$pic_gubernur;
			if(!is_file(storage_path($pic_gubernur))){
				$data_government->pic_leader=url('/images/default/kd_profile.png');
			}

			$pic_wakil=$data_government->pic_vice;
			$data_government->pic_vice=url('/').'/storage/'.$pic_wakil;
			if(!is_file(storage_path($pic_wakil))){
				$data_government->pic_vice=url('/images/default/kd_profile.png');
			}
		}

		if($select[0]=='*' && $nama_daerah==''){
			$left_logo=url('/images/centryc.jpeg');
			$right_logo=url('/images/logo-g20.png');

			$brands=[
				[
					'brand_url'=>url('/images/logo_bigview.svg'),
					'brand_desc'=>'Logo BigView'
				],
				[
					'brand_url'=>url('/images/logo_centryc.svg'),
					'brand_desc'=>'Logo Centryc'
				],
				[
					'brand_url'=>url('/images/logo_g20.svg'),
					'brand_desc'=>'Logo G20'
				]
			];

			$brands_sm=[
				[
					'brand_url'=>url('/images/logo_bigview_sm.svg'),
					'brand_desc'=>'Logo BigView'
				],
				[
					'brand_url'=>url('/images/logo_centryc_sm.svg'),
					'brand_desc'=>'Logo Centryc'
				],
				[
					'brand_url'=>url('/images/logo_g20.svg'),
					'brand_desc'=>'Logo G20'
				]
			];

            

			$response=[
				'id_usecase'=>$id_usecase,
				'level_usecase'=>0,
				'base_color1'=>$data->base_color1,
				'base_color2'=>$data->base_color2,
				'base_color3'=>$data->base_color3,
				'base_color4'=>$data->base_color4,
				'brands'=>$brands,
				'brands_small'=>$brands_sm,
				'total_brands'=>count($brands),
				'footer_logo'=>[
					[
						'footer_url'=>url('/images/logo_bigbox.svg'),
						'footer_desc'=>'Logo BigBox'
					]
				],
				'left_logo'=>$left_logo,
				'right_logo'=>$right_logo,
				'footer_year'=>date('Y'),
				'footer_brand'=>'TravelAja Centryc | G20 Indonesia'
			];

			return $response;
		}
		
		
		if($id_usecase == 98){
			
			$brands=[
				[
					'brand_url'=>url('/images/logo_bigview.svg'),
					'brand_desc'=>'Logo BigView'
				],
				[
					'brand_url'=>url('/images/logo_centryc.svg'),
					'brand_desc'=>'Logo Centryc'
				],
				[
					'brand_url'=>url('/images/logo_admedika.svg'),
					'brand_desc'=>'Logo Admedika'
				]
			];
		}
		else{
			$brands=[
				[
					'brand_url'=>url('/images/logo_bigview.svg'),
					'brand_desc'=>'Logo BigView'
				],
				[
					'brand_url'=>url('/images/logo_centryc.svg'),
					'brand_desc'=>'Logo Centryc'
				],
			];
		}

		if($id_usecase == 98){
			$brands_sm=[
				[
					'brand_url'=>url('/images/logo_bigview_sm.svg'),
					'brand_desc'=>'Logo BigView'
				],
				[
					'brand_url'=>url('/images/logo_centryc_sm.svg'),
					'brand_desc'=>'Logo Centryc'
				],
				[
					'brand_url'=>url('/images/logo_admedika_sm.svg'),
					'brand_desc'=>'Logo Admedika'
				]
			];
		}
		else{
			$brands_sm=[
				[
					'brand_url'=>url('/images/logo_bigview_sm.svg'),
					'brand_desc'=>'Logo BigView'
				],
				[
					'brand_url'=>url('/images/logo_centryc_sm.svg'),
					'brand_desc'=>'Logo Centryc'
				],
			];
		}
		
		$data->brands=$brands;
		$data->brands_small=$brands_sm;
		$data->total_brands=count($brands);
		$data->footer_logo=[];

		$data->left_logo=url('/images/logo_bigview.png');
		$data->right_logo='';
		$data->footer_year=date('Y');
		$data->footer_brand='BigView';

		return $data;
	}
    public function addSektor($data) {
        try {
            $getId = DB::table('sektor')->insertGetId($data);
            $getRow = DB::table('sektor')
                        ->where('id_sektor', $getId)
                        ->first();
            
            $usecase = DB::table('user')->where('id_usecase', $data['id_usecase'])->where('level', 1)->first();

            $data_user_sektor = [];
            $data_user_sektor['id_role'] = $usecase->id_role;
            $data_user_sektor['id_sektor'] = $getId;
            $data_user_sektor['order'] = (DB::table('user_sektor')->where('id_role', $usecase->id_role)->max('order')) + 1;
            DB::table('user_sektor')->insert($data_user_sektor);

            return $getRow;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal menambahkan sektor.');
        }
    }

    public function getSektorById($id_sektor) {
        try {
            $data = DB::table('sektor')
                    ->where('id_sektor', $id_sektor)
                    ->first();

            return $data;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil data sektor.');
        }
    }

    public function deleteSektor($id_sektor) {
        try {
            $row = DB::table('user_sektor')
                ->where('id_sektor', $id_sektor)
                ->first();

            DB::table('user_sektor')
                ->where('id_sektor', $id_sektor)
                ->delete();
            
            DB::table('sektor')
                ->where('id_sektor', $id_sektor)
                ->delete();
            
            $order_deleted = $row->order;
        
            $affected_rows = DB::table('user_sektor')
                ->where('id_role', $row->id_role)
                ->where('order', '>', $order_deleted)
                ->get();

            foreach ($affected_rows as $affected_row) {
                DB::table('user_sektor')
                    ->where('id', $affected_row->id)
                    ->decrement('order');
            }
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal menghapus sektor.');
        }
    }

    public function updateSektor($data, $id_sektor) {
        try {
            DB::table('sektor')
                ->where('id_sektor', $id_sektor)
                ->update($data);

            return DB::table('sektor')
                    ->where('id_sektor', $id_sektor)
                    ->first();
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal memperbarui data sektor.');
        }
    }

    public function getSektorUsecase($id_usecase) {
        try {
            $data = DB::table('sektor')
                ->where('id_usecase', $id_usecase)
                ->get();

            return $data;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil data sektor usecase.');
        }
    }

    public function editSubadminSektor($data, $id_subadmin, $id_usecase) {
        try {
            $id_role_admin = (DB::table('user')->select('id_role')->where('id_usecase', $id_usecase)->where('level', 1)->first())->id_role;
            $id_role_sub = (DB::table('user')->select('id_role')->where('id_user', $id_subadmin)->first())->id_role;

            DB::table('user_sektor')
                ->where('id_role', $id_role_sub)
                ->whereNotIn('id_sektor', $data)
                ->delete();
        
            $adminAccess = DB::table('user_sektor')
                            ->where('id_role', $id_role_admin)
                            ->orderBy('order', 'asc')
                            ->get();
        
            $sortedData = [];
            foreach ($adminAccess as $adminRow) {
                if (in_array($adminRow->id_sektor, $data)) {
                    $sortedData[] = $adminRow->id_sektor;
                }
            }
            
            
        
            $accessRows = [];
            $order = 1;
            foreach ($sortedData as $id_sektor) {
                $checkAccess = DB::table('user_sektor')
                                ->where('id_role', $id_role_sub)
                                ->where('id_sektor', $id_sektor)
                                ->first();
        
                if ($checkAccess) {
                    DB::table('user_sektor')
                        ->where('id', $checkAccess->id)
                        ->update(['order' => $order]);
        
                    $accessRows[] = [
                        'id' => $checkAccess->id,
                        'id_role' => $id_role_sub,
                        'id_sektor' => $id_sektor,
                        'order' => $order,
                        'action' => 'updated'
                    ];
                } else {
                    $newId = DB::table('user_sektor')->insertGetId([
                        'id_role' => $id_role_sub,
                        'id_sektor' => $id_sektor,
                        'order' => $order
                    ]);
        
                    $accessRows[] = [
                        'id' => $newId,
                        'id_role' => $id_role_sub,
                        'id_sektor' => $id_sektor,
                        'order' => $order,
                        'action' => 'inserted'
                    ];
                }
                $order++;
            }
            return $accessRows;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal memperbarui sektor.');
        }
    }

    public function sortSektor($data, $id_usecase) {
        try {
            $id_role = (DB::table('user')->where('id_usecase', $id_usecase)->where('level', 1)->first())->id_role;
            $subadmin = DB::table('user')->where('id_usecase', $id_usecase)->where('level', 2)->pluck('id_role')->toArray();

            DB::table('user_sektor')
                ->where('id_role', $id_role)
                ->whereNotIn('id_sektor', $data)
                ->delete();

            $existingMenus = DB::table('user_sektor')
                            ->where('id_role', $id_role)
                            ->get();

            $adminRows = [];
            $orderAdmin = 1;
            foreach ($data as $order => $id_sektor) {
                $existingMenu = $existingMenus->firstWhere('id_sektor', $id_sektor);

                if ($existingMenu) {
                    DB::table('user_sektor')
                        ->where('id', $existingMenu->id)
                        ->update(['order' => $orderAdmin]);

                    $adminRows[] = [
                        'id' => $existingMenu->id,
                        'id_role' => $id_role,
                        'id_sektor' => $id_sektor,
                        'order' => $orderAdmin,
                        'action' => 'updated'
                    ];
                } else {
                    $newId = DB::table('user_sektor')->insertGetId([
                            'id_role' => $id_role,
                            'id_sektor' => $id_sektor,
                            'order' => $orderAdmin
                    ]);

                    $adminRows[] = [
                        'id' => $newId,
                        'id_role' => $id_role,
                        'id_sektor' => $id_sektor,
                        'order' => $orderAdmin,
                        'action' => 'inserted'
                    ];
                }
                $orderAdmin++;
            }

            foreach ($subadmin as $id_sub) {
                DB::table('user_sektor')
                    ->where('id_role', $id_sub)
                    ->whereNotIn('id_sektor', $data)
                    ->delete();

                $subAccess = DB::table('user_sektor')
                        ->where('id_role', $id_sub)
                        ->get();

                $order = 1;
                foreach ($data as $id_sektor) {
                    $existingMenu = $subAccess->firstWhere('id_sektor', $id_sektor);
                    if ($existingMenu) {
                        DB::table('user_sektor')
                            ->where('id_role', $existingMenu->id_role)
                            ->where('id_sektor', $id_sektor)
                            ->update(['order' => $order]);
                    } 
                    $order++;
                }
            }
            return $adminRows;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal sorting sektor.');
        }
    }

    public function getAssignedSektor($id_user) {
        try {
            $id_role = DB::table('user')->where('id_user', $id_user)->value('id_role');
            $assignedSektor = DB::table('user_sektor')->where('id_role', $id_role)->pluck('id_sektor')->toArray();
            $data = DB::table('sektor')->whereIn('id_sektor', $assignedSektor)->get();
            return $data;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil assigned sektor.');
        }
    }

    public function updateAssignedSektor($data, $id_sektor) {
        try {
            DB::table('sektor')
                ->where('id_sektor', $id_sektor)
                ->update($data);

            return DB::table('sektor')
                    ->where('id_sektor', $id_sektor)
                    ->first();
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal memperbarui data sektor.');
        }
    }
}


