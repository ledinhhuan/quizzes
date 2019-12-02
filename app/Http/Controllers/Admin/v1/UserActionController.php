<?php

namespace App\Http\Controllers\Admin\v1;

use App\Models\UserAction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserActionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin/list-action-users",
     *     tags={""},
     *     operationId="listAction",
     *     summary="List Action of User",
     *   security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=401,
     *         description="Token not provided",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */
    public function listActionUser ()
    {
        $userActions = UserAction::query()->paginate(10);
        if ($userActions->total() == 0) {
            return $this->responseError('user_action.empty_action', [], 404);
        }
        return $this->responseSuccessNoMess($userActions);
    }
}
