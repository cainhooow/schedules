<?php

namespace App\Http\Controllers\Api\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Services\ProfileServices;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    public function __construct(protected $service = new ProfileServices()) {}
    //
    public function index()
    {
        return new ProfileResource(Auth::user()->profile);
    }
    public function store(ProfileRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;
        $profileCreated = $this->service->store($data);

        return new ProfileResource($profileCreated);
    }
    public function update(ProfileRequest $request)
    {
        $data = $request->validated();
        $profileUpdated = $this->service->update(Auth::user()->profile->id, $data);

        if (!$profileUpdated) {
            return response()->json([
                'error' => true,
                'message' => 'Não foi possivel atualizar o perfil, tente novamente mais tarde'
            ], Response::HTTP_NOT_FOUND);
        }

        return new ProfileResource(
            $this->service->getById(Auth::user()->profile->id)
        );
    }

    public function destroy() {}
}
