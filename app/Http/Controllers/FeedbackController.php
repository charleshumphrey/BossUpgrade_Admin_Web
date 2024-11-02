<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Kreait\Firebase\Contract\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    protected $firebaseService;
    protected $firebaseStorage;

    public function __construct(FirebaseService $firebaseService, Storage $firebaseStorage)
    {
        $this->firebaseService = $firebaseService;
        $this->firebaseStorage = $firebaseStorage;
    }
    public function showFeedbacks(Request $request)
    {
        $database = $this->firebaseService->getDatabase();

        // Retrieve users, orders, and menu data from Firebase
        $users = $database->getReference('users')->getValue();
        $orders = $database->getReference('orders')->getValue();
        $menu = $database->getReference('menu')->getValue();

        // Filter the orders to find those that have been rated
        $ratedOrders = [];
        foreach ($orders as $orderId => $order) {
            if ($order['status'] === 'rated') {
                $ratedOrders[$orderId] = $order;
            }
        }

        // Paginate the rated orders
        $collection = collect($ratedOrders);
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 4;
        $currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $paginatedOrders = new LengthAwarePaginator(
            $currentPageItems,
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Pass the required data to the view
        return view('feedback', [
            'users' => $users,
            'orders' => $paginatedOrders,
            'menu' => $menu,
        ]);
    }
}
