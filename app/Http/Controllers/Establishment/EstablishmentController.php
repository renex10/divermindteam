<?php
// app/Http/Controllers/Establishment/EstablishmentController.php
namespace App\Http\Controllers\Establishment;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Establishment;


class EstablishmentController extends Controller
{
  public function index()
    {
        return Inertia::render('Dashboard/establishments/Index', [
            'establishments' => Establishment::all()
        ]);
    }
}
