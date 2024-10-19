<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Kreait\Firebase\Contract\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    protected $firebaseService;
    protected $firebaseStorage;

    public function __construct(FirebaseService $firebaseService, Storage $firebaseStorage)
    {
        $this->firebaseService = $firebaseService;
        $this->firebaseStorage = $firebaseStorage;
    }
    public function login(Request $request)
    {
        $database = $this->firebaseService->getDatabase();

        $username = $request->input('username');
        $password = $request->input('password');

        $staffRef = $database->getReference('staff')->getValue();
        foreach ($staffRef as $staffId => $staffData) {
            if ($staffData['username'] === $username) {
                if (Hash::check($password, $staffData['password'])) {

                    $roleId = $staffData['roleId'];
                    $roleData = $database->getReference('roles/' . $roleId)->getValue();

                    Session::put('logged_in', true);
                    Session::put('user', $staffData);
                    Session::put('permissions', $roleData['permissions']);

                    return redirect()->route('dashboard');
                }
            }
        }

        return redirect()->back()->withErrors('Invalid credentials!');
    }

    // public function login()
    // {
    //     request()->validate([
    //         'username' => 'required|min:5|max:240',
    //         'password' => 'required|min:3|max:240'
    //     ]);

    //     $uname = request()->get('username');
    //     $pass = request()->get('password');

    //     if ($uname == 'patatas') {
    //         if ($pass == '12345678') {
    //             return redirect()->route('dashboard', null)->with('success', 'Login successfully!');
    //         }
    //     }
    // }

    public function logout(Request $request)
    {
        Session::flush();

        $request->session()->invalidate();

        return redirect()->route('login')->with('success', 'You have successfully logged out.');
    }
    public function authentiCate() {}
}
