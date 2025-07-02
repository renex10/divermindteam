<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Region;
use App\Models\Commune;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
  public function index()
{
    $users = User::with('establishment.commune.region')->get();
    $regions = Region::with('country')->get();
    $communes = Commune::with('region')->get();
    
    // DEBUG
    Log::info('UserController index:', [
        'users_count' => $users->count(),
        'regions_count' => $regions->count(),
        'communes_count' => $communes->count(),
    ]);

    return inertia('Dashboard/users/Users', [
        'users' => $users,
        'regions' => $regions,
        'communes' => $communes,
    ]);
}

    
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
