<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\QuizUsers;

class UserController extends Controller
{
    public function index()
    {
        $users = QuizUsers::paginate();
        return view('users.index', compact('users'));
    }
}
