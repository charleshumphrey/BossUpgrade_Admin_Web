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

        $menuData = $database->getReference('archives/menu')->getValue();
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
}
