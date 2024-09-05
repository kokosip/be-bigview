<?php

namespace App\Http\Controllers\Pariwisata;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;
use App\Services\Pariwisata\TelkomselService;

class TelkomselController extends Controller
{
    use ApiResponse;
    protected $id_usecase;
    protected $level;
    protected $telkomselService;

    public function __construct(TelkomselService $telkomselService)
    {
        $this->id_usecase = Auth::user()->id_usecase ?? null;
        $this->level = Auth::user()->level ?? null;
        $this->telkomselService = $telkomselService;
    }

    public function tripMap(Request $request)
    {
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'period' => 'required',
                'destination' => 'required',
                'origin' => 'nullable',
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->telkomselService->getTripMap($validator->validate(), $this->id_usecase);
        } else {
            $validator = Validator::make($request->all(), [
                'period' => 'required',
                'destination' => 'required',
                'origin' => 'nullable',
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->telkomselService->getTripMap($validatedData, $idUsecase);
        }

        return $this->successResponse(data: $data);
    }

    public function topOrigin(Request $request)
    {
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'period' => 'required',
                'origin' => 'nullable',
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->telkomselService->getTopOrigin($validator->validate(), $this->id_usecase);
        } else {
            $validator = Validator::make($request->all(), [
                'period' => 'required',
                'origin' => 'nullable',
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->telkomselService->getTopOrigin($validatedData, $idUsecase);
        }
        return $this->successResponse(data: $data);
    }

    public function topDestination(Request $request)
    {
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'period' => 'required',
                'destination' => 'required',
                'origin' => 'nullable',
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->telkomselService->getTopDestination($validator->validate(), $this->id_usecase);
        } else {
            $validator = Validator::make($request->all(), [
                'period' => 'required',
                'destination' => 'required',
                'origin' => 'nullable',
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->telkomselService->getTopDestination($validatedData, $idUsecase);
        }
        return $this->successResponse(data: $data);
    }

    public function numberOfTrips(Request $request)
    {
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'provinsi' => 'required',
                'kab_kota' => 'nullable',
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->telkomselService->getNumberOfTrips($validator->validate(), $this->id_usecase);
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'provinsi' => 'required',
                'kab_kota' => 'nullable',
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->telkomselService->getNumberOfTrips($validatedData, $idUsecase);
        }
        return $this->successResponse(data: $data);
    }

    public function numberOfTripsOrigin(Request $request)
    {
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'provinsi' => 'required',
                'kab_kota' => 'nullable',
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->telkomselService->getNumberOfTripsOrigin($validator->validate(), $this->id_usecase);
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'provinsi' => 'required',
                'kab_kota' => 'nullable',
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->telkomselService->getNumberOfTripsOrigin($validatedData, $idUsecase);
        }
        return $this->successResponse(data: $data);
    }

    public function numberOfTripsDestination(Request $request)
    {
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'provinsi' => 'required',
                'kab_kota' => 'nullable',
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->telkomselService->getNumberOfTripsDestination($validator->validate(), $this->id_usecase);
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'provinsi' => 'required',
                'kab_kota' => 'nullable',
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->telkomselService->getNumberOfTripsDestination($validatedData, $idUsecase);
        }
        return $this->successResponse(data: $data);
    }

