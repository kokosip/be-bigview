<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\UsecaseServices;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsecaseController extends Controller
{
    use ApiResponse;
    protected $usecaseService;

    public function __construct(UsecaseServices $usecaseService)
    {
        $this->usecaseService = $usecaseService;
    }

    public function listUsecase(Request $request){
        $search = $request->input("search");
        $perPage = is_null($request->input('per_page')) ? 10 : $request->input('per_page');

        try{
            [$data, $metadata] = $this->usecaseService->getListUsecase($search, $perPage);

            return $this->successResponse(data: $data, metadata: $metadata);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function listNameUsecase(){
        try{
            $data = $this->usecaseService->getListNameUsecase();

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function getUsecaseById($id_usecase){
        try{
            $data = $this->usecaseService->getUsecaseById($id_usecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function addUsecaseCustom(Request $request){
        $validator = Validator::make($request->all(), [
            'name_usecase' => 'required',
            'deskripsi' => 'required',
            'base_color1' => 'required',
            'base_color2' => 'required',
            'base_color3' => 'required',
            'base_color4' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $govern = $this->usecaseService->addUsecaseCustom($validator->validate());

            return $this->successResponse(data: $govern, message: "Usecase Custom Berhasil ditambahkan");
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function addUsecaseGovernment(Request $request){
        $validator = Validator::make($request->all(), [
            'kode_provinsi' => 'required',
            'kode_kab_kota' => 'nullable',
            'name_usecase' => 'required',
            'base_color1' => 'required',
            'base_color2' => 'required',
            'base_color3' => 'required',
            'base_color4' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $govern = $this->usecaseService->addUsecaseGovernment($validator->validate());

            return $this->successResponse(data: $govern, message: "Usecase Government Berhasil ditambahkan");
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function updateUsecaseGovern(Request $request, $id_usecase){
        $validator = Validator::make($request->all(), [
            'kode_provinsi' => 'required',
            'kode_kab_kota' => 'nullable',
            'name_usecase' => 'required',
            'base_color1' => 'required',
            'base_color2' => 'required',
            'base_color3' => 'required',
            'base_color4' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            [$govern, $usecase] = $this->usecaseService->updateUsecaseGovern($validator->validate(), $id_usecase);

            return $this->successResponse(data: $govern, metadata: $usecase, message: "Usecase Government Berhasil diperbaruhi");
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function updateUsecaseCustom(Request $request, $id_usecase){
        $validator = Validator::make($request->all(), [
            'name_usecase' => 'required',
            'deskripsi' => 'required',
            'base_color1' => 'required',
            'base_color2' => 'required',
            'base_color3' => 'required',
            'base_color4' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            [$custom, $usecase] = $this->usecaseService->updateUsecaseCustom($validator->validate(), $id_usecase);

            return $this->successResponse(data: $custom, metadata: $usecase, message: "Usecase Custom Berhasil diperbaruhi");
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function deleteUsecaseGovernment($id_usecase){
        try{
            $this->usecaseService->deleteUsecaseGovern($id_usecase);

            return $this->successResponse(message: "Data Berhasil dihapus");
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function deleteUsecaseCustom($id_usecase){
        try{
            $this->usecaseService->deleteUsecaseCustom($id_usecase);

            return $this->successResponse(message: "Data Berhasil dihapus");
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function uploadLogo(Request $request, $id_usecase){
        $validator = Validator::make($request->all(), [
            'file' => 'required|file',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            [$message, $data] = $this->usecaseService->setLogo($id_usecase, $validator->validate());

            return $this->successResponse(data: $data, message: $message);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function getLogo($id_usecase){
        try{
            $data = $this->usecaseService->getLogo($id_usecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function deleteLogo($id_usecase){
        try {
            [$data, $message] = $this->usecaseService->deleteLogo($id_usecase);

            return $this->successResponse(data: $data, message: $message);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message: $e->getMessage(), statusCode:400);
        }
    }

    public function uploadProfilePimpinan(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'leader' => 'file',
            'vice' => 'file',
            'leader_name' => 'string',
            'vice_name' => 'string',
        ], [
            'required' => 'At least one of Leader file, Vice file, Leader name, or Vice name is required.',
        ]);
    
        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
    
        $validatedData = $validator->validated();
    
        try {
            [$data, $message] = $this->usecaseService->setProfile($id_usecase, $validatedData);
    
            return $this->successResponse(data: $data, message: $message);
        } catch(Exception $e) {
            return $this->errorResponse(type: "Failed", message: $e->getMessage(), statusCode: 400);
        }
    }

    public function updateContact(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'address' => 'sometimes|string',
            'phone' => 'sometimes|string',
            'link_map' => 'sometimes|string',
        ]);
    
        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
    
        $validatedData = $validator->validated();
    
        try {
            [$data, $message] = $this->usecaseService->updateContact($id_usecase, $validatedData);
            return $this->successResponse(data: $data, message: $message);
        } catch (Exception $e) {
            return $this->errorResponse(type: "Failed", message: $e->getMessage(), statusCode: 400);
        }
    }

    public function addPeriode(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'start_year' => 'required|string',
            'end_year' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $validatedData = $validator->validated();

        try {
            [$data, $message] = $this->usecaseService->addPeriode($id_usecase, $validatedData);
            return $this->successResponse(data: $data, message: $message);
        } catch (Exception $e) {
            return $this->errorResponse(type:'Failed', message: $e->getMessage(), statusCode:400);
        }
    }

    public function addVisi(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'short_desc' => 'required|string',
            'description' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $validatedData = $validator->validated();

        try {
            [$data, $message] = $this->usecaseService->addVisi($id_usecase, $validatedData);
            return $this->successResponse(data: $data, message: $message);
        } catch (Exception $e) {
            return $this->errorResponse(type:'Failed', message: $e->getMessage(), statusCode:400);
        }
    }

    public function updateVisi(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'id_visi' => 'required|string',
            'short_desc' => 'required|string',
            'description' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $validatedData = $validator->validated();

        try {
            [$data, $message] = $this->usecaseService->updateVisi($id_usecase, $validatedData);
            return $this->successResponse(data: $data, message: $message);
        } catch (Exception $e) {
            return $this->errorResponse(type:'Failed', message: $e->getMessage(), statusCode:400);
        }
    }

    public function deleteVisi(Request $request, $id_usecase) {
        try {
            $validator = Validator::make($request->all(), [
                'id_visi' => 'required|string',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
    
            $validatedData = $validator->validated();

            $message = $this->usecaseService->deleteVisi($id_usecase, $validatedData);
            return $this->successResponse(message: $message);
        } catch (Exception $e) {
            return $this->errorResponse(type:'failed', message: $e->getMessage(), statusCode:400);
        }
    }

    public function listVisi(Request $request, $id_usecase) {
        try {
            $validator = Validator::make($request->all(), [
                'perPage' => 'required|string',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();

            [$data, $metadata] = $this->usecaseService->getListVisi($id_usecase, $validatedData);

            return $this->successResponse(data: $data, metadata: $metadata);
        } catch (Exception $e) {
            return $this->errorResponse(type:'failed', message:$e->getMessage(), statusCode:400);
        }
    }

    public function addMisi(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'short_desc' => 'required|string',
            'description' => 'sometimes|string',
            'urutan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $validatedData = $validator->validated();

        try {
            [$data, $message] = $this->usecaseService->addMisi($id_usecase, $validatedData);
            return $this->successResponse(data: $data, message: $message);
        } catch (Exception $e) {
            return $this->errorResponse(type:'Failed', message: $e->getMessage(), statusCode:400);
        }
    }

    public function updateMisi(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'id_misi' => 'required|string',
            'short_desc' => 'required|string',
            'description' => 'sometimes|string',
            'urutan' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $validatedData = $validator->validated();

        try {
            [$data, $message] = $this->usecaseService->updateMisi($id_usecase, $validatedData);
            return $this->successResponse(data: $data, message: $message);
        } catch (Exception $e) {
            return $this->errorResponse(type:'Failed', message: $e->getMessage(), statusCode:400);
        }
    }

    public function deleteMisi(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'id_misi' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $validatedData = $validator->validated();

        try {
            $message = $this->usecaseService->deleteMisi($id_usecase, $validatedData);
            return $this->successResponse(message: $message);
        } catch (Exception $e) {
            return $this->errorResponse(type:'Failed', message: $e->getMessage(), statusCode:400);
        }
    }

    public function listMisi(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'perPage' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $validatedData = $validator->validated();
        try {
            [$data, $metadata] = $this->usecaseService->getListMisi($id_usecase, $validatedData);

            return $this->successResponse(data: $data, metadata: $metadata);
        } catch (Exception $e) {
            return $this->errorResponse(type:'failed', message:$e->getMessage(), statusCode:400);
        }
    }

    public function addSektor(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'nama_sektor' => 'required',
            'id_usecase' => 'required',
            'state_iku' => 'required|string',
            'kode_sektor' => 'required',
            'id_menu' => 'required',
            'link_iku' => 'required_if:state_iku,Embed',
            'nama_alamat' => 'required',
            'deskripsi' => 'required',
            'short_desc' => 'required',
            'deskripsi_detail' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
            'link_map' => 'required',
            'state_non_iku' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $validatedData = $validator->validated();

        try {
            [$data, $message] = $this->usecaseService->addSektor($id_usecase, $validatedData);
            return $this->successResponse(data: $data, message: $message);
        } catch (Exception $e) {
            return $this->errorResponse(type:'failed', message:$e->getMessage(), statusCode:400);
        }
    }

    public function listSektor($id_usecase) {
        try {
            $data = $this->usecaseService->getListSektor($id_usecase);
            return $this->successResponse(data: $data);
        } catch (Exception $e) {
            return $this->errorResponse(type:'failed', message:$e->getMessage(), statusCode:400);
        }
    }

    public function listDataSektor(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'sektor' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $validatedData = $validator->validated();

        try {
            $data = $this->usecaseService->getListDataSektor($id_usecase, $validatedData);
            return $this->successResponse(data: $data);
        } catch (Exception $e) {
            return $this->errorResponse(type:'failed', message:$e->getMessage(), statusCode:400);
        }
    }

    public function listIndikator(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'sektor' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $validatedData = $validator->validated();

        try {
            $data = $this->usecaseService->getListIndikator($id_usecase, $validatedData);
            return $this->successResponse(data: $data);
        } catch (Exception $e) {
            return $this->errorResponse(type:'failed', message:$e->getMessage(), statusCode:400);
        }
    }

    public function listSatuan() {
        try {
            $data = $this->usecaseService->getListSatuan();
            return $this->successResponse(data: $data);
        } catch (Exception $e) {
            return $this->errorResponse(type:'failed', message:$e->getMessage(), statusCode:400);
        }
    }

    public function listOpd(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'sektor' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $validatedData = $validator->validated();

        try {
            $data = $this->usecaseService->getListOpd($id_usecase, $validatedData);
            return $this->successResponse(data: $data);
        } catch (Exception $e) {
            return $this->errorResponse(type:'failed', message:$e->getMessage(), statusCode:400);
        }
    }

    public function addSektorIku(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'indikator' => 'required',
            'satuan' => 'required',
            'opd' => 'required',
            'tahun' => 'required',
            'nilai' => 'required',
            'public' => 'required',
            'sektor' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $validatedData = $validator->validated();

        try {
            [$data, $message] = $this->usecaseService->addSektorIku($id_usecase, $validatedData);
            return $this->successResponse(data: $data, message: $message);
        } catch (Exception $e) {
            return $this->errorResponse(type:'failed', message:$e->getMessage(), statusCode:400);
        }
        
    }

    public function updateSektorIku(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'id_sektor' => 'required',
            'indikator' => 'required',
            'satuan' => 'required',
            'opd' => 'required',
            'tahun' => 'required',
            'nilai' => 'required',
            'public' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $validatedData = $validator->validated();

        try {
            [$data, $message] = $this->usecaseService->updateSektorIku($id_usecase, $validatedData);
            return $this->successResponse(data: $data, message: $message);
        } catch (Exception $e) {
            return $this->errorResponse(type:'failed', message:$e->getMessage(), statusCode:400);
        }
    }

    public function deleteSektorIku(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'id_sektor' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $validatedData = $validator->validated();

        try {
            $message = $this->usecaseService->deleteSektorIku($id_usecase, $validatedData);
            return $this->successResponse(message: $message);
        } catch (Exception $e) {
            return $this->errorResponse(type:'failed', message:$e->getMessage(), statusCode:400);
        }
    }

    public function addIndikator(Request $request) {
        $validator = Validator::make($request->all(), [
            'sektor' => 'required',
            'indikator' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $validatedData = $validator->validated();

        try {
            [$data, $message] = $this->usecaseService-> addIndikator($validatedData);
            return $this->successResponse(data: $data, message: $message);
        } catch (Exception $e) {
            return $this->errorResponse(type:'failed', message:$e->getMessage(), statusCode:400);
        }
    }

    public function importSektorIKU(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'sektor' => 'required',
            'file' => 'required|file|mimes:csv,txt',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $requestFile = $request->file('file');
        $file_to_read = fopen($requestFile, 'r');
        while (!feof($file_to_read)) {
            $data[] = fgetcsv($file_to_read, 10000, ',');
        }
        fclose($file_to_read);

        $sektor = $request->sektor;

        try {
            [$data, $message] = $this->usecaseService-> importSektorIku($id_usecase, $sektor, $data);
            return $this->successResponse(data: $data, message: $message);
        } catch (Exception $e) {
            return $this->errorResponse(type:'failed', message:$e->getMessage(), statusCode:400);
        }
    }
}