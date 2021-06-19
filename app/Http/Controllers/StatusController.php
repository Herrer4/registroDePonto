<?php

namespace App\Http\Controllers;

use App\Http\Resources\JsonConvet;
use App\Models\StatusEntrega;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use function GuzzleHttp\Promise\all;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return JsonConvet::collection(StatusEntrega::all());
    }

    public function store(Request $request)
    {
        StatusEntrega::create($request->all());
    }
}
