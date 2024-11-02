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

    public function showPendingOrders(Request $request)
    {
        $currentPage = $request->get('page', 1);
        $pendingOrders = $this->firebaseService->getOrdersWithMenuDetails(10, $currentPage, "pending");

        return view('orders_pending', ['pendingOrders' => $pendingOrders]);
    }

    public function showConfirmedOrders(Request $request)
    {
        $currentPage = $request->get('page', 1);
        $pendingOrders = $this->firebaseService->getOrdersWithMenuDetails(10, $currentPage, "confirmed");

        return view('orders_confirmed', ['pendingOrders' => $pendingOrders]);
    }

    public function showPreparingOrders(Request $request)
    {
        $currentPage = $request->get('page', 1);
        $pendingOrders = $this->firebaseService->getOrdersWithMenuDetails(10, $currentPage, "on_preparation");

        return view('orders_on_preparation', ['pendingOrders' => $pendingOrders]);
    }
    public function showforDeliveryOrders(Request $request)
    {
        $currentPage = $request->get('page', 1);
        $fordeliveryOrders = $this->firebaseService->getOrdersWithMenuDetails(10, $currentPage, "out_for_delivery");

        return view('orders_for_delivery', ['orders' => $fordeliveryOrders]);
    }
    public function showDeliveredOrders(Request $request)
    {
        $currentPage = $request->get('page', 1);
        $pendingOrders = $this->firebaseService->getOrdersWithMenuDetails(10, $currentPage, "delivered");

        return view('orders_pending', ['pendingOrders' => $pendingOrders]);
    }
    public function confirmOrder(Request $request, $orderId)
    {
        $ordersRef = $this->firebaseService->getDatabase()->getReference('orders/' . $orderId);
        $orderDetails = $ordersRef->getValue();

        if ($orderDetails) {
            $ordersRef->update(['status' => 'confirmed']);

            $userId = $orderDetails['userId'];
            $userRef = $this->firebaseService->getDatabase()->getReference('users/' . $userId);
            $userData = $userRef->getValue();

            if ($userData) {
                $fcmToken = $userData['fcmToken'];

                $notificationData = [
                    'title' => 'Order Confirmed',
                    'body' => 'Your order #' . $orderId . ' has been confirmed!',
                    'orderId' => $orderId,
                ];

                $this->firebaseService->sendNotificationToUser($fcmToken, $notificationData);
            }

            return redirect()->route('pending_orders.paginated')->with('success', 'Order confirmed successfully!');
        }

        return redirect()->route('pending_orders.paginated')->with('error', 'Order not found!');
    }


    public function prepareOrder(Request $request, $orderId)
    {
        $ordersRef = $this->firebaseService->getDatabase()->getReference('orders/' . $orderId);
        $orderDetails = $ordersRef->getValue();

        if ($orderDetails) {
            $ordersRef->update(['status' => 'on_preparation']);

            $userId = $orderDetails['userId'];
            $userRef = $this->firebaseService->getDatabase()->getReference('users/' . $userId);
            $userData = $userRef->getValue();

            if ($userData) {
                $fcmToken = $userData['fcmToken'];

                $notificationData = [
                    'title' => 'Order Preparation',
                    'body' => 'Your order #' . $orderId . ' is now being prepared!',
                    'orderId' => $orderId,
                ];

                $this->firebaseService->sendNotificationToUser($fcmToken, $notificationData);
            }

            return redirect()->route('confirmed_orders.paginated')->with('success', 'Order is now in preparation!');
        }

        return redirect()->route('confirmed_orders.paginated')->with('error', 'Order not found!');
    }

    public function forDeliveryOrder(Request $request, $orderId)
    {
        $ordersRef = $this->firebaseService->getDatabase()->getReference('orders/' . $orderId);
        $orderDetails = $ordersRef->getValue();

        if ($orderDetails) {
            $ordersRef->update(['status' => 'out_for_delivery']);

            $userId = $orderDetails['userId'];
            $userRef = $this->firebaseService->getDatabase()->getReference('users/' . $userId);
            $userData = $userRef->getValue();


            $totalPrice = isset($orderDetails['totalPrice']) ? $orderDetails['totalPrice'] : 0.00;


            $formattedTotalPrice = '₱' . number_format($totalPrice, 2);

            if ($userData) {
                $fcmToken = $userData['fcmToken'];

                $notificationData = [
                    'title' => 'Order Out for Delivery',
                    'body' => 'Your order #' . $orderId . ' is now out for delivery! Please prepare a amount of ' . $formattedTotalPrice .
                        ' plus an extra for delivery fee' .
                        '. Note that delivery fees may vary based on your location.',
                    'orderId' => $orderId,
                ];

                $this->firebaseService->sendNotificationToUser($fcmToken, $notificationData);
            }

            return redirect()->route('on_preparation_orders.paginated')->with('success', 'Order is out for delivery!');
        }

        return redirect()->route('on_preparation_orders.paginated')->with('error', 'Order not found!');
    }


    public function deliveredOrder(Request $request, $orderId)
    {

        $ordersRef = $this->firebaseService->getDatabase()->getReference('orders/' . $orderId);
        $orderDetails = $ordersRef->getValue();

        if ($orderDetails) {

            $ordersRef->update(['status' => 'delivered']);

            $items = $orderDetails['items'];

            foreach ($items as $menuId => $itemDetails) {
                $quantity = $itemDetails['quantity'];

                $menuRef = $this->firebaseService->getDatabase()->getReference('menu/' . $menuId);
                $menuItem = $menuRef->getValue();

                if ($menuItem) {
                    $newSoldCount = isset($menuItem['soldCount']) ? $menuItem['soldCount'] + $quantity : $quantity;

                    $menuRef->update(['soldCount' => $newSoldCount]);
                }
            }

            $userId = $orderDetails['userId'];
            $userRef = $this->firebaseService->getDatabase()->getReference('users/' . $userId);
            $userData = $userRef->getValue();

            if ($userData) {
                $fcmToken = $userData['fcmToken'];

                $notificationData = [
                    'title' => 'Order Delivered',
                    'body' => 'Your order #' . $orderId . ' has been delivered successfully!',
                    'orderId' => $orderId,
                ];

                $this->firebaseService->sendNotificationToUser($fcmToken, $notificationData);
            }

            return redirect()->route('for_delivery_orders.paginated')->with('success', 'Order delivered successfully!');
        }

        return redirect()->route('for_delivery_orders.paginated')->with('error', 'Order not found!');
    }



    public function showOrderDetails($orderId)
    {

        $orderDetails = $this->firebaseService->getOrderDetailsById($orderId);

        if (!$orderDetails) {
            return redirect()->back()->with('error', 'Order not found');
        }

        return view('view_order_details', compact('orderDetails'));
    }
}
