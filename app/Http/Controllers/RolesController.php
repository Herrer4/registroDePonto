<?php

namespace App\Http\Controllers;

use App\Models\Role;

class RolesController extends Controller
{
    public function index()
    {
//        abort_if(true, Response::HTTP_FORBIDDEN, '403 Forbidden');
        return \response()->json(Role::all(), 202);
    }
}
