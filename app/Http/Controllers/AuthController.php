<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\StoreRequest;
use App\Http\Resources\ResponseResource;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct(private UserRepository $userRepository)
    {
        //
    }

    /**
     * @OA\POST(
     *  path="/login",
     *  summary="Authenticate user",
     *  tags={"Auth"},
     *  @OA\RequestBody(
     *     @OA\JsonContent(
     *        required={"documentNumber","password"},
     *        @OA\Property(property="documentNumber", type="string", format="text", example="11122233344"),
     *        @OA\Property(property="password", type="string", format="text", example="mypass"),
     *     ),
     *  ),
     *  @OA\Response(response=200, description="user authenticated"), 
     *  @OA\Response(response=401, description="document number or password is invalid")
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $token = auth()->guard(config('auth.defaults.guard'))->attempt([
            'document_number' => \App\Helpers\UtilsHelper::onlyNumbers($request->documentNumber),
            'password' => $request->password
        ]);

        if (empty($token)) {
            throw new \Exception('document number or password is invalid', 401);
        }

        $auth = auth()->guard(config('auth.defaults.guard'));
 
        return ResponseResource::handle(
            [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $auth->factory()->getTTL() * 60,
            ],
            ['user' => $auth->user()],
            Response::HTTP_OK
        );
    }

    /**
     * @OA\POST(
     *  path="/register",
     *  summary="Register new user",
     *  tags={"Auth"},
     *  @OA\RequestBody(
     *     @OA\JsonContent(
     *        required={"documentNumber","password"},
     *        @OA\Property(property="name", type="string", example="João Neves"),
     *        @OA\Property(property="email", type="email", example="joao@example.com"),
     *        @OA\Property(property="documentNumber", type="string", example="11122233344"),
     *        @OA\Property(property="password", type="string", example="mypass"),
     *        @OA\Property(property="type", type="string", example="pf"),
     *     ),
     *  ),
     *  @OA\Response(response=200, description="user created"), 
     *  @OA\Response(response=500, description="error to create user")
     * )
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $user = $this->userRepository->insert([
            'public_id' => \Illuminate\Support\Str::uuid()->toString(),
            'name' => ucwords($request->name),
            'email' => strtolower($request->email),
            'document_number' => \App\Helpers\UtilsHelper::onlyNumbers($request->documentNumber),
            'password' => Hash::make($request->password),
            'type' => $request->type
        ]);

        if (empty($user)) {
            throw new \Exception('error to create user', 500);
        }

        return ResponseResource::handle($user, [], Response::HTTP_CREATED);
    }

    /**
     * @OA\GET(
     *  path="/logout",
     *  summary="Logout user from session",
     *  tags={"Auth"},
     *  security = {"jwt"},
     *  @OA\Response(response=200, description="user created"), 
     *  @OA\Response(response=500, description="error to create user")
     * )
     */
    public function logout(): JsonResponse
    {
        try {
            auth()->guard(config('auth.defaults.guard'))->logout(true);
            auth()->guard(config('auth.defaults.guard'))->invalidate(true);
        } catch (\Throwable $th) {
            throw $th;
        }

        return ResponseResource::handle(['success' => true], [], Response::HTTP_OK);
    }
}
