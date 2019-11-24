<?php

namespace App\Http\Controllers\Home\v1;

use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\LoginRequest;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;
use Tymon\JWTAuth\JWTAuth;

class UserController extends Controller
{
    protected $userService;
    protected $JWTAuth;

    /**
     * UserController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService, JWTAuth $JWTAuth)
    {
        $this->userService = $userService;
        $this->JWTAuth = $JWTAuth;
    }


    public function signUp(CreateUserRequest $request)
    {
        try {
            $data = $request->only(['name', 'email', 'password']);

            $user = $this->userService->create($data);
            $token = $this->JWTAuth->fromUser($user);

            $data = ['token' => $token, 'expire_at' => Carbon::createFromTimestamp($this->JWTAuth->setToken($token)->getPayload()->get('exp'))->toDateTimeString(), 'info' => $user->toArray()];

            return $this->responseSuccessNoMess($data);
        } catch (\Exception $ex) {
            return $this->responseError($ex);
        }
    }
    /**
     * @OA\Post(
     *     path="/api/users/login",
     *     tags={"login"},
     *     operationId="doLogin",
     *     summary="Login User",
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Invalid input",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login Successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        try {
            $user = $this->userService->login($request->all());
            $user->last_login = Carbon::now()->toDateTimeString();
            $user->save();
            $token = $this->JWTAuth->fromUser($user);
            $data = ['token' => $token, 'expire_at' => Carbon::createFromTimestamp($this->JWTAuth->setToken($token)->getPayload()->get('exp'))->toDateTimeString(), 'info' => $user->toArray()];

            return $this->responseSuccessNoMess($data);
        } catch (UnauthorizedException $e) {
            return $this->responseError(trans('auth.failed'), [], Response::HTTP_UNAUTHORIZED);
        }
    }
}
