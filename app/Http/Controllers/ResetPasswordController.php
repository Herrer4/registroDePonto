<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResetPasswordController extends Controller
{

    public function store(Request $request)
    {
        if (Auth::attempt(['email' => $request->user()->email, 'password' => $request->correntPass]))
        {
            if ($request->newPass && $request->confPass && $request->newPass == $request->confPass){
                $user = User::find($request->user()->id);
                $user->password = password_hash($request->newPass, PASSWORD_BCRYPT);
                $user->save();
            }
            return response()->json([],202);
        }
        return response()->json([],401);
    }
}