    public function movementOfTrips(Request $request)
    {
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'periode' => 'required',
                'origin' => 'nullable',
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->telkomselService->getMovementOfTrips($validator->validate(), $this->id_usecase);
        } else {
            $validator = Validator::make($request->all(), [
                'periode' => 'required',
                'origin' => 'nullable',
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->telkomselService->getMovementOfTrips($validatedData, $idUsecase);
        }
        return $this->successResponse(data: $data);
    }

    public function lengthOfStay(Request $request)
    {
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'periode' => 'required',
                'origin' => 'nullable',
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->telkomselService->getLengthOfStay($validator->validate(), $this->id_usecase);
        } else {
            $validator = Validator::make($request->all(), [
                'periode' => 'required',
                'origin' => 'nullable',
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->telkomselService->getLengthOfStay($validatedData, $idUsecase);
        }
        return $this->successResponse(data: $data);
    }

    public function movementOfGender(Request $request)
    {
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'periode' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->telkomselService->getMovementOfGender($validator->validate(), $this->id_usecase);
        } else {
            $validator = Validator::make($request->all(), [
                'periode' => 'required',
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->telkomselService->getMovementOfGender($validatedData, $idUsecase);
        }
        return $this->successResponse(data: $data);
    }

    public function movementOfAge(Request $request)
    {
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'periode' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->telkomselService->getMovementOfAge($validator->validate(), $this->id_usecase);
        } else {
            $validator = Validator::make($request->all(), [
                'periode' => 'required',
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->telkomselService->getMovementOfAge($validatedData, $idUsecase);
        }
        return $this->successResponse(data: $data);
    }

    public function statusSES(Request $request)
    {
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'periode' => 'required',
                'origin' => 'nullable'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->telkomselService->getStatusSES($validator->validate(), $this->id_usecase);
        } else {
            $validator = Validator::make($request->all(), [
                'periode' => 'required',
                'origin' => 'nullable',
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->telkomselService->getStatusSES($validatedData, $idUsecase);
        }
        return $this->successResponse(data: $data);
    }

    public function matrixOrigin(Request $request)
    {
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'periode' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->telkomselService->getMatrixOrigin($validator->validate(), $this->id_usecase);
        } else {
            $validator = Validator::make($request->all(), [
                'periode' => 'required',
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->telkomselService->getMatrixOrigin($validatedData, $idUsecase);
        }
        return $this->successResponse(data: $data);
    }

    public function jenisWisatawan(Request $request)
    {
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'periode' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->telkomselService->getJenisWisatawan($validator->validate(), $this->id_usecase);
        } else {
            $validator = Validator::make($request->all(), [
                'periode' => 'required',
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->telkomselService->getJenisWisatawan($validatedData, $idUsecase);
        }
        return $this->successResponse(data: $data);
    }

    public function filterProvinsi(Request $request)
    {
        if ($this->level >= 1) {
            $data = $this->telkomselService->getFilterProvinsi($this->id_usecase);
        } else {
            $validator = Validator::make($request->all(), [
                'id_usecase' => 'required'
            ]);
            $idUsecase = $validator->validate()['id_usecase'];
            $data = $this->telkomselService->getFilterProvinsi($idUsecase);
        }
        return $this->successResponse(data: $data);
    }

    public function filterKabKota(Request $request)
    {
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'origin' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->telkomselService->getFilterKabKota($validator->validate(), $this->id_usecase);
        } else {
            $validator = Validator::make($request->all(), [
                'origin' => 'required',
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->telkomselService->getFilterKabKota($validatedData, $idUsecase);
        }
        return $this->successResponse(data: $data);
    }

    public function filterTahun(Request $request)
    {
        if ($this->level >= 1) {
            $data = $this->telkomselService->getFilterTahun($this->id_usecase);
        } else {
            $validator = Validator::make($request->all(), [
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $idUsecase = $validator->validate()['id_usecase'];
            $data = $this->telkomselService->getFilterTahun($idUsecase);
        }
        return $this->successResponse(data: $data);
    }

    public function filterPeriode(Request $request)
    {
        if ($this->level >= 1) {
            $data = $this->telkomselService->getFilterPeriode($this->id_usecase);
        } else {
            $validator = Validator::make($request->all(), [
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $idUsecase = $validator->validate()['id_usecase'];
            $data = $this->telkomselService->getFilterPeriode($idUsecase);
        }
        return $this->successResponse(data: $data);
    }

    public function movementTripMap(Request $request)
    {
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'period' => 'required',
                'origin' => 'nullable'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->telkomselService->getMovementTripMap($request->all(), $this->id_usecase);
        } else {
            $validator = Validator::make($request->all(), [
                'period' => 'required',
                'origin' => 'nullable',
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->telkomselService->getMovementTripMap($validatedData, $idUsecase);
        }
        return $this->successResponse(data: $data);
    }

    public function movementHeatMap(Request $request)
    {
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'period' => 'required',
                'origin' => 'nullable'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->telkomselService->getMovementHeatMap($validator->validate(), $this->id_usecase);
        } else {
            $validator = Validator::make($request->all(), [
                'period' => 'required',
                'origin' => 'nullable',
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->telkomselService->getMovementHeatMap($validatedData, $idUsecase);
        }
        return $this->successResponse(data: $data);
    }

    public function filterSingleYear(Request $request)
    {
        if ($this->level >= 1) {
            $data = $this->telkomselService->getFilterSingleYear($this->id_usecase);
        } else {
            $validator = Validator::make($request->all(), [
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->telkomselService->getFilterSingleYear($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function trendJumlahPerjalanan(Request $request)
    {
        if ($this->level >= 1) {
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
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'parent_origin' => 'nullable',
                'origin' => 'nullable',
                'breakdown' => 'nullable',
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->telkomselService->getTrendJumlahPerjalanan($validatedData, $idUsecase);
        }
        return $this->successResponse(data: $data);
    }

    public function filterMonth(Request $request)
    {
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->telkomselService->getFilterMonth($validator->validate(), $this->id_usecase);
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->telkomselService->getFilterMonth($validatedData, $idUsecase);
        }
        return $this->successResponse(data: $data);
    }

    public function tempatWisata(Request $request)
    {
        if ($this->level >= 1) {
            $data = $this->telkomselService->getTempatWisata($this->id_usecase);
        } else {
            $validator = Validator::make($request->all(), [
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->telkomselService->getTempatWisata($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function filterDestination(Request $request)
    {
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->telkomselService->getFilterDestination($validator->validate(), $this->id_usecase);
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->telkomselService->getFilterDestination($validatedData, $idUsecase);
        }
        return $this->successResponse(data: $data);
    }

    public function trendWisatawanByLamaTinggal(Request $request)
    {
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'destination' => 'nullable',
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->telkomselService->getTrendWisataByLamaTinggal($validator->validate(), $this->id_usecase);
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'destination' => 'nullable',
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->telkomselService->getTrendWisataByLamaTinggal($validatedData, $idUsecase);
        }
        return $this->successResponse(data: $data);
    }

    public function jumlahWisatawan(Request $request)
    {
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'destination' => 'nullable',
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->telkomselService->getJumlahWisatawan($validator->validate(), $this->id_usecase);
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'destination' => 'nullable',
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->telkomselService->getJumlahWisatawan($validatedData, $idUsecase);
        }
        return $this->successResponse(data: $data);
    }

    public function kelompokUsiaWisatawan(Request $request)
    {
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'destination' => 'nullable',
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->telkomselService->getKelompokUsiaWisatawan($validator->validate(), $this->id_usecase);
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'destination' => 'nullable',
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->telkomselService->getKelompokUsiaWisatawan($validatedData, $idUsecase);
        }
        return $this->successResponse(data: $data);
    }
}
