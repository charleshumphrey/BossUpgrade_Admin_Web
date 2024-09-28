<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Kreait\Firebase\Contract\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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

        // Fetch staff data
        $staffSnapshot = $database->getReference('staff')->getValue();
        // Fetch roles data
        $rolesSnapshot = $database->getReference('roles')->getValue();

        // Check if staffSnapshot exists
        if ($staffSnapshot) {
            $staffCollection = collect($staffSnapshot)->map(function ($staff, $id) use ($rolesSnapshot) {
                // Add the role name to each staff member
                $staff['id'] = $id;
                $roleId = $staff['roleId'];
                $staff['roleName'] = isset($rolesSnapshot[$roleId]) ? $rolesSnapshot[$roleId]['name'] : 'Unknown Role';
                return $staff;
            });

            // Sort staff by created_at
            $sortedStaff = $staffCollection->sortByDesc('created_at');

            // Pagination
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
            'input_image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $existingUser = $this->firebaseService->usernameExists($request->username);

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
            'profileImage' => $profileImage ?? null,
            'password' => $hashedPassword,
            'roleId' => $request->role,
            'email' => $request->email,
            'phone' => $phone,
            'created_at' => now()->toDateTimeString(),
        ];

        $this->firebaseService->saveData('staff', $staffData);

        return redirect()->route('staff.index')->with('success', 'Staff added successfully!');
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
        $database->getReference('staff/' . $id)->remove();

        return redirect()->route('staff.index')->with('success', 'Staff member deleted successfully!');
    }
}
