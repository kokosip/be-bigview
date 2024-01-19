<?php

namespace App\Http\Controllers\Poda;

use App\Http\Controllers\Controller;
use App\Services\Poda\EkonomiPerdaganganServices;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EkonomiPerdaganganController extends Controller
{
    use ApiResponse;
    protected $ekonomiService;
    protected $idUsecase;

    public function __construct(EkonomiPerdaganganServices $ekonomiService)
    {
        $this->ekonomiService = $ekonomiService;
        $this->idUsecase = Auth::user()->id_usecase;
    }

    // Inflasi dan IHK
    public function monthPeriodeInflasi(){
        try{
            $data = $this->ekonomiService->getMonthPeriodeInflasi($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function namaDaerahInflasi(){
        try{
            $data = $this->ekonomiService->getNamaDaerahInflasi($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function tahunInflasi(){
        try{
            $data = $this->ekonomiService->getTahunInflasi($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function bulanInflasi(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->ekonomiService->getBulanInflasi($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function mapInflasi(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required',
            'bulan' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->ekonomiService->getMapInflasi($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function dualChartInflasi(Request $request){
        $validator = Validator::make($request->all(),[
            'nama_daerah' => 'required',
            'periode' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->ekonomiService->getDualChartInflasi($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
    // End Infalsi dan IHK

    // Start PDRB
    public function tahunPDRB(){
        try{
            $data = $this->ekonomiService->getTahunPDRB($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function kategoriPDRB(){
        try{
            $data = $this->ekonomiService->getKategoriPDRB($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function sektorPDRB(){
        try{
            $data = $this->ekonomiService->getSektorPDRB($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function cardPDRB(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required',
            'filter' => 'required',
            'jenis' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->ekonomiService->getCardPDRB($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function barPDRB(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required',
            'filter' => 'required',
            'jenis' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->ekonomiService->getBarPDRB($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function areaPDRB(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required',
            'filter' => 'required',
            'jenis' => 'required',
            'sektor' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->ekonomiService->getAreaPDRB($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
    // End PDRB

    // Start Pariwisata
    public function indikatorPariwisata(){
        try{
            $data = $this->ekonomiService->getIndikatorPariwisata($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function namaDaerahPariwisataDTW(){
        try{
            $data = $this->ekonomiService->getNamaDaerahPariwisataDTW($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function periodePariwisataDTW(){
        try{
            $data = $this->ekonomiService->getPeriodePariwisataDTW($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function tahunPariwisataDTW(){
        try{
            $data = $this->ekonomiService->getTahunPariwisataDTW($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function mapPariwisataDTW(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->ekonomiService->getMapPariwisataDTW($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function linePariwisataDTW(Request $request){
        $validator = Validator::make($request->all(),[
            'periode' => 'required',
            'daerah' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->ekonomiService->getLinePariwisataDTW($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function periodePariwisataHotel(){
        try{
            $data = $this->ekonomiService->getPeriodePariwisataHotel($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function tahunPariwisataHotel(){
        try{
            $data = $this->ekonomiService->getTahunPariwisataHotel($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function mapPariwisataHotel(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->ekonomiService->getMapPariwisataHotel($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function barPariwisataHotel(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->ekonomiService->getBarPariwisataHotel($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function linePariwisataHotel(Request $request){
        $validator = Validator::make($request->all(),[
            'periode' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->ekonomiService->getLinePariwisataHotel($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function periodePariwisataWisatawan(){
        try{
            $data = $this->ekonomiService->getPeriodePariwisataWisatawan($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function cardPariwisataWisatawan(Request $request){
        $validator = Validator::make($request->all(),[
            'periode' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->ekonomiService->getCardPariwisataWisatawan($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function linePariwisataWisatawan(Request $request){
        $validator = Validator::make($request->all(),[
            'periode' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->ekonomiService->getLinePariwisataWisatawan($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function tahunPariwisataTPK(){
        try{
            $data = $this->ekonomiService->getTahunPariwisataTPK($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function bulanPariwisataTPK(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->ekonomiService->getBulanPariwisataTPK($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function cardPariwisataTPK(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required',
            'bulan' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->ekonomiService->getCardPariwisataTPK($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function linePariwisataTPK(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->ekonomiService->getLinePariwisataTPK($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function periodePariwisataResto(){
        try{
            $data = $this->ekonomiService->getPeriodePariwisataResto($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function tahunPariwisataResto(){
        try{
            $data = $this->ekonomiService->getTahunPariwisataResto($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function namaDaerahPariwisataResto(){
        try{
            $data = $this->ekonomiService->getNamaDaerahPariwisataResto($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function mapPariwisataResto(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->ekonomiService->getMapPariwisataResto($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function linePariwisataResto(Request $request){
        $validator = Validator::make($request->all(),[
            'periode' => 'required',
            'daerah' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->ekonomiService->getLinePariwisataResto($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
}
