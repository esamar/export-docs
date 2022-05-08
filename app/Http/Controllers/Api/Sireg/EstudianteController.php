<?php

namespace App\Http\Controllers\Api\Sireg;

use App\Sireg\Estudiante;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Sireg\V1\EstudianteResource;

class EstudianteController extends Controller
{

    public function index()
    {

        return Estudiante::getAllEstudianteRegistro()->chunk(300);

    }

    public function store(Request $request)
    {
        //
    }

    public function show($id_estudiante)
    {

        return new EstudianteResource(Estudiante::getEstudianteRegistro($id_estudiante)[0]);

    }

    public function update(Request $request, Estudiante $estudiante)
    {
        //
    }

    public function destroy(Estudiante $estudiante)
    {
        //
    }
}
