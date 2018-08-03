<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User; // 追加

class UsersController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);

        return view('tasks.index', [
            'users' => $users,
        ]);
    }
    public function show($id)
    {
        $user = User::find($id);

        return view('tasks.show', [
            'user' => $user,
        ]);
    }
}
