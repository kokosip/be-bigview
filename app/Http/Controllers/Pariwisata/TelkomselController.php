<?php

namespace App\Http\Controllers\Pariwisata;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;
use  App\Services\Pariwisata\TelkomselService;

class TelkomselController extends Controller
{
    use ApiResponse;
    protected $id_usecase;
    protected $telkomselService;

    public function __construct(TelkomselService $telkomselService)
    {
        $this->id_usecase = Auth::user()->id_usecase ?? null;
        $this->telkomselService = $telkomselService;
    }

    public function tripMap(Request $request) {
        $validator = Validator::make($request->all(), [
            'period' => 'required',
            'destination' => 'required',
            'origin' => 'nullable',
        ]);
        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $this->telkomselService->getTripMap($validator->validate(), $this->id_usecase);
        return $this->successResponse(data: $data);
    }

    public function topOrigin(Request $request) {
        $validator = Validator::make($request->all(), [
            'period' => 'required',
            'origin' => 'nullable',
        ]);
        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $this->telkomselService->getTopOrigin($validator->validate(), $this->id_usecase);
        return $this->successResponse(data: $data);
    }

    public function topDestination(Request $request) {
        $validator = Validator::make($request->all(), [
            'period' => 'required',
            'destination' => 'required',
            'origin' => 'nullable',
        ]);
        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $this->telkomselService->getTopDestination($validator->validate(), $this->id_usecase);
        return $this->successResponse(data: $data);
    }

    public function numberOfTrips(Request $request) {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'provinsi' => 'required',
            'kab_kota' => 'nullable',
        ]);
        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $this->telkomselService->getNumberOfTrips($validator->validate(), $this->id_usecase);
        return $this->successResponse(data: $data);
    }

    public function numberOfTripsOrigin(Request $request) {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'provinsi' => 'required',
            'kab_kota' => 'nullable',
        ]);
        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $this->telkomselService->getNumberOfTripsOrigin($validator->validate(), $this->id_usecase);
        return $this->successResponse(data: $data);
    }

    public function numberOfTripsDestination(Request $request) {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'provinsi' => 'required',
            'kab_kota' => 'nullable',
        ]);
        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $this->telkomselService->getNumberOfTripsDestination($validator->validate(), $this->id_usecase);
        return $this->successResponse(data: $data);
    }

    public function movementOfTrips(Request $request) {
        $validator = Validator::make($request->all(), [
            'periode' => 'required',
            'origin' => 'nullable',
        ]);
        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $this->telkomselService->getMovementOfTrips($validator->validate(), $this->id_usecase);
        return $this->successResponse(data: $data);
    }

    public function lengthOfStay(Request $request) {
        $validator = Validator::make($request->all(), [
            'periode' => 'required',
            'origin' => 'nullable',
        ]);
        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $this->telkomselService->getLengthOfStay($validator->validate(), $this->id_usecase);
        return $this->successResponse(data: $data);
    }

    public function movementOfGender(Request $request) {
        $validator = Validator::make($request->all(), [
            'periode' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $this->telkomselService->getMovementOfGender($validator->validate(), $this->id_usecase);
        return $this->successResponse(data: $data);
    }

    public function movementOfAge(Request $request) {
        $validator = Validator::make($request->all(), [
            'periode' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $this->telkomselService->getMovementOfAge($validator->validate(), $this->id_usecase);
        return $this->successResponse(data: $data);
    }

    public function statusSES(Request $request) {
        $validator = Validator::make($request->all(), [
            'periode' => 'required',
            'origin' => 'nullable'
        ]);
        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $this->telkomselService->getStatusSES($validator->validate(), $this->id_usecase);
        return $this->successResponse(data: $data);
    }

    public function matrixOrigin(Request $request) {
        $validator = Validator::make($request->all(), [
            'periode' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $this->telkomselService->getMatrixOrigin($validator->validate(), $this->id_usecase);
        return $this->successResponse(data: $data);
    }

    public function jenisWisatawan(Request $request) {
        $validator = Validator::make($request->all(), [
            'periode' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $this->telkomselService->getJenisWisatawan($validator->validate(), $this->id_usecase);
        return $this->successResponse(data: $data);
    }

    public function filterProvinsi() {
        $data = $this->telkomselService->getFilterProvinsi($this->id_usecase);
        return $this->successResponse(data: $data);
    }

    public function filterKabKota(Request $request) {
        $validator = Validator::make($request->all(), [
            'origin' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $this->telkomselService->getFilterKabKota($validator->validate(), $this->id_usecase);
        return $this->successResponse(data: $data);
    }

    public function filterTahun() {
        $data = $this->telkomselService->getFilterTahun($this->id_usecase);
        return $this->successResponse(data: $data);
    }

    public function filterPeriode() {
        $data = $this->telkomselService->getFilterPeriode($this->id_usecase);
        return $this->successResponse(data: $data);
    }

    public function movementTripMap(Request $request) {
        $validator = Validator::make($request->all(), [
            'period' => 'required',
            'origin' => 'nullable'
        ]);
        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $this->telkomselService->getMovementTripMap($request->all(), $this->id_usecase);
        return $this->successResponse(data: $data);
    }

    public function movementHeatMap(Request $request) {
        $validator = Validator::make($request->all(), [
            'period' => 'required',
            'origin' => 'nullable'
        ]);
        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $this->telkomselService->getMovementHeatMap($validator->validate(), $this->id_usecase);
        return $this->successResponse(data: $data);
    }

    public function filterSingleYear() {
        $data = $this->telkomselService->getFilterSingleYear($this->id_usecase);
        return $this->successResponse(data: $data);
    }

    public function trendJumlahPerjalanan(Request $request) {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'parent_origin' => 'nullable',
            'origin' => 'nullable',
            'breakdown' => 'nullable',
        ]);
        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $this->telkomselService->getTrendJumlahPerjalanan($validator->validate(), $this->id_usecase);
        return $this->successResponse(data: $data);
    }
    
    public function filterMonth(Request $request) {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $this->telkomselService->getFilterMonth($validator->validate(), $this->id_usecase);
        return $this->successResponse(data: $data);
    }

    public function tempatWisata() {
        $data = $this->telkomselService->getTempatWisata($this->id_usecase);
        return $this->successResponse(data: $data);
    }

    public function filterDestination(Request $request) {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $this->telkomselService->getFilterDestination($validator->validate(), $this->id_usecase);
        return $this->successResponse(data: $data);
    }

    public function trendWisatawanByLamaTinggal(Request $request) {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'destination' => 'nullable',
        ]);
        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $this->telkomselService->getTrendWisataByLamaTinggal($validator->validate(), $this->id_usecase);
        return $this->successResponse(data: $data);
    }

    public function jumlahWisatawan(Request $request) {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'destination' => 'nullable',
        ]);
        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $this->telkomselService->getJumlahWisatawan($validator->validate(), $this->id_usecase);
        return $this->successResponse(data: $data);
    }

    public function kelompokUsiaWisatawan(Request $request) {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'destination' => 'nullable',
        ]);
        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $this->telkomselService->getKelompokUsiaWisatawan($validator->validate(), $this->id_usecase);
        return $this->successResponse(data: $data);
    }
}
