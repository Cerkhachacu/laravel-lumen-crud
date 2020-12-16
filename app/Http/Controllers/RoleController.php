<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\RoleRepositoryInterface;
class RoleController extends Controller
{
    private $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }
    /**
     * Show all role data.
     *
     * @return List of role data
     */
    public function index(Request $req)
    {
        try {
            //code...
            $limit = empty($req->input('limit')) ? 5 : $req->input('limit');
            return $this->roleRepository->paginator($limit);
        } catch (\Exception $ex) {
            //throw $ex;
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Show a single role data.
     *
     * @return Single role data
     */
    public function show($id)
    {
        try {
            //code...
            return $this->roleRepository->show($id);
            } catch (\Exception $ex) {
            //throw $th;
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Adding a new role
     *
     * @request name, last_update_by
     *
     * @return message success
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            //code...
            $newRole = $this->roleRepository->store($request->all());
            DB::commit();
            return $newRole;
        } catch (\Exception $ex) {
            //throw $ex;
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
            $role = $this->roleRepository->update($request->all(), $id);
            DB::commit();
            return $role;
        } catch (\Exception $ex) {
            //throw $ex;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     *
     * Delete role by role id
     *
     * @require role id
     *
     * @return message delete success
     */

     public function destroy($id)
     {
         DB::beginTransaction();
        try {
            //code...
            $role = $this->roleRepository->destroy($id);
            DB::commit();
            return $role;
        } catch (\Exception $ex) {
            //throw $ex;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
     }
}
