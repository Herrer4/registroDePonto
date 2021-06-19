<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePontoRequest;
use App\Http\Resources\JsonConvet;
use App\Servicos\Gerador;
use App\Models\Ponto;
use App\Models\Historico;
use Carbon\Carbon;
use DateTime;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use IntlDateFormatter;

class PontosController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return LengthAwarePaginator
     */
    public function index()
    {
        if(Auth::user()->isAdmin){
            $per_page = empty(request('per_page')) ? 10 : (int)request('per_page');
            $entregas = Ponto::orderBy('id','desc')->paginate($per_page);
            return $entregas;
        }else{
            $per_page = empty(request('per_page')) ? 10 : (int)request('per_page');
            $entregas = Ponto::where('user_id', Auth::user()->getAuthIdentifier())->orderBy('id','desc')->paginate($per_page);
            return $entregas;
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePontoRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePontoRequest $request)
    {
        $hoje = Ponto::whereDate('created_at', Carbon::now()->locale('pt-BR'))->where('user_id', $request->user()->id)->first();
        if(!$hoje){
            $hoje = Ponto::create([
                'ponto_1' => $request->hora,
                'user_id' => $request->user()->id
            ]);
        }else{
            $hoje->justificativa = $request->justificativa??null;
            if (!$hoje->ponto_1):
                $hoje->ponto_1 = $request->hora;
            elseif(!$hoje->ponto_2):
                $hoje->ponto_2 = $request->hora;
            elseif(!$hoje->ponto_3):
                $hoje->ponto_3 = $request->hora;
            elseif(!$hoje->ponto_4):
                $hoje->ponto_4 = $request->hora;
            else:
                return response()->json($hoje,413);
            endif;
            $hoje->save();
        }
        return response()->json($hoje,201);
    }

    public function show($id)
    {
        $pontos = Ponto::whereMonth('created_at', Carbon::now())->where('user_id',\request()->user()->id)->get();
        $totalhorasMes = 0;
        foreach ($pontos as $ponto){
            $totalhorasMes += $ponto->totalHoras;
        }
        $data = new DateTime();
        $formatter = new IntlDateFormatter('pt_BR',
            IntlDateFormatter::FULL,
            IntlDateFormatter::NONE,
            'America/Sao_Paulo',
            IntlDateFormatter::GREGORIAN,'MMMM');
        $month_name = $formatter->format($data);

        return response()->json(['pontos'=>$pontos, 'usuario'=>request()->user(),'mes'=>$month_name, 'totalHorasMes'=>$totalhorasMes]);
    }
    public function destroy($id)
    {
        Ponto::find($id)->delete();
    }
}
