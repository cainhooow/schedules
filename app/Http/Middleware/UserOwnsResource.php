<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UserOwnsResource
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(
        Request $request,
        Closure $next,
        string $serviceClass,
        string $routeParam,
        string $ownerField = "user_id"
    ): Response {
        $service = "\\App\\Services\\$serviceClass";
        if (!class_exists($service)) {
            return response()->json([
                'message' => "O service responsável por [{$serviceClass}] não foi encontrada"
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $id = request()->route($routeParam);
        if (!$id) {
            return $next($request);
        }

        $service = new $service();
        if (!method_exists($service, "getById")) {
            return response()->json([
                'message' => "O serviço [{$serviceClass}] não implementa getById()"
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $model = $service->getById($id);
        if (!$model) {
            return response()->json([
                'message' => "Recurso não encontrado"
            ], Response::HTTP_NOT_FOUND);
        }

        $ownerId = data_get($model, $ownerField);
        if ($ownerId !== Auth::user()->id) {
            return response()->json([
                'message' => "Acesso negado ao recurso solicitado"
            ], Response::HTTP_FORBIDDEN);
        }

        $request->attributes->add(['authorized_' . $routeParam => $model]);
        return $next($request);
    }
}
