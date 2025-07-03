<?php
// app/Http/Controllers/Establishment/EstablishmentController.php

namespace App\Http\Controllers\Establishment;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Establishment;
use App\Http\Resources\EstablishmentResource;
use Illuminate\Support\Facades\Log;

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

public function update(Request $request, Establishment $establishment)
{
    Log::info('=== UPDATE METHOD CALLED ===', [
        'establishment_id' => $establishment->id,
        'request_method' => $request->method(),
        'request_url' => $request->fullUrl(),
        'request_data' => $request->all(),
        'headers' => $request->headers->all(),
        'user_agent' => $request->userAgent(),
        'referrer' => $request->header('referer')
    ]);

    $validated = $request->validate([
        'is_active' => 'required|boolean'
    ]);

    $establishment->update(['is_active' => $validated['is_active']]);

    Log::info('=== UPDATE COMPLETED ===', [
        'establishment_id' => $establishment->id,
        'new_state' => $establishment->is_active
    ]);

    return back()->with([
        'success' => 'Estado actualizado correctamente',
        'updatedData' => [
            'id' => $establishment->id,
            'is_active' => $establishment->is_active
        ]
    ]);
}
}

