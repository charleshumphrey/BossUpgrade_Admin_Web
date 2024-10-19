<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Kreait\Firebase\Auth as FirebaseAuth;

class AuthController extends Controller
{
    protected $firebaseAuth;

    public function __construct(FirebaseAuth $firebaseAuth)
    {
        $this->firebaseAuth = $firebaseAuth;
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        $firebase = app('firebase.database');
        $staffRef = $firebase->getReference('staff');
        $staffData = $staffRef->orderByChild('username')->equalTo($username)->getSnapshot()->getValue();

        if ($staffData) {
            $staff = reset($staffData);

            if (password_verify($password, $staff['password'])) {
                $roleRef = $firebase->getReference('roles/' . $staff['roleId']);
                $role = $roleRef->getSnapshot()->getValue();

                Session::put('staff', [
                    'username' => $staff['username'],
                    'fullname' => $staff['fullname'],
                    'role' => $role['name'],
                    'permissions' => $role['permissions'],
                ]);

                return redirect()->route('dashboard');
            }
        }

        return back()->withErrors(['Invalid username or password']);
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login');
    }
}
