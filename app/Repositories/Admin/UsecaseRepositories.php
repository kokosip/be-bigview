<?php

namespace App\Repositories\Admin;

use Exception;
use Illuminate\Support\Facades\DB;

class UsecaseRepositories {

    public function getListUsecase($search, $perPage){
        $db = DB::table('usecase')
            ->select('id_usecase', 'name_usecase', 'base_color1', 'base_color2', 'base_color3', 'base_color4');

        if($search){
            $db = $db->whereRaw("name_usecase LIKE ? ", ["%{$search}%"]);
        }

        $result = $db->paginate($perPage, $perPage);
        return $result;
    }
}
