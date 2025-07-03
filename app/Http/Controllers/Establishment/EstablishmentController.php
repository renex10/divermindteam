<?php
// app/Http/Controllers/Establishment/EstablishmentController.php

namespace App\Http\Controllers\Establishment;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Establishment;
use App\Http\Resources\EstablishmentResource;

class EstablishmentController extends Controller
{


public function index()
{
    $establishments = Establishment::paginate(8);

    return Inertia::render('Dashboard/establishments/Index', [
        'establishments' => [
            'data' => EstablishmentResource::collection($establishments)->resolve(),
            'pagination' => [
                'total' => $establishments->total(),
                'per_page' => $establishments->perPage(),
                'current_page' => $establishments->currentPage(),
                'last_page' => $establishments->lastPage(),
                'prev_page_url' => $establishments->previousPageUrl(),
                'next_page_url' => $establishments->nextPageUrl(),
            ]
        ]
    ]);
}

}

