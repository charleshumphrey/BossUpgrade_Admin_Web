<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Kreait\Firebase\Contract\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    protected $firebaseService;
    protected $firebaseStorage;
    public function __construct(FirebaseService $firebaseService, Storage $firebaseStorage)
    {
        $this->firebaseService = $firebaseService;
        $this->firebaseStorage = $firebaseStorage;
    }
    public function paginatedData(Request $request)
    {
        $database = $this->firebaseService->getDatabase();

        $menuData = $database->getReference('archives/menu')->getValue() ?? [];
        $categoriesSnapshot = $database->getReference('categories')->getValue();

        $selectedCategoryId = $request->input('category');


        if ($selectedCategoryId && $categoriesSnapshot) {
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


        if (!empty($menuData)) {
            usort($menuData, function ($a, $b) {
                return $b['time_added'] <=> $a['time_added'];
            });
        }


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

        return view('archive', [
            'data' => $paginatedData,
            'categories' => $categories,
            'selectedCategory' => $selectedCategoryId
        ]);
    }


    public function destroy($menuId)
    {

        try {
            $database = $this->firebaseService->getDatabase();
            $database->getReference('archives/menu/' . $menuId)->remove();

            return redirect()->route('archive.index')->with('success', 'Menu Item deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('archive.index')->with('error', 'Failed to Category.');
        }
    }

    public function unarchive($menuId)
    {
        $database = $this->firebaseService->getDatabase();


        $menuItem = $database->getReference('archives/menu/' . $menuId)->getValue();


        if (!$menuItem) {
            return response()->json(['success' => false, 'message' => 'Archived menu item not found'], 404);
        }


        $categoryId = $menuItem['category'];


        $database->getReference('menu/' . $menuId)->set($menuItem);


        $database->getReference('categories/' . $categoryId . '/menuIds/' . $menuId)->set(true);


        $database->getReference('archives/menu/' . $menuId)->remove();

        return redirect()->route('archive')->with('success', 'Menu Item successfully restored from the archive.');
    }
}
