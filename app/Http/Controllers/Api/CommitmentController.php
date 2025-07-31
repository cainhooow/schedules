<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CommitmentService;
use Illuminate\Http\Request;

class CommitmentController extends Controller
{
    public function __construct(
        protected CommitmentService $service
    ) {}

    public function index() {}
}
