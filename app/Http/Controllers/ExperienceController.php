<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\ExperienceRepositoryInterface;

class ExperienceController extends Controller
{
    private $experienceRepository;

    public function __construct(ExperienceRepositoryInterface $experienceRepository)
    {
        $this->experienceRepository = $experienceRepository;
    }
    /**
     * Show all experience data.
     *
     * @return List of experience data
     */
    public function index(Request $req)
    {
        try {
            //code...
            $limit = empty($req->input('limit')) ? 5 : $req->input('limit');
            return $this->experienceRepository->paginator($limit);
        } catch (\Exception $ex) {
            //throw $th;
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Show a single experience data.
     *
     * @return Single experience data
     */
    public function show($id)
    {
        try {
            //code...
            return $this->experienceRepository->show($id);
        } catch (\Exception $ex) {
            //throw $th;
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Adding a new experience
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
            $new_experience = $this->experienceRepository->store($request->all());
            DB::commit();
            return $new_experience;
        } catch (\Exception $ex) {
            //throw $th;
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
            $experience = $this->experienceRepository->update($request->all(), $id);
            DB::commit();
            return $experience;
        } catch (\Exception $ex) {
            //throw $th;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     *
     * Delete experience by experience id
     *
     * @require experience id
     *
     * @return message delete success
     */

     public function destroy($id)
     {
         DB::beginTransaction();
        try {
            //code...
            $experience= $this->experienceRepository->destroy($id);
            DB::commit();
            return $experience;
        } catch (\Exception $ex) {
            //throw $th;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
     }
}
