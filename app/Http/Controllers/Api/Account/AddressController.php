<?php

namespace App\Http\Controllers\Api\Account;

use App\Exceptions\InvalidAddressException;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Http\Resources\AddressResource;
use App\Services\AddressServices;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AddressController extends Controller
{
    public function __construct(protected $service = new AddressServices())
    {
    }

    /**
     * @OA\Get(
     *   path="/api/v1/me/address",
     *   tags={"Endereços"},
     *   summary="Informações sobre o endereço do usuário",
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="array",
     *       @OA\Items(ref="#/components/schemas/AddressResponse")
     *     )
     *   )
     * )
     */
    public function index()
    {
        return AddressResource::collection(Auth::user()->addresses);
    }
    /**
     * @OA\Post(
     *   path="/api/v1/me/address/create",
     *   tags={"Endereços"},
     *   summary="Criando um endereço padrão",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/AddressRequest")
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/AddressResponse")
     *   )
     * )
     */
    public function create(AddressRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;
        $createdAddress = $this->service->create($data);

        return new AddressResource($createdAddress);
    }
    /**
     * @OA\Post(
     *   path="/api/v1/me/address",
     *   tags={"Endereços"},
     *   summary="Criando um endereço",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/AddressRequest")
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/AddressResponse")
     *   )
     * )
     */
    public function store(AddressRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;
        $createdAddress = $this->service->store($data);

        return new AddressResource($createdAddress);
    }
    /**
     * @OA\Patch(
     *   path="/api/v1/me/address/{addressId}",
     *   tags={"Endereços"},
     *   summary="Atualizando um endereço",
     *   @OA\Parameter(
     *     name="addressId",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/AddressRequest")
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/AddressResponse")
     *   )
     * )
     */
    public function update(AddressRequest $request, int $address)
    {
        $data = $request->validated();
        $address = $this->service->getById($address);

        if (Auth::user()->id !== $address->user->id) {
            return response()->json([
                'error' => true,
                'message' => 'Você não possui permissão para este recurso'
            ], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $this->service->update($address, $data);
            return response()->json(['message' => 'Registro atualizado com sucesso'], Response::HTTP_OK);
        } catch (InvalidAddressException $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
    /**
     * @OA\Delete(
     *   path="/api/v1/me/address/{addressId}",
     *   tags={"Endereços"},
     *   summary="Deletando um endereço",
     *   @OA\Parameter(
     *     name="addressId",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         description="Mensagem confirmando a exclusão do registro"
     *       )
     *     )
     *   )
     * )
     */
    public function destroy(int $address)
    {
        $address = $this->service->getById($address);

        if ($address->user->id !== Auth::user()->id) {
            return response()->json([
                'error' => true,
                'message' => 'Você não possui permissão para este recurso'
            ], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $this->service->delete(Auth::user(), $address->id);
            return response()->json(['message' => 'Registro deletado com sucesso'], Response::HTTP_OK);
        } catch (InvalidAddressException $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
