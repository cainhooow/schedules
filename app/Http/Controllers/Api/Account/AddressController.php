<?php

namespace App\Http\Controllers\Api\Account;

use App\Exceptions\InvalidAddressException;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Http\Resources\AddressResource;
use App\Services\AddressService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AddressController extends Controller
{
    public function __construct(protected $service = new AddressService()) {}

    public function index()
    {
        return AddressResource::collection(Auth::user()->addresses);
    }

    public function create(AddressRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;
        $createdAddress = $this->service->create($data);

        return new AddressResource($createdAddress);
    }

    public function store(AddressRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;
        $createdAddress = $this->service->store($data);

        return new AddressResource($createdAddress);
    }

    public function update(AddressRequest $request, int $address)
    {
        $data = $request->validated();
        $address = $this->service->getById($address);

        if ($address->user->id !== Auth::user()->id) {
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
