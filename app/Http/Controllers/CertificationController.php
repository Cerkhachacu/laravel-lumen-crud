<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\CertificationRepositoryInterface;
use Illuminate\Support\Facades\DB;
class CertificationController extends Controller
{
    private $certificationRepository;

    public function __construct(CertificationRepositoryInterface $certificationRepository)
    {
        $this->certificationRepository = $certificationRepository;
    }
    /**
     * Show all certification data.
     *
     * @return List of certification data
     */
    public function index(Request $req)
    {
        try {
            //code...
            $certification = !empty($req->input('limit')) ? $req->input('limit') : 5;
            return $this->certificationRepository->paginator($certification);
        } catch (\Exception $ex) {
            //throw $th;
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Show a single certification data.
     *
     * @return Single certification data
     */
    public function show($id)
    {
        try {
            //code...
            return $this->certificationRepository->show($id);
        } catch (\Exception $ex) {
            //throw $th;
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Adding a new certification
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
            $newCertification = $this->certificationRepository->store($request->all());
            DB::commit();
            return $newCertification;
        } catch (\Exception $ex) {
            //throw $th;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            //code...
            $certification = $this->certificationRepository->update($request-> all(), $id);
            DB::commit();
            return $certification;
        } catch (\Exception $ex) {
            //throw $th;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     *
     * Delete certification by certification id
     *
     * @require certification id
     *
     * @return message delete success
     */

     public function destroy($id)
     {
        DB::beginTransaction();
        try {
            //code...
            $certification = $this->certificationRepository->destroy($id);
            DB::commit();
            return $certification;
        } catch (\Exception $ex) {
            //throw $th;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
     }
}
