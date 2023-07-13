<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserStoreRequest;
use App\Http\Resources\ResponseResource;
use App\Repositories\UserRepository;
use App\Services\WalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository,
        protected WalletService $walletService
    ) {
        //
    }

    /**
     * @OA\Get(
     *  path="/me",
     *  summary="Info about authenticated user [admin, user]",
     *  tags={"User"},
     *  security = {{"bearer":{}}},
     *  @OA\Response(response="200", description="success", @OA\JsonContent(example={
     *      "data":{
     *          "id": 1,
     *          "public_id": "4b0a8cf3-dbb0-43a1-9b99-a90056793ade",
     *          "name": "User Name",
     *          "email": "user@mail.com",
     *          "document_number": "12345678900",
     *          "type": "pf",
     *          "created_at": "2023-07-04T05:58:15.000000Z",
     *          "updated_at": "2023-07-04T05:58:15.000000Z",
     *          "deleted_at": null
     *      },
     *      "meta":{"balance":1000}
     *  })),
     *  @OA\Response(response="401", description="unauthorized", @OA\JsonContent(
     *      ref="#/components/schemas/UnauthorizedResponse"
     *  )),
     * )
     */
    public function me(): JsonResponse
    {
        $user = auth()->guard(config('auth.defaults.guard'))->user();
        $balance = $this->walletService->getBalance($user->id);

        return ResponseResource::handle($user, [
            'roles' => $user->roles()->pluck('name'),
            'permissions' => $user->getAllPermissions()->pluck('name'),
            'balance' => $balance,
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *   tags={"User"},
     *   path="/users/{id}",
     *   summary="User show [admin]",
     *   security = {{"bearer":{}}},
     *   @OA\Parameter(
     *      name="id",
     *      description="users.id",
     *      in="path",
     *      example="1",
     *      required=true,
     *      @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(response="200", description="success", @OA\JsonContent(example={
     *      "data":{
     *          "id": 1,
     *          "public_id": "4b0a8cf3-dbb0-43a1-9b99-a90056793ade",
     *          "name": "User Name",
     *          "email": "user@mail.com",
     *          "document_number": "12345678900",
     *          "type": "pf",
     *          "created_at": "2023-07-04T05:58:15.000000Z",
     *          "updated_at": "2023-07-04T06:15:20.000000Z",
     *          "deleted_at": null
     *      },
     *      "meta":{"roles":{"admin","user"}}
     *   })),
     *   @OA\Response(response="401", description="unauthorized", @OA\JsonContent(
     *      ref="#/components/schemas/UnauthorizedResponse"
     *   )),
     * )
     */
    public function show(int $id): JsonResponse
    {
        $user = $this->userRepository->findOrFail($id, ['*']);

        return ResponseResource::handle($user->toArray(), [
            'roles' => $user->roles()->pluck('name')
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Patch(
     *   tags={"User"},
     *   path="/users/{id}",
     *   summary="User update [admin]",
     *   security = {{"bearer":{}}},
     *   @OA\Parameter(
     *      name="id",
     *      description="users.id",
     *      in="path",
     *      example="1",
     *      required=true,
     *      @OA\Schema(type="integer")
     *   ),
     *   @OA\RequestBody(
     *      @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/UserStoreRequest")),
     *   ),
     *   @OA\Response(response="200", description="success", @OA\JsonContent(example={
     *      "data":{
     *          "id": 1,
     *          "public_id": "4b0a8cf3-dbb0-43a1-9b99-a90056793ade",
     *          "name": "User Name",
     *          "email": "user@mail.com",
     *          "document_number": "12345678900",
     *          "type": "pf",
     *          "created_at": "2023-07-04T05:58:15.000000Z",
     *          "updated_at": "2023-07-04T06:15:20.000000Z",
     *          "deleted_at": null
     *      },
     *      "meta":{"roles":{"admin","user"}}
     *   })),
     *   @OA\Response(response="401", description="unauthorized", @OA\JsonContent(
     *      ref="#/components/schemas/UnauthorizedResponse"
     *   )),
     * )
     */
    public function update(UserStoreRequest $request, int $id): JsonResponse
    {
        $data = $request->only('name', 'email');

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        $user = $this->userRepository->update($id, $data);

        $user->syncRoles($request->roles);

        return ResponseResource::handle($user->toArray(), [
            'roles' => $user->roles()->pluck('name')
        ], Response::HTTP_OK);
    }
}
