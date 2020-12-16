<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\LocationRepositoryInterface;
class LocationController extends Controller
{
    private $locationRepository;

    public function __construct(LocationRepositoryInterface $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }
    /**
     * Show all location data.
     *
     * @return List of location data
     */
    public function index(Request $req)
    {
        try {
            //code...
            $limit = empty($req->input('limit')) ? 5 : $req->input('limit');
            return $this->locationRepository->paginator($limit);
        } catch (\Exception $ex) {
            //throw $ex;
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Show a single location data.
     *
     * @return Single location data
     */
    public function show($id)
    {
        try {
            //code...
            return $this->locationRepository->show($id);
        } catch (\Exception $ex) {
            //throw $th;
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Adding a new location
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
            $newLocation = $this->locationRepository->store($request->all());
            DB::commit();
            return $newLocation;
        } catch (\Exception $ex) {
            //throw $ex;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Update a single data
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            //code...
            $location = $this->locationRepository->update($request->all(), $id);
            DB::commit();
            return $location;
        } catch (\Exception $ex) {
            //throw $ex;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     *
     * Delete location by location id
     *
     * @require location id
     *
     * @return message delete success
     */

     public function destroy($id)
     {
         DB::beginTransaction();
        try {
            //code...
            $location = $this->locationRepository->destroy($id);
            DB::commit();
            return $location;
        } catch (\Exception $ex) {
            //throw $ex;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
     }
}
