<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\DrivingLicenseRepositoryInterface;

class DrivingLicenseController extends Controller
{
    private $drivinglicenseRepository;

    public function __construct(DrivingLicenseRepositoryInterface $drivinglicenseRepository)
    {
        $this->drivinglicenseRepository = $drivinglicenseRepository;
    }
    /**
     * Show all drivinglicense data.
     *
     * @return List of drivinglicense data
     */
    public function index(Request $req)
    {
        try {
            //code...
            $limit = empty($req->input('limit')) ? 5 : $req->input('limit');
            return $this->drivinglicenseRepository->paginator($limit);
        } catch (\Exception $ex) {
            //throw $th;
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Show a single drivinglicense data.
     *
     * @return Single drivinglicense data
     */
    public function show($id)
    {
        try {
            //code...
            return $this->drivinglicenseRepository->show($id);
        } catch (\Exception $ex) {
            //throw $th;
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Adding a new drivinglicense
     *
     * @request name, last_updated_by, email, password, role
     *
     * @return message success
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            //code...
            $newDrivingLicense = $this->drivinglicenseRepository->store($request->all());
            DB::commit();
            return $newDrivingLicense;
        } catch (\Exception $ex) {
            //throw $th;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Update a sinle data
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            //code...
            $driverLicense = $this->drivinglicenseRepository->update($request->all(), $id);
            DB::commit();
            return $driverLicense;
        } catch (\Exception $ex) {
            //throw $th;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     *
     * Delete drivinglicense by drivinglicense id
     *
     * @require drivinglicense id
     *
     * @return message delete success
     */

     public function destroy($id)
     {
        try {
            //code...
            $driverLicense = $this->drivinglicenseRepository->destroy($id);
            DB::commit();
            return $driverLicense;
        } catch (\Exception $ex) {
            //throw $th;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
     }
}
