<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Entities\Experience;
use App\Transformers\ExperienceTransformer;

class ExperienceController extends Controller
{
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
            $response = $this->paginate(Experience::orderBy('updated_at', 'desc')->paginate($limit), new ExperienceTransformer());
            return $this->responseJSON('List of data found', $response);
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
            if(!$experience = Experience::find($id)) return $this->notFound('Experience', 404, $id);
            $response=$this->item($experience, new ExperienceTransformer());
            return $this->responseJSON('Experience with id = '. $id .' found', $response);
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
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255|unique:experiences',
                'last_updated_by' => 'required|exists:users,id'
            ]);
            if ($validator->fails()) {
                return $this->validationError($validator->errors());
            }
            $new_experience = Experience::create([
                'name' => $request->input('name'),
                'last_updated_by' => $request->input('last_updated_by')
            ]);
            $new_experience = $this->item($new_experience, new ExperienceTransformer());
            DB::commit();
            return $this->responseJSON('Data is stored successfully!', $new_experience, 201);
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
            $validator = Validator::make($request->all(), [
                'name' => 'unique:experiences|max:255',
                'last_updated_by' => 'exists:users,id|required'
            ]);
            if ($validator->fails()) {
                return $this->validationError($validator->errors());
            }
            if($experience = Experience::find($id)) return $this->notFound('Experience', 404, $id);
            $experience->update([
                'name' => $request->input('name') ? $request->input('name'):$experience->name,
                'last_updated_by' => $request->input('last_updated_by') ? $request->input('last_updated_by'):$experience->last_updated_by
            ]);
            $response = $this->item($experience, new ExperienceTransformer());
            DB::commit();
            return $this->responseJSON('Education with id = '. $id . ' is updated successfully', $response);
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
            if(!$experience = Experience::find($id)) return $this->notFound('Experience', 404, $id);
            $experience->delete();
            DB::commit();
            return $this->responseJSON('Delete success!', ['id'=> $id]);
        } catch (\Exception $ex) {
            //throw $th;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
     }
}
