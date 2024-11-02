<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Kreait\Firebase\Contract\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Kreait\Firebase\Database;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    protected $firebaseService;
    protected $firebaseStorage;

    public function __construct(FirebaseService $firebaseService, Storage $firebaseStorage)
    {
        $this->firebaseService = $firebaseService;
        $this->firebaseStorage = $firebaseStorage;
    }

    public function index(Request $request)
    {
        $database = $this->firebaseService->getDatabase();

        $staffSnapshot = $database->getReference('staff')->getValue();
        $rolesSnapshot = $database->getReference('roles')->getValue();

        if ($staffSnapshot) {
            $staffCollection = collect($staffSnapshot)->map(function ($staff, $id) use ($rolesSnapshot) {

                $staff['id'] = $id;
                $roleId = $staff['roleId'];
                $staff['roleName'] = isset($rolesSnapshot[$roleId]) ? $rolesSnapshot[$roleId]['name'] : 'Unknown Role';
                return $staff;
            });


            $sortedStaff = $staffCollection->sortByDesc('created_at');

            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $perPage = 10;
            $currentPageItems = $sortedStaff->slice(($currentPage - 1) * $perPage, $perPage)->all();

            $paginatedStaff = new LengthAwarePaginator(
                $currentPageItems,
                $sortedStaff->count(),
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return view('staff_display', [
                'staffs' => $paginatedStaff
            ]);
        } else {
            return view('staff_display', [
                'staffs' => collect([])
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'role' => ['required', function ($attribute, $value, $fail) {
                $roleExists = $this->firebaseService->getDatabase()->getReference('roles')
                    ->orderByKey()
                    ->equalTo($value)
                    ->getSnapshot()->exists();

                if (!$roleExists) {
                    $fail('The selected role does not exist.');
                }
            }],
            'email' => 'required|email|max:255',
            'phone' => 'required|string|regex:/^[0-9]{10}$/',
            'password' => 'required|min:8',
            'retypepass' => 'required|same:password',
            'input_image' => 'required|image|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $existingUser = $this->firebaseService->usernameExists($request->username);

        // Uncomment if you want to check for existing username
        // if ($existingUser) {
        //     return redirect()->back()->withErrors(['username' => 'Username already exists'])->withInput();
        // }

        $profileImage = null;

        if ($request->hasFile('input_image')) {
            $image = $request->file('input_image');
            $imagePath = 'staff/' . time() . '_' . $image->getClientOriginalName();


            $uploadedFile = $this->firebaseStorage->getBucket()->upload(
                file_get_contents($image->getRealPath()),
                [
                    'name' => $imagePath,
                    'predefinedAcl' => 'publicRead'
                ]
            );


            $profileImage = 'https://storage.googleapis.com/' . $this->firebaseStorage->getBucket()->name() . '/' . $imagePath;
        }

        $phone = '+63' . $request->phone;

        $hashedPassword = Hash::make($request->password);

        $staffData = [
            'fullname' => $request->fullname,
            'username' => $request->username,
            'profileImage' => $profileImage,
            'password' => $hashedPassword,
            'roleId' => $request->role,
            'email' => $request->email,
            'phone' => $phone,
            'created_at' => now()->toDateTimeString(),
        ];

        $this->firebaseService->saveData('staff', $staffData);

        return redirect()->route('staff.index')->with('success', 'Staff added successfully!');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'profileImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|regex:/^[0-9]{10}$/',
        ]);

        $staffId = Session::get('staffId');
        $database = $this->firebaseService->getDatabase();
        $staffData = $database->getReference('staff/' . $staffId)->getValue();

        if ($request->hasFile('profileImage')) {
            $image = $request->file('profileImage');
            $imagePath = 'staff/' . time() . '_' . $image->getClientOriginalName();

            $uploadedFile = $this->firebaseStorage->getBucket()->upload(
                file_get_contents($image->getRealPath()),
                [
                    'name' => $imagePath,
                    'predefinedAcl' => 'publicRead'
                ]
            );

            if (!empty($staffData['profileImage'])) {
                $oldImagePath = basename($staffData['profileImage']);
                $this->firebaseStorage->getBucket()->object('staff/' . $oldImagePath)->delete();
            }


            $staffData['profileImage'] = 'https://storage.googleapis.com/' . $this->firebaseStorage->getBucket()->name() . '/' . $imagePath;
        }

        $staffData['fullname'] = $request->input('fullname');
        $staffData['username'] = $request->input('username');
        $staffData['email'] = $request->input('email');
        $staffData['phone'] = '+63' . $request->input('phone');

        $database->getReference('staff/' . $staffId)->update($staffData);

        $userSession = Session::get('user');
        $userSession['profileImage'] = $staffData['profileImage'] ?? $userSession['profileImage'];
        $userSession['fullname'] = $staffData['fullname'];
        $userSession['username'] = $staffData['username'];
        $userSession['email'] = $staffData['email'];
        $userSession['phone'] = $staffData['phone'];
        Session::put('user', $userSession);

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }



    public function usernameExists($username)
    {
        $staffRef = $this->firebaseService->getDatabase()->getReference('staff')
            ->orderByChild('username')
            ->equalTo($username)
            ->getSnapshot();

        return $staffRef->exists();
    }

    public function destroy($id)
    {
        $database = $this->firebaseService->getDatabase();

        $staffMember = $database->getReference('staff/' . $id)->getValue();

        if (!$staffMember) {
            return redirect()->route('staff.index')->withErrors(['staff' => 'Staff member not found.']);
        }

        if (Session::get('staffId') == $id) {
            return redirect()->route('staff.index')->with('error', 'You cannot delete your own account.');
        }

        $database->getReference('staff/' . $id)->remove();

        return redirect()->route('staff.index')->with('success', 'Staff member removed successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|min:8',
        ], [
            'current-password.required' => 'Current password is required.',
            'new-password.required' => 'New password is required.',
            'new-password.min' => 'New password must be at least 8 characters.',
        ]);

        $staffId = Session::get('staffId');
        if (!$staffId) {
            return redirect()->back()->with('error', 'User not authenticated.');
        }

        $currentPasswordInSession = Session::get('user')['password'];

        if (!Hash::check($request->input('current-password'), $currentPasswordInSession)) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        if ($request->input('new-password') !== $request->input('new-password-confirmation')) {
            return redirect()->back()->with('error', 'New password and confirmation do not match.');
        }

        $newPasswordHash = Hash::make($request->input('new-password'));
        $database = app('firebase.database');
        $database->getReference('staff/' . $staffId . '/password')->set($newPasswordHash);


        $userSession = Session::get('user');
        $userSession['password'] = $newPasswordHash;
        Session::put('user', $userSession);

        return redirect()->back()->with('success', 'Password updated successfully.');
    }
}
