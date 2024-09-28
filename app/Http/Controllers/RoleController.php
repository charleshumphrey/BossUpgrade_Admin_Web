<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Kreait\Firebase\Contract\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class RoleController extends Controller
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
        $rolesSnapshot = $database->getReference('roles')->getValue();

        if ($rolesSnapshot) {
            $rolesCollection = collect($rolesSnapshot)->map(function ($role, $id) {
                $role['id'] = $id;
                return $role;
            });

            $sortedRoles = $rolesCollection->sortByDesc('created_at');

            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $perPage = 10;
            $currentPageItems = $sortedRoles->slice(($currentPage - 1) * $perPage, $perPage)->all();

            $paginatedRoles = new LengthAwarePaginator(
                $currentPageItems,
                $sortedRoles->count(),
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return view('roles_staff', [
                'roles' => $paginatedRoles
            ]);
        } else {
            return view('roles_staff', [
                'roles' => collect([])
            ]);
        }
    }
    public function addStaff()
    {
        try {
            $database = $this->firebaseService->getDatabase();
            $rolesRef = $database->getReference('roles');

            $rolesSnapshot = $rolesRef->getValue();

            $roles = [];
            if ($rolesSnapshot) {
                foreach ($rolesSnapshot as $roleId => $roleData) {
                    if (isset($roleData['name'])) {
                        $roles[] = [
                            'id' => $roleId,
                            'name' => $roleData['name']
                        ];
                    }
                }
            }

            return view('staff_create', ['roles' => $roles]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to retrieve roles.');
        }
    }
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'required|array|min:1',
        ], [
            'permissions.required' => 'You must select at least one permission.',
            'permissions.min' => 'You must select at least one permission.',
        ]);

        $name = $request->input('name');
        $permissions = $request->input('permissions', []);

        $database = $this->firebaseService->getDatabase();

        $newRoleId = $database->getReference('roles')->push()->getKey();

        $database->getReference('roles/' . $newRoleId)->set([
            'name' => $name,
            'permissions' => $permissions,
            'created_at' => now()->toDateTimeString(),
        ]);

        return redirect()->route('roles.index')->with('success', 'Role created successfully!');
    }
    public function destroy($role)
    {
        $database = $this->firebaseService->getDatabase();


        $database->getReference('roles/' . $role)->remove();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully!');
    }


    public function update(Request $request, $role)
    {
        $name = $request->input('name');
        $permissions = $request->input('permissions', []);
        $database = $this->firebaseService->getDatabase();


        $database->getReference('roles/' . $role)->update([
            'name' => $name,
            'permissions' => $permissions
        ]);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully!');
    }
    public function edit($role)
    {
        $editing = true;

        return view('roles_update_add', compact('role', 'editing'));
    }
}
