<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\JsonApiSerializer;


class Controller extends BaseController
{
    //
    private function getFractalManager()
    {
        $request = app(Request::class);
        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer());
        if (!empty($request->query('include'))) {
            $manager->parseIncludes($request->query('include'));
        }
        return $manager;
    }

    // Single item transformer to get a single data
    public function item($data, $transformer)
    {
        $manager = $this->getFractalManager();
        $resource = new Item($data, $transformer, $transformer->type);
        return $manager->createData($resource)->toArray();
    }

    // Collection item transformer to get a collection of data
    public function collection($data, $transformer)
    {
        $manager = $this->getFractalManager();
        $resource = new Collection($data, $transformer, $transformer->type);
        return $manager->createData($resource)->toArray();
    }

    /**
     * @param LengthAwarePaginator $data
     * @param $transformer
     * @return array
     */
    public function paginate($data, $transformer)
    {
        $manager = $this->getFractalManager();
        $resource = new Collection($data, $transformer, $transformer->type);
        $resource->setPaginator(new IlluminatePaginatorAdapter($data));
        return $manager->createData($resource)->toArray();
    }

    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    protected function responseJSON($message, $data, $code = 200)
    {
        return $this->successResponse(array_merge([
            'success' => true,
            'message' => $message,
        ], $data), $code);
    }

    public function notFound($modelName, $code, $id = null, $message = null)
    {
        $message = !$message ? $id ? $modelName . " with id = " . $id . " not found" : $modelName . " not found" : $message;
        return response()->json([
            'code' => $code,
            'success' => false,
            'message' => $message,
        ], $code);
    }

    public function otherError($error_message, $error_code)
    {
        return response()->json([
            'code' => $error_code,
            'success' => false,
            'message' => $error_message,
        ], $error_code);
    }

    public function validationError($error)
    {
        return response()->json([
            'code' => 422,
            'success' => false,
            'message' => $error,
        ], 422);
    }
}
