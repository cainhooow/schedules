<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CommitmentServices;
use Illuminate\Http\Request;

class CommitmentController extends Controller
{
    public function __construct(
        protected CommitmentServices $service
    ) {}

    public function index() {}
}
