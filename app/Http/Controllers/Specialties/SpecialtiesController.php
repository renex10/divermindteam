<?php

namespace App\Http\Controllers\Specialties;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
class SpecialtiesController extends Controller

{
  public function index()
    {
        return Inertia::render('Dashboard/specialties/Index', [
       
        ]);
    }
}
