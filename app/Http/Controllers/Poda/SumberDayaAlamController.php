<?php

namespace App\Http\Controllers\Poda;

use App\Http\Controllers\Controller;
use App\Services\Poda\SumberDayaAlamServices;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SumberDayaAlamController extends Controller
{
    use ApiResponse;
    protected $sdaService;
    protected $idUsecase;

    public function __construct(SumberDayaAlamServices $sdaService)
    {
        $this->sdaService = $sdaService;
        $this->idUsecase = Auth::user()->id_usecase;
    }

    public function listIndikator(){

    }
}
