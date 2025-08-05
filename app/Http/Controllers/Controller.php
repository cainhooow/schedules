<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @OA\Info(
 *  title="Schedules Documentation API interface",
 *  version="1.0.0",
 *  description="Schedules API Interface"
 * )
 */
abstract class Controller
{
    //
    use AuthorizesRequests;
}
