<?php

namespace App\Http\Controllers\Specialties;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
class SpecialtiesController extends Controller

{
 public function index(Request $request)
{
    $query = Specialty::query();
    
    // Ordenamiento
    if ($request->has('sort') && $request->has('direction')) {
        $query->orderBy($request->sort, $request->direction);
    } else {
        $query->orderBy('created_at', 'desc');
    }
    
    $specialties = $query->paginate(5);
    
    return inertia('Dashboard/specialties/Index', [
        'specialties' => $specialties
    ]);
}
}
