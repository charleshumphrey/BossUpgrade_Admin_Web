<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\FirebaseService;
use Kreait\Firebase\Contract\Storage;
use Carbon\Carbon;
use Kreait\Firebase\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class CategoryController extends Controller
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

        $data = $database->getReference('categories')->getValue();


        return view('category', compact('data'));
    }

    public function addMenu()
    {
        try {
            $database = $this->firebaseService->getDatabase();
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

            return view('add_menu_items', ['categories' => $categories]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to retrieve categories.');
        }
    }


    public function store(Request $request)
    {
        $request->validate([
            'category-name' => 'required|string|max:255',
        ]);

        try {
            $categoryName = $request->input('category-name');

            $database = $this->firebaseService->getDatabase();
            $categoriesRef = $database->getReference('categories');

            $categoriesSnapshot = $categoriesRef->getValue();

            $categoryExists = false;
            if ($categoriesSnapshot) {
                foreach ($categoriesSnapshot as $categoryId => $categoryData) {
                    if (isset($categoryData['categoryName']) && $categoryData['categoryName'] === $categoryName) {
                        $categoryExists = true;
                        break;
                    }
                }
            }

            if ($categoryExists) {
                return redirect()->back()->with('error', 'Category name already exists.');
            }


            $categoryData = [
                'categoryName' => $categoryName,
                'added_date' => Carbon::now()->toDateTimeString(),
            ];


            $categoriesRef->push($categoryData);

            return redirect()->back()->with('success', 'Category added successfully!');
        } catch (\Exception $e) {
            Log::error('Error adding category: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add category.');
        }
    }


    public function paginatedData(Request $request)
    {
        $database = $this->firebaseService->getDatabase();
        $data = $database->getReference('categories')->getValue();

        $categoriesWithIds = collect($data)->map(function ($category, $categoryId) {
            $category['categoryId'] = $categoryId;
            return $category;
        });

        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $perPage = 7;

        $currentPageItems = $categoriesWithIds->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $data = new LengthAwarePaginator(
            $currentPageItems,
            $categoriesWithIds->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('category', compact('data'));
    }


    public function destroy($categoryId)
    {
        try {
            $database = $this->firebaseService->getDatabase();

            $category = $database->getReference('categories/' . $categoryId)->getValue();

            if (isset($category['menuIds']) && !empty($category['menuIds'])) {
                return redirect()->route('categories.index')->with('error', 'Category cannot be deleted because it has associated menu items.');
            }

            $database->getReference('categories/' . $categoryId)->remove();

            return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Failed to delete category.');
        }
    }
}
