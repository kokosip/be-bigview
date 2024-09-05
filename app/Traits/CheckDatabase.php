<?php

namespace App\Traits;

use App\Exceptions\ErrorResponse;
use Exception;
use Illuminate\Support\Facades\DB;

trait CheckDatabase
{

    public function isDuplicate($table_name, $columns, $data)
    {
        $query = DB::table($table_name);

        foreach ($columns as $index => $column) {
            $query->where($column, $data[$index]);
        }

        if ($query->exists()) {
            throw new ErrorResponse(type: 'Bad Request', statusCode: 400, message: 'Data sudah terdaftar sebelumnya, silahkan mengganti dengan yang lain');
        }
    }
}
