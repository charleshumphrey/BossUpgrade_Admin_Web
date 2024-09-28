<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login()
    {
        request()->validate([
            'username' => 'required|min:5|max:240',
            'password' => 'required|min:3|max:240'
        ]);

        $uname = request()->get('username');
        $pass = request()->get('password');

        if ($uname == 'patatas') {
            if ($pass == '1234') {
                return redirect()->route('dashboard', null)->with('success', 'Login successfully!');
            }
        }
    }

    public function authentiCate() {}
}
