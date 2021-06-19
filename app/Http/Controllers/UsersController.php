<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    public function index()
    {
        return \response()->json(User::with(['roles'])->get());
    }

    public function store(Request $request)
    {
        $rule[] = $request->rule_id;
        $request = [
            'name' => $request->name,
            'cpf' => $request->cpf,
            'valor_hora' => $request->valor_hora,
            'email' => $request->email,
            'password' => password_hash($request->password, PASSWORD_BCRYPT),
        ];
        $user = User::create($request);

        $user->roles()->sync($rule);
        return \response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        $rule[] =  $request->input('roles', [])[0]['id'];
        $user->update($request->all());
        $user->roles()->sync($rule);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }
}
