<?php

namespace App\Http\Controllers\Poda;

use App\Http\Controllers\Controller;
use App\Services\Poda\SosialKependudukanServices;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SosialKependudukanController extends Controller
{
    use ApiResponse;
    protected $sosialService;
    protected $idUsecase;

    public function __construct(SosialKependudukanServices $sosialService)
    {
        $this->sosialService = $sosialService;
        $this->idUsecase = Auth::user()->id_usecase;
    }

    // Start Kependudukan
    public function tahunJumlahPenduduk(){
        try{
            $data = $this->sosialService->getTahunJumlahPenduduk($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function mapJumlahPenduduk(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getMapJumlahPenduduk($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function pieJumlahPenduduk(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getPieJumlahPenduduk($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function barJumlahPenduduk(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getBarJumlahPenduduk($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function detailJumlahPenduduk(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getDetailJumlahPenduduk($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
    // End Kependudukan

    // Start Rentang Usia
    public function tahunRentangUsia(){
        try{
            $data = $this->sosialService->getTahunRentangUsia($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function stackedBarRentangUsia(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getStackedBarRentangUsia($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function detailRentangUsia(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getDetailRentangUsia($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
    // End Rentang Usia

    // Start Laju Pertumbuhan
    public function periodeLaju(){
        try{
            $data = $this->sosialService->getPeriodeLaju($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function namaDaerahLaju(){
        try{
            $data = $this->sosialService->getNamaDaerahLaju($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function dualAxesLaju(Request $request){
        $validator = Validator::make($request->all(), [
            'periode' => 'required',
            'nama_daerah' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getDualAxesLaju($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function detailLaju(Request $request){
        $validator = Validator::make($request->all(), [
            'periode' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getDetailLaju($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
    // End Laju Pertumbuhan

    // Start Rasio Jenis Kelamin
    public function tahunRasio(){
        try{
            $data = $this->sosialService->getTahunRasio($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function mapRasio(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getMapRasio($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function barRasio(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getBarRasio($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function detailRasio(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getDetailRasio($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
    // End Rasio Jenis Kelamin

    // Start Kepadatan Penduduk
    public function tahunKepadatan(){
        try{
            $data = $this->sosialService->getTahunKepadatan($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function mapKepadatan(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getMapKepadatan($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function barKepadatan(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getBarKepadatan($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function detailKepadatan(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getDetailKepadatan($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
    // End Kepadatan Penduduk

    // Start IPM
    public function periodeIPM(Request $request){
        $validator = Validator::make($request->all(), [
            'filter' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getPeriodeIPM($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function namaDaerahIPM(Request $request){
        $validator = Validator::make($request->all(), [
            'filter' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getNamaDaerahIPM($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function indikatorIPM(){
        try{
            $data = $this->sosialService->getIndikatorIPM($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function areaIPM(Request $request){
        $validator = Validator::make($request->all(), [
            'periode' => 'required',
            'filter' => 'required',
            'nama_daerah' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getAreaIPM($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function mapIPM(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'filter' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getMapIPM($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function detailIPM(Request $request){
        $validator = Validator::make($request->all(), [
            'periode' => 'required',
            'filter' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getDetailIPM($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
    // End IPM

    // Start Kemiskinan
    public function indikatorKemiskinan(){
        try{
            $data = $this->sosialService->getIndikatorKemiskinan($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function tahunKemiskinan(){
        try{
            $data = $this->sosialService->getTahunKemiskinan($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function daerahKemiskinan(){
        try{
            $data = $this->sosialService->getDaerahKemiskinan($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function periodeKemiskinan(Request $request){
        $validator = Validator::make($request->all(), [
            'filter' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getPeriodeKemiskinan($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function mapKemiskinan(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'filter' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getMapKemiskinan($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function areaKemiskinan(Request $request){
        $validator = Validator::make($request->all(), [
            'periode' => 'required',
            'filter' => 'required',
            'nama_daerah' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getAreaKemiskinan($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function detailKemiskinan(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'filter' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getDetailKemiskinan($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
    // End Kemiskinan

    // Start Pekerjaan
    public function indikatorPekerjaan(){
        try{
            $data = $this->sosialService->getIndikatorPekerjaan($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function tahunPekerjaan(){
        try{
            $data = $this->sosialService->getTahunPekerjaan($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function tahunJenisPekerjaan(){
        try{
            $data = $this->sosialService->getTahunJenisPekerjaan($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function periodePekerjaan(Request $request){
        $validator = Validator::make($request->all(), [
            'filter' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getPeriodePekerjaan($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function barJenisPekerjaan(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getBarJenisPekerjaan($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function mapPekerjaan(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'filter' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getMapPekerjaan($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function linePekerjaan(Request $request){
        $validator = Validator::make($request->all(), [
            'periode' => 'required',
            'filter' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getLinePekerjaan($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function detailJenisPekerjaan(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getDetailJenisPekerjaan($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function detailPekerjaan(Request $request){
        $validator = Validator::make($request->all(), [
            'periode' => 'required',
            'indikator' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getDetailPekerjaan($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
    // End Pekerjaan

    // Start Pendidikan
    public function tahunAjaranPendidikan(){
        try{
            $data = $this->sosialService->getTahunAjaranPendidikan($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function tahunPendidikan(){
        try{
            $data = $this->sosialService->getTahunPendidikan($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function jenjangPendidikan(){
        try{
            $data = $this->sosialService->getJenjangPendidikan($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function indikatorPendidikan(){
        try{
            $data = $this->sosialService->getIndikatorPendidikan($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function barPendidikan(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'jenjang' => 'required',
            'indikator' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getBarPendidikan($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function barJenjangPendidikan(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getBarJenjangPendidikan($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function mapPendidikan(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'jenjang' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getMapPendidikan($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function detailPendidikan(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getDetailPendidikan($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
    // End Pendidikan

    // Start Kesehatan
    public function tahunKesehatan(){
        try{
            $data = $this->sosialService->getTahunKesehatan($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function indikatorKesehatan(){
        try{
            $data = $this->sosialService->getIndikatorKesehatan($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function periodeKesehatan(Request $request){
        try{
            $data = $this->sosialService->getPeriodeKesehatan($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function barKesehatan(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'filter' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getBarKesehatan($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function barColumnKesehatan(Request $request){
        $validator = Validator::make($request->all(), [
            'periode' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getBarColumnKesehatan($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function mapKesehatan(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getMapKesehatan($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function detailKesehatan(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getDetailKesehatan($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
    // End Kesehatan
}
