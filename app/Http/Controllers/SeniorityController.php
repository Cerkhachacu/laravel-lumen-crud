<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\SeniorityRepositoryInterface;
class SeniorityController extends Controller
{
    private $seniorityRepository;

    public function __construct(SeniorityRepositoryInterface $seniorityRepository)
    {
        $this->seniorityRepository = $seniorityRepository;
    }
    /**
     * Show all seniority data.
     *
     * @return List of seniority data
     */
    public function index(Request $req)
    {
        try {
            //code...
            $limit = empty($req->input('limit')) ? 5 : $req->input('limit');
            return $this->seniorityRepository->paginator($limit);
        } catch (\Exception $ex) {
            //throw $th;
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Show a single seniority data.
     *
     * @return Single seniority data
     */
    public function show($id)
    {
        try {
            //code...
            return $this->seniorityRepository->show($id);
        } catch (\Exception $ex) {
            //throw $th;
            return $this($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Adding a new seniority
     *
     * @request name, last_updated_by
     *
     * @return message success
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            //code...
            $newSeniority = $this->seniorityRepository->store($request->all());
            DB::commit();
            return $newSeniority;
        } catch (\Exception $ex) {
            //throw $th;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     *
     * Update a single item
     *
     * @require id
     *
     * @return message update success
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            //code...
            $seniority = $this->seniorityRepository->update($request->all(), $id);
            DB::commit();
            return $seniority;
        } catch (\Exception $ex) {
            //throw $th;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     *
     * Delete seniority by seniority id
     *
     * @require seniority id
     *
     * @return message delete success
     */

     public function destroy($id)
     {
         DB::beginTransaction();
        try {
            //code...
            $seniority = $this->seniorityRepository->destroy($id);
            DB::commit();
            return $seniority;
        } catch (\Exception $ex) {
            //throw $th;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
     }
}
