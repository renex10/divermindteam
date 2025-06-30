<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        // Obtiene todos los usuarios y carga sus relaciones anidadas
        // para evitar múltiples consultas a la base de datos (problema N+1).
        $users = User::with('establishment.commune.region')->get();

        // Le dice a Inertia que renderice el componente Vue
        // 'Dashboard/users/Users' y le pasa la variable 'users' como prop.
        return inertia('Dashboard/users/Users', [
            'users' => $users,
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
