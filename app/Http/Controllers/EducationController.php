<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\EducationRepositoryInterface;
use Illuminate\Support\Facades\DB;

class EducationController extends Controller
{
    private $educationRepository;

    public function __construct(EducationRepositoryInterface $educationRepository)
    {
        $this->educationRepository = $educationRepository;
    }
    /**
     * Show all education data.
     *
     * @return List of education data
     */
    public function index(Request $req)
    {
        try {
            //code...
            $paginate = !empty($req->input('limit')) ? $req->input('limit') : 5;
            return $this->educationRepository->paginator($paginate);
        } catch (\Exception $ex) {
            //throw $th;
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Show a single education data.
     *
     * @return Single education data
     */
    public function show($id)
    {
        try {
            //code...
            return $this->educationRepository->show($id);
        } catch (\Exception $ex) {
            //throw $th;
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Adding a new education
     *
     * @request name, last_updated_by, email, password, role
     *
     * @return message success
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $new_education = $this->educationRepository->store($request->all());
            DB::commit();
            return $new_education;
        } catch (\Exception $ex) {
            //throw $th;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Update a single data
     * @require $id
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            //code...
            $education = $this->educationRepository->update($request->all(), $id);
            DB::commit();
            return $education;
        } catch (\Exception $ex) {
            //throw $th;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     *
     * Delete education by education id
     *
     * @require education id
     *
     * @return message delete success
     */

     public function destroy($id)
     {
         DB::beginTransaction();
        try {
            //code...
            $education = $this->educationRepository->destroy($id);
            DB::commit();
            return $education;
        } catch (\Exception $ex) {
            //throw $th;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
     }
}
