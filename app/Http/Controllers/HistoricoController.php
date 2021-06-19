<?php

namespace App\Http\Controllers;

use App\Models\Ponto;
use App\Models\Historico;
use Illuminate\Http\Request;

class HistoricoController extends Controller
{
    public function index(Request $request){
        $entregas = Ponto::with('historico')->where('codigo', $request->codigo)->first();
        if ($entregas){
            return response()->json($entregas->historico, 200);
        }else{
            return response()->json([], 404);
        }

    }
    public function store(Request $request)
    {
        Historico::create($request->all());
    }
}
