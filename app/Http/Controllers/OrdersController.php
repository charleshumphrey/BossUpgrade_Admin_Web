<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService; // Assuming this is your Firebase Service
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class OrdersController extends Controller
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

        // Get all orders
        $ordersData = $database->getReference('orders')->getValue();
        $usersData = $database->getReference('users')->getValue();
        $menuData = $database->getReference('menu')->getValue();
        $paymentsData = $database->getReference('payments')->getValue();

        $orders = [];

        foreach ($ordersData as $orderId => $order) {
            $userId = $order['userId'];
            $user = isset($usersData[$userId]) ? $usersData[$userId] : null;
            $username = $user ? $user['username'] : 'Unknown User';

            $orderItems = [];
            $totalPrice = 0;

            foreach ($order['items'] as $menuId => $item) {
                $menuItem = isset($menuData[$menuId]) ? $menuData[$menuId] : null;
                if ($menuItem) {
                    $orderItems[] = [
                        'name' => $menuItem['name'],
                        'imageUrl' => $menuItem['imageUrl'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ];
                    $totalPrice += $item['price'] * $item['quantity'];
                }
            }

            // Get payment details for the order
            $payment = null;
            foreach ($paymentsData as $paymentId => $paymentData) {
                if ($paymentData['orderId'] === $orderId) {
                    $payment = $paymentData;
                    break;
                }
            }

            $orders[] = [
                'orderId' => $orderId,
                'username' => $username,
                'items' => $orderItems,
                'totalPrice' => $order['totalPrice'],
                'status' => $order['status'],
                'orderDate' => $order['orderDate'],
                'modeOfPayment' => $payment['modeOfPayment'] ?? 'Unknown',
                'receiptImageUrl' => $payment['receiptImageUrl'] ?? null,
                'paymentStatus' => $payment['paymentStatus'] ?? 'pending',
            ];
        }

        // Paginate orders
        $collection = collect($orders);
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 7;
        $currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $orders = new LengthAwarePaginator(
            $currentPageItems,
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('orders', compact('orders'));
    }
}
