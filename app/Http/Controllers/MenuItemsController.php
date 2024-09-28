<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Kreait\Firebase\Contract\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class MenuItemsController extends Controller
{
    protected $firebaseService;
    protected $firebaseStorage;

    public function __construct(FirebaseService $firebaseService, Storage $firebaseStorage)
    {
        $this->firebaseService = $firebaseService;
        $this->firebaseStorage = $firebaseStorage;
    }

    public function index()
    {
        $database = $this->firebaseService->getDatabase();

        $data = $database->getReference('menu')->getValue();


        return view('menu_items', compact('data'));
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category' => 'required',
        ]);

        $imageUrls = [];
        $menuId = Str::random(20);


        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = Str::random(20) . '.' . $image->getClientOriginalExtension();
                $firebaseStoragePath = 'menu/' . $imageName;

                $uploadedFile = $this->firebaseStorage->getBucket()->upload(
                    file_get_contents($image->getRealPath()),
                    [
                        'name' => $firebaseStoragePath,
                        'predefinedAcl' => 'publicRead',
                    ]
                );


                $imageUrl = 'https://storage.googleapis.com/' . $this->firebaseStorage->getBucket()->name() . '/' . $firebaseStoragePath;
                $imageUrls[] = $imageUrl;
            }
        }


        $data = [
            'menuId' => $menuId,
            'name' => $validatedData['name'],
            'menuDescription' => $validatedData['description'],
            'price' => (int) $validatedData['price'],
            'imageUrl' => $imageUrls,
            'category' => $validatedData['category'],
            'time_added' => now()->toDateTimeString(),
        ];


        $database = $this->firebaseService->getDatabase();
        $database->getReference('menu/' . $menuId)->set($data);


        $categoryRef = $database->getReference('categories/' . $validatedData['category'] . '/menuIds');
        $categoryRef->update([$menuId => true]);

        return redirect()->route('menu-items')->with('success', 'Menu item added successfully.');
    }



    public function paginatedData(Request $request)
    {

        $database = $this->firebaseService->getDatabase();

        $menuData = $database->getReference('menu')->getValue();
        $categoriesSnapshot = $database->getReference('categories')->getValue();

        $selectedCategoryId = $request->input('category');

        if ($selectedCategoryId) {
            $filteredMenu = [];

            if (isset($categoriesSnapshot[$selectedCategoryId]['menuIds'])) {
                foreach ($categoriesSnapshot[$selectedCategoryId]['menuIds'] as $menuId => $value) {
                    if (isset($menuData[$menuId])) {
                        $filteredMenu[$menuId] = $menuData[$menuId];
                    }
                }
            }
            $menuData = $filteredMenu;
        }

        $categories = [];
        if ($categoriesSnapshot) {
            foreach ($categoriesSnapshot as $categoryId => $categoryData) {
                if (isset($categoryData['categoryName'])) {
                    $categories[] = [
                        'id' => $categoryId,
                        'name' => $categoryData['categoryName']
                    ];
                }
            }
        }


        usort($menuData, function ($a, $b) {
            return $b['time_added'] <=> $a['time_added'];
        });

        $collection = collect($menuData);
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $paginatedData = new LengthAwarePaginator(
            $currentPageItems,
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('menu_items', [
            'data' => $paginatedData,
            'categories' => $categories,
            'selectedCategory' => $selectedCategoryId
        ]);
    }
    public function destroy($key)
    {
        try {
            $database = $this->firebaseService->getDatabase();
            $database->getReference('menu/' . $key)->remove();

            return redirect()->route('menu-items')->with('success', 'Menu item deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('menu-items')->with('error', 'Failed to delete menu item.');
        }
    }

    public function archiveMenuItem($id)
    {
        $database = $this->firebaseService->getDatabase();

        $menuItem = $database->getReference("menu/$id")->getValue();

        if ($menuItem) {
            $database->getReference("archives/menu/$id")->set($menuItem);

            $database->getReference("menu/$id")->remove();
        }


        return redirect()->route('menu-items')->with('success', 'Menu item move to archive successfully.');
    }
}
