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
            'soldCount' => 0,
            'totalRatings' => 0,
            'averageRating' => 0
        ];


        $database = $this->firebaseService->getDatabase();
        $database->getReference('menu/' . $menuId)->set($data);


        $categoryRef = $database->getReference('categories/' . $validatedData['category'] . '/menuIds');
        $categoryRef->update([$menuId => true]);

        return redirect()->route('menu-items')->with('success', 'Menu item added successfully.');
    }


    public function edit($menuId)
    {
        try {
            $database = $this->firebaseService->getDatabase();


            $menuItemRef = $database->getReference("menu/{$menuId}");
            $menuItem = $menuItemRef->getValue();

            if (!$menuItem) {
                return redirect()->back()->with('error', 'Menu item not found.');
            }

            $categoriesRef = $database->getReference('categories');
            $categoriesSnapshot = $categoriesRef->getValue();

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

            $categoryName = 'Unknown';
            if (isset($menuItem['categoryId']) && isset($categories[$menuItem['categoryId']])) {
                $categoryName = $categories[$menuItem['categoryId']]['name'];
            }

            return view('edit-menu-item', [
                'menuItem' => $menuItem,
                'categories' => $categories,
                'selectedCategoryId' => $menuItem['category'] ?? null,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to retrieve menu item or categories.');
        }
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

    // public function archiveMenuItem($menuId)
    // {
    //     $database = $this->firebaseService->getDatabase();

    //     $menuItem = $database->getReference("menu/$menuId")->getValue();

    //     if ($menuItem) {
    //         $database->getReference("archives/menu/$menuId")->set($menuItem);

    //         $database->getReference("menu/$menuId")->remove();
    //     }


    //     return redirect()->route('menu-items')->with('success', 'Menu item move to archive successfully.');
    // }

    public function archive($menuId)
    {
        $database = $this->firebaseService->getDatabase();


        $menuItem = $database->getReference('menu/' . $menuId)->getValue();

        if (!$menuItem) {
            return response()->json(['success' => false, 'message' => 'Menu item not found'], 404);
        }


        $categoryId = $menuItem['category'];


        $database->getReference('archives/menu/' . $menuId)->set($menuItem);


        $database->getReference('menu/' . $menuId)->remove();

        $database->getReference('categories/' . $categoryId . '/menuIds/' . $menuId)->remove();

        return redirect()->route('menu-items')->with('success', 'Menu Item successfully put to the archive.');
    }
    public function update(Request $request, $menuId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'categoryId' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $firebaseService = new FirebaseService();
        $existingMenuItem = $firebaseService->getMenuItemById($menuId);

        if (!$existingMenuItem) {
            return redirect()->route('menu-items')->with('error', 'Menu item not found.');
        }

        $updateData = [
            'name' => $request->input('name'),
            'menuDescription' => $request->input('description'),
            'price' => (int)$request->input('price'),
            'category' => $request->input('categoryId'),
            'averageRating' => $existingMenuItem['averageRating'],
            'totalRatings' => $existingMenuItem['totalRatings'],
        ];

        $imageUrls = [];

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

        if (!empty($imageUrls)) {
            $updateData['imageUrl'] = array_values(array_unique($imageUrls));
        } else {
            $updateData['imageUrl'] = $existingMenuItem['imageUrl'];
        }

        if ($existingMenuItem['category'] !== $updateData['category']) {
            $oldCategoryId = $existingMenuItem['category'];
            $this->removeMenuIdFromCategory($oldCategoryId, $menuId);
            $this->addMenuIdToCategory($updateData['category'], $menuId);
        }

        $firebaseService->updateMenuItem($menuId, $updateData);

        return redirect()->route('menu-items')->with('success', 'Menu item updated successfully.');
    }
    private function uploadImageToFirebaseStorage($image)
    {
        $storagePath = 'menu-images/' . time() . '_' . $image->getClientOriginalName();
        $image->storeAs('menu-images', $storagePath, 'firebase');

        return $this->firebaseService->getStorage()->getBucket()->object($storagePath)->signedUrl(new \DateTime('tomorrow')); // Adjust time as needed
    }

    private function removeMenuIdFromCategory($categoryId, $menuId)
    {
        $categoryRef = $this->firebaseService->getDatabase()->getReference('categories/' . $categoryId . '/menuIds');
        $menuIds = $categoryRef->getValue() ?: [];

        if (isset($menuIds[$menuId])) {
            unset($menuIds[$menuId]);
        }

        $categoryRef->set($menuIds);
    }

    private function addMenuIdToCategory($categoryId, $menuId)
    {
        $categoryRef = $this->firebaseService->getDatabase()->getReference('categories/' . $categoryId . '/menuIds');
        $menuIds = $categoryRef->getValue() ?: [];

        $menuIds[$menuId] = true;

        $categoryRef->set($menuIds);
    }
}
