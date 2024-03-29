<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Enums\TypeEnum;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthStoreRequest;
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
     * @OA\Post(
     *  path="/login",
     *  summary="Authenticate user",
     *  tags={"Auth"},
     *  @OA\RequestBody(
     *     @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/AuthAuthLoginRequest")),
     *  ),
     *  @OA\Response(response="200", description="success", @OA\JsonContent(example={
     *      "data": {
     *          "access_token": "eyJ0e...",
     *          "token_type": "bearer",
     *          "expires_in": 3600
     *      },
     *      "meta": {
     *          "user": {
     *              "public_id": "20ce8f4a-506b-4ebd-ab10-756494da00de",
     *              "name": "User Name Example",
     *              "email": "email@example.com",
     *              "document_number": "11122233301",
     *              "type": "pf",
     *              "updated_at": "2023-07-05T02:44:52.000000Z",
     *              "created_at": "2023-07-05T02:44:52.000000Z",
     *              "id": 3,
     *          }
     *      },
     *  })),
     *  @OA\Response(response=401, description="document number or password is invalid")
     * )
     */
    public function login(AuthLoginRequest $request): JsonResponse
    {
        $token = auth()->guard(config('auth.defaults.guard'))->attempt([
            'document_number' => \App\Helpers\UtilsHelper::onlyNumbers($request->documentNumber),
            'password' => $request->password
        ]);

        if (empty($token)) {
            throw new \Exception('document number or password is invalid', Response::HTTP_UNAUTHORIZED);
        }

        $auth = auth()->guard(config('auth.defaults.guard'));
        $user = $auth->user();
 
        return ResponseResource::handle(
            [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $auth->factory()->getTTL() * 60,
            ],
            [
                'user' => $user,
                'roles' => $user->roles()->pluck('name'),
                'permissions' => $user->getAllPermissions()->pluck('name'),
            ],
            Response::HTTP_OK
        );
    }

    /**
     * @OA\Post(
     *  path="/register",
     *  summary="Register new user",
     *  tags={"Auth"},
     *  @OA\RequestBody(
     *     @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/AuthStoreRequest")),
     *  ),
     *  @OA\Response(response="200", description="success", @OA\JsonContent(example={
     *      "data": {
     *          "public_id": "20ce8f4a-506b-4ebd-ab10-756494da00de",
     *          "name": "User Name Example",
     *          "email": "email@example.com",
     *          "document_number": "11122233301",
     *          "type": "pf",
     *          "updated_at": "2023-07-05T02:44:52.000000Z",
     *          "created_at": "2023-07-05T02:44:52.000000Z",
     *          "id": 3
     *      },
     *      "meta":{}
     *  })),
     *  @OA\Response(response=500, description="error to create user")
     * )
     */
    public function store(AuthStoreRequest $request): JsonResponse
    {
        $user = $this->userRepository->create([
            'public_id' => \Illuminate\Support\Str::uuid()->toString(),
            'name' => ucwords($request->name),
            'email' => strtolower($request->email),
            'document_number' => \App\Helpers\UtilsHelper::onlyNumbers($request->documentNumber),
            'password' => Hash::make($request->password),
            'type' => $request->type
        ]);

        $user->assignRole(RoleEnum::USER);

        switch ($user->type) {
            case TypeEnum::PESSOA_FISICA:
                $user->assignRole(RoleEnum::USER_PF);
                break;
            case TypeEnum::PESSOA_JURIDICA:
                $user->assignRole(RoleEnum::USER_PJ);
                break;
        }

        return ResponseResource::handle($user, [], Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *  path="/logout",
     *  summary="Logout user from session [user]",
     *  tags={"Auth"},
     *  security = {{"bearer":{}}},
     *  @OA\Response(response="200", description="success", @OA\JsonContent(example={
     *      "data":{"success":true},
     *      "meta":{}
     *  })),
     *  @OA\Response(response="401", description="unauthorized", @OA\JsonContent(
     *      ref="#/components/schemas/UnauthorizedResponse"
     *  )),
     * )
     */
    public function logout(): JsonResponse
    {
        auth()->guard(config('auth.defaults.guard'))->logout(true);
        auth()->guard(config('auth.defaults.guard'))->invalidate(true);

        return ResponseResource::handle(['success' => true], [], Response::HTTP_OK);
    }
}
