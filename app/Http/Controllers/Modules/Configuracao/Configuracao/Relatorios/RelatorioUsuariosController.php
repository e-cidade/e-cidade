<?php

namespace App\Http\Controllers\Modules\Configuracao\Configuracao\Relatorios;

use App\Http\Controllers\Controller;

class RelatorioUsuariosController extends Controller
{
    public function index()
    {
        return view()->file(base_path('resources/legacy/configuracao/sys1_relatoriodeusuarios.php'));
    }
}
