<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fund;

class FundController extends Controller
{
    /**
     * GET /api/funds
     * Retorna todos os fundos disponíveis
     */
    public function index()
    {
        return response()->json(Fund::all());
    }
}
