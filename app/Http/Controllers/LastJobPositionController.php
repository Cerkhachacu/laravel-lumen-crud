<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\LastJobPositionRepositoryInterface;

class LastJobPositionController extends Controller
{
    private $lastJobPositionRepository;

    public function __construct(LastJobPositionRepositoryInterface $lastJobPositionRepository)
    {
        $this->lastJobPositionRepository = $lastJobPositionRepository;
    }
    /**
     * Show all lastjobposition data.
     *
     * @return List of lastjobposition data
     */
    public function index(Request $req)
    {
        try {
            //code...
            $limit = empty($req->input('limit')) ? 5 : $req->input('limit');
            return $this->lastJobPositionRepository->paginator($limit);
        } catch (\Exception $ex) {
            //throw $ex;
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Show a single lastjobposition data.
     *
     * @return Single lastjobposition data
     */
    public function show($id)
    {
        try {
            //code...
            return $this->lastJobPositionRepository->show($id);
        } catch (\Exception $ex) {
            //throw $th;
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Adding a new lastjobposition
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
            $newLastJobPosition = $this->lastJobPositionRepository->store($request->all());
            DB::commit();
            return $newLastJobPosition;
        } catch (\Exception $ex) {
            //throw $ex;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            //code...
            $lastJobPosition = $this->lastJobPositionRepository->update($request->all(), $id);
            DB::commit();
            return $lastJobPosition;
        } catch (\Exception $ex) {
            //throw $th;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     *
     * Delete lastjobposition by lastjobposition id
     *
     * @require lastjobposition id
     *
     * @return message delete success
     */

     public function destroy($id)
     {
         DB::beginTransaction();
        try {
            //code...
            $lastJobPosition = $this->lastJobPositionRepository->destroy($id);
            DB::commit();
            return $lastJobPosition;
        } catch (\Exception $ex) {
            //throw $ex;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
     }
}
