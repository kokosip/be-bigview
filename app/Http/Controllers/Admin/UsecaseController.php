<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\UsecaseServices;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UsecaseController extends Controller
{
    use ApiResponse;
    protected $usecaseService;
    protected $user_id;
    protected $id_usecase;

    public function __construct(UsecaseServices $usecaseService)
    {
        $this->usecaseService = $usecaseService;
        $this->user_id = Auth::user()->id_user ?? null;
        $this->id_usecase = Auth::user()->id_usecase ?? null;
    }

    public function listUsecase(Request $request){
        $validator = Validator::make($request->all(), [
            'search' => 'required',
            'per_page'=> 'sometimes',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $search = $request->input("search");
        $perPage = is_null($request->input('per_page')) ? 10 : $request->input('per_page');

        [$data, $metadata] = $this->usecaseService->getListUsecase($search, $perPage);
        return $this->successResponse(data: $data, metadata: $metadata);
    }

    public function listNameUsecase(){
        $data = $this->usecaseService->getListNameUsecase();

        return $this->successResponse(data: $data);
    }
    public function getUsecaseById($id_usecase){
        $data = $this->usecaseService->getUsecaseById($id_usecase);

        return $this->successResponse(data: $data);
    }

    public function getUsecaseProfileById($id_usecase) {
        $data = $this->usecaseService->getUsecaseProfileById($id_usecase);

        return $this->successResponse(data: $data);
    }

    public function getUserUsecase() {
        $data = $this->usecaseService->getUsecaseById($this->id_usecase);

        return $this->successResponse(data: $data);
    }

    public function getUserProfile() {
        $data = $this->usecaseService->getUsecaseProfileById($this->id_usecase);

        return $this->successResponse(data: $data);
    }

    public function addUsecase(Request $request) {
        $validator = Validator::make($request->all(), [
            'name_usecase' => 'required',
            'kode_provinsi' => 'required',
            'kode_kab_kota' =>  'required',
            'base_color1' => 'required',
            'base_color2' => 'required',
            'base_color3' => 'required',
            'base_color4' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        [$usecase, $message] = $this->usecaseService->addUsecase($validator->validate());

        return $this->successResponse(data: $usecase, message: $message);
    }

    public function addUsecaseProfile(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'nama_pimpinan' => 'required',
            'jabatan_pimpinan' => 'required',
            'nama_wakil' => 'required',
            'jabatan_wakil' => 'required',
            'alamat' => 'sometimes',
            'telepon' => 'sometimes',
            'link_map' => 'sometimes'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        [$profile, $message] = $this->usecaseService->addUsecaseProfile($validator->validate(), $id_usecase);

        return $this->successResponse(data: $profile, message: $message);
    }

    public function updateUsecase(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'name_usecase' => 'required',
            'kode_provinsi' => 'required',
            'kode_kab_kota' =>  'required',
            'base_color1' => 'required',
            'base_color2' => 'required',
            'base_color3' => 'required',
            'base_color4' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        [$profile, $message] = $this->usecaseService->updateUsecase($validator->validate(), $id_usecase);

        return $this->successResponse(data: $profile, message: $message);
    }

    public function updateUsecaseProfile(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'nama_pimpinan' => 'required',
            'jabatan_pimpinan' => 'required',
            'nama_wakil' =>  'required',
            'jabatan_wakil' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
            'link_map' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        [$profile, $message] = $this->usecaseService->updateUsecaseProfile($validator->validate(), $id_usecase);

        return $this->successResponse(data: $profile, message: $message);
    }

    public function updateUserProfile(Request $request) {
        $validator = Validator::make($request->all(), [
            'nama_pimpinan' => 'required',
            'jabatan_pimpinan' => 'required',
            'nama_wakil' =>  'required',
            'jabatan_wakil' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
            'link_map' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        [$profile, $message] = $this->usecaseService->updateUsecaseProfile($validator->validate(), $this->id_usecase);

        return $this->successResponse(data: $profile, message: $message);
    }

    public function deleteUsecase($id_usecase) {
        $message = $this->usecaseService->deleteUsecase($id_usecase);
        return $this->successResponse(message: $message);
    }

    public function deleteUsecaseProfile($id_usecase) {
        $message = $this->usecaseService->deleteUsecaseProfile($id_usecase);
        return $this->successResponse(message: $message);
    }

    public function getAllPolygon() {
        $data = $this->usecaseService->getAllPolygon();
        return $this->successResponse(data: $data);
    }

    public function uploadPolygon(Request $request) {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'polygon' => 'required|file|mimetypes:application/json'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        [$polygon, $message] = $this->usecaseService->uploadPolygon($validator->validate());
        return $this->successResponse(data: $polygon, message: $message);
    }

    public function updateUsecasePolygon(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'id_polygon' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $message = $this->usecaseService->updateUsecasePolygon($validator->validate(), $id_usecase);
        return $this->successResponse(message: $message);
    }

    public function getUsecasePolygon($id_usecase) {
        $data = $this->usecaseService->getUsecasePolygon($id_usecase);
        return $this->successResponse(data: $data);
    }

    public function getUserPolygon() {
        $data = $this->usecaseService->getUsecasePolygon($this->id_usecase);
        return $this->successResponse(data: $data);
    }

    public function uploadLogo(Request $request, $id_usecase){
        $validator = Validator::make($request->all(), [
            'file' => 'required|file',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        [$message, $data] = $this->usecaseService->setLogo($id_usecase, $validator->validate());
        return $this->successResponse(data: $data, message: $message);
    }

    public function getLogo($id_usecase){
        $data = $this->usecaseService->getLogo($id_usecase);

        return $this->successResponse(data: $data);
    }

    public function deleteLogo($id_usecase){
        [$data, $message] = $this->usecaseService->deleteLogo($id_usecase);
        return $this->successResponse(data: $data, message: $message);
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
    
        [$data, $message] = $this->usecaseService->setProfile($id_usecase, $validatedData);
        return $this->successResponse(data: $data, message: $message);
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
    
        [$data, $message] = $this->usecaseService->updateContact($id_usecase, $validatedData);
        return $this->successResponse(data: $data, message: $message);
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

        [$data, $message] = $this->usecaseService->addVisi($id_usecase, $validatedData);
        return $this->successResponse(data: $data, message: $message);
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
        [$data, $message] = $this->usecaseService->updateVisi($id_usecase, $validatedData);
        return $this->successResponse(data: $data, message: $message);
    }

    public function updateUserVisi(Request $request) {
        $validator = Validator::make($request->all(), [
            'id_visi' => 'required|string',
            'short_desc' => 'required|string',
            'description' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $validatedData = $validator->validated();
        [$data, $message] = $this->usecaseService->updateVisi($this->id_usecase, $validatedData);
        return $this->successResponse(data: $data, message: $message);
    }

    public function deleteVisi(Request $request, $id_usecase) {

        $validator = Validator::make($request->all(), [
            'id_visi' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $validatedData = $validator->validated();

        $message = $this->usecaseService->deleteVisi($id_usecase, $validatedData);

        return $this->successResponse(message: $message);
    }

    public function listVisi(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'perPage' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $validatedData = $validator->validated();

        [$data, $metadata] = $this->usecaseService->getListVisi($id_usecase, $validatedData);

        return $this->successResponse(data: $data, metadata: $metadata);
    }
    
    public function listUserVisi(Request $request) {
        $validator = Validator::make($request->all(), [
            'perPage' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $validatedData = $validator->validated();

        [$data, $metadata] = $this->usecaseService->getListVisi($this->id_usecase, $validatedData);

        return $this->successResponse(data: $data, metadata: $metadata);
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

        [$data, $message] = $this->usecaseService->addMisi($id_usecase, $validatedData);
        return $this->successResponse(data: $data, message: $message);
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

        [$data, $message] = $this->usecaseService->updateMisi($id_usecase, $validatedData);
        return $this->successResponse(data: $data, message: $message);
    }

    public function updateUserMisi(Request $request) {
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

        [$data, $message] = $this->usecaseService->updateMisi($this->id_usecase, $validatedData);
        return $this->successResponse(data: $data, message: $message);
    }

    public function deleteMisi(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'id_misi' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $validatedData = $validator->validated();

        $message = $this->usecaseService->deleteMisi($id_usecase, $validatedData);
        return $this->successResponse(message: $message);
    }

    public function listMisi(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'perPage' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        [$data, $metadata] = $this->usecaseService->getListMisi($id_usecase, $validator->validated());
        return $this->successResponse(data: $data, metadata: $metadata);
    }

    public function listUserMisi(Request $request) {
        $validator = Validator::make($request->all(), [
            'perPage' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        [$data, $metadata] = $this->usecaseService->getListMisi($this->id_usecase, $validator->validated());
        return $this->successResponse(data: $data, metadata: $metadata);
    }

    public function listSektor($id_usecase) {
        $data = $this->usecaseService->getListSektor($id_usecase);
        return $this->successResponse(data: $data);
    }

    public function listDataSektor(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'sektor' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->usecaseService->getListDataSektor($id_usecase, $validator->validated());
        return $this->successResponse(data: $data);
    }

    public function listIndikator(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'sektor' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->usecaseService->getListIndikator($id_usecase, $validator->validated());
        return $this->successResponse(data: $data);
    }

    public function listSatuan() {
        $data = $this->usecaseService->getListSatuan();
        return $this->successResponse(data: $data);
    }

    public function listOpd(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'sektor' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->usecaseService->getListOpd($id_usecase, $validator->validated());
        return $this->successResponse(data: $data);
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
        [$data, $message] = $this->usecaseService->addSektorIku($id_usecase, $validator->validated());
        return $this->successResponse(data: $data, message: $message);
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

        [$data, $message] = $this->usecaseService->updateSektorIku($id_usecase, $validator->validated());
        return $this->successResponse(data: $data, message: $message);
    }

    public function deleteSektorIku(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'id_sektor' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $validatedData = $validator->validated();

        $message = $this->usecaseService->deleteSektorIku($id_usecase, $validatedData);
        return $this->successResponse(message: $message);
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

        [$data, $message] = $this->usecaseService-> addIndikator($validatedData);
        return $this->successResponse(data: $data, message: $message);
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

        [$data, $message] = $this->usecaseService-> importSektorIku($id_usecase, $sektor, $data);
        return $this->successResponse(data: $data, message: $message);
    }

    public function addSektor(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'nama_sektor' => 'required',
            'state_iku' => 'required',
            'kode_sektor' => 'required',
            'id_menu' => 'required',
            'link_iku' => 'nullable',
            'nama_alamat' => 'required',
            'deskripsi' => 'required',
            'short_desc' => 'required',
            'deskripsi_detail' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
            'link_map' => 'required',
            'state_non_iku' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        [$data, $message] = $this->usecaseService->addSektor($validator->validate(), $id_usecase);
        return $this->successResponse(data: $data, message: $message);
    }

    public function deleteSektor($id_sektor) {
        $message = $this->usecaseService->deleteSektor($id_sektor);
        return $this->successResponse(message: $message);
    }

    public function updateSektor(Request $request, $id_sektor) {
        $validator = Validator::make($request->all(), [
            'nama_sektor' => 'required',
            'state_iku' => 'required',
            'kode_sektor' => 'required',
            'id_menu' => 'required',
            'link_iku' => 'nullable',
            'nama_alamat' => 'required',
            'deskripsi' => 'required',
            'short_desc' => 'required',
            'deskripsi_detail' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
            'link_map' => 'required',
            'state_non_iku' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        [$data, $message] = $this->usecaseService->updateSektor($validator->validate(), $id_sektor);
        return $this->successResponse(data: $data, message: $message);
    }

    public function getSektorUsecase($id_usecase) {
        $data= $this->usecaseService->getSektorUsecase($id_usecase);
        return $data;
    }

    public function editSubadminSektor(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'id_subadmin' => 'required',
            'sektor_order' => 'required|array',
            'sektor_order.*' => 'integer'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $this->usecaseService->editSubadminSektor($validator->validate(), $id_usecase);
        return $this->successResponse(data: $data);
    }

    public function sortSektor(Request $request, $id_usecase) {
        $validator = Validator::make($request->all(), [
            'sektor_order' => 'required|array',
            'sektor_order.*' => 'integer'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $this->usecaseService->sortSektor($validator->validate(), $id_usecase);
        return $this->successResponse(data: $data);
    }

    public function getAssignedSektor() {
        $data = $this->usecaseService->getAssignedSektor($this->user_id);
        return $data;
    }

    public function updateAssignedSektor(Request $request, $id_sektor) {
        $validator = Validator::make($request->all(), [
            'nama_sektor' => 'required',
            'state_iku' => 'required',
            'kode_sektor' => 'required',
            'id_menu' => 'required',
            'link_iku' => 'nullable',
            'nama_alamat' => 'required',
            'deskripsi' => 'required',
            'short_desc' => 'required',
            'deskripsi_detail' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
            'link_map' => 'required',
            'state_non_iku' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        [$data, $message] = $this->usecaseService->updateAssignedSektor($validator->validate(), $id_sektor, $this->user_id);
        return $this->successResponse(data: $data, message: $message);
    }
}