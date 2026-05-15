<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class UserController extends Controller
{
    public function index()
    {
    if (Auth::check() && Auth::user()->user_type === 'admin') {
            return view('admin.dashboard');
        }
        else if (Auth::check() && Auth::user()->user_type === 'user') {
            return view('dashboard');
        }
        else {
            return redirect()->route('login');
        }
    }

    // public function show($id)
    // {
    //     // Code to show a specific user
    // }

    // public function create()
    // {
    //     // Code to show form for creating a new user
    // }

    // public function store(Request $request)
    // {
    //     // Code to store a new user
    // }

    // public function edit($id)
    // {
    //     // Code to show form for editing a user
    // }

    // public function update(Request $request, $id)
    // {
    //     // Code to update a user
    // }

    // public function destroy($id)
    // {
    //     // Code to delete a user
    // }
}
