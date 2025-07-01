<?php

namespace App\Http\Controllers\Establishment;

use App\Http\Controllers\Controller;

use App\Http\Requests\Establishment\StoreEstablishmentRequest;
use Illuminate\Http\Request;
use App\Models\Establishment;
use App\Models\Commune;
use Inertia\Inertia;

class EstablishmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

     public function create()
    {
        return Inertia::render('Establishments/Create', [
            'communes' => Commune::with('region.country')->get()
        ]);
    }

    public function store(StoreEstablishmentRequest $request)
    {
        $establishment = Establishment::create($request->validated());
        
        return redirect()->route('establishments.index')
            ->with('success', 'Establecimiento creado exitosamente');
    }
    public function show(Establishment $establishment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Establishment $establishment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Establishment $establishment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Establishment $establishment)
    {
        //
    }
}
