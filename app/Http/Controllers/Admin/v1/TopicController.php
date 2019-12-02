<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Requests\Topic\CreateRequest;
use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TopicController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin/topics",
     *     tags={""},
     *     operationId="viewTopics",
     *     summary="List Topics Admin",
     *   security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=401,
     *         description="Token not provided",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List Topics Successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */
    public function index()
    {
        try {
            $data = Topic::query()->withCount('questions')->get();
            return $this->responseSuccessNoMess($data);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return $this->responseError($ex);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * @OA\Post(
     *     path="/api/admin/topics",
     *     tags={""},
     *     operationId="doTopic",
     *     summary="Create Topics Admin",
     *   security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token not provided",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Create Topic Successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */
    public function store(CreateRequest $request)
    {
        try {
            $data = $request->only(['title']);
            $topic = Topic::create($data);
            return $this->responseCreate($topic);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return $this->responseError($ex);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
