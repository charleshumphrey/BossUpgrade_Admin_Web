<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use Illuminate\Pagination\LengthAwarePaginator;
use Kreait\Firebase\Messaging\CloudMessage;

class FirebaseService
{
    protected $database;
    protected $storage;
    protected $messaging;

    public function __construct()
    {
        $firebase = (new Factory)->withServiceAccount(config('firebase.credentials'))
            ->withDatabaseUri('https://bossupgrade-101-default-rtdb.firebaseio.com');

        $this->database = $firebase->createDatabase();
        $this->storage = $firebase->createStorage();
        $this->messaging = $firebase->createMessaging();
    }

    public function getDatabase(): Database
    {
        return $this->database;
    }
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * 
     *
     * @param string $path
     * @param int $perPage
     * @param string|null $startAfter
     * @return array
     */
    public function getPaginatedData(string $path, int $perPage, string $startAfter = null): array
    {
        $query = $this->database->getReference($path)
            ->orderByKey()
            ->limitToFirst($perPage + 1);

        if ($startAfter) {
            $query = $query->startAfter($startAfter);
        }

        $snapshot = $query->getSnapshot();
        $data = $snapshot->getValue();

        $nextPageToken = null;
        if ($data && count($data) > $perPage) {

            $data = array_slice($data, 0, $perPage, true);
            $nextPageToken = array_key_last($data);
        }

        return [
            'data' => $data,
            'next_page_token' => $nextPageToken,
        ];
    }
    public function usernameExists($username)
    {
        $staffRef = $this->database->getReference('staff')
            ->orderByChild('username')
            ->equalTo($username)
            ->getSnapshot();

        return !empty($staffRef);
    }
    public function saveData($node, $data)
    {
        return $this->database->getReference($node)->push($data);
    }

    public function getOrdersWithMenuDetails($perPage = 10, $page = 1, $status)
    {
        $ordersRef = $this->database->getReference('orders');
        $menuRef = $this->database->getReference('menu');
        $usersRef = $this->database->getReference('users');
        $paymentsRef = $this->database->getReference('payments');

        $orders = $ordersRef->getValue() ?? [];
        $menuItems = $menuRef->getValue() ?? [];
        $users = $usersRef->getValue() ?? [];
        $payments = $paymentsRef->getValue() ?? [];

        $filteredOrders = [];

        foreach ($orders as $orderId => $order) {
            if ($order['status'] === $status) {

                $userId = $order['userId'];
                $username = isset($users[$userId]) ? $users[$userId]['username'] : 'Unknown User';
                $fullname = isset($users[$userId]) ? $users[$userId]['fullname'] : 'Unknown User';

                $payment = array_filter($payments, function ($payment) use ($orderId) {
                    return $payment['orderId'] === $orderId;
                });
                $payment = reset($payment);

                $orderDetails = [
                    'orderId' => $orderId,
                    'totalPrice' => $order['totalPrice'],
                    'orderDate' => $order['orderDate'],
                    'username' => $username,
                    'fullname' => $fullname,
                    'sitioStreet' => $order['address']['sitioStreet'],
                    'barangay' => $order['address']['barangay'],
                    'city' => $order['address']['city'],
                    'paymentMode' => $payment['paymentMode'] ?? 'Unknown',
                    'paymentStatus' => $payment['paymentStatus'] ?? 'Unknown',
                    'referenceNumber' => $payment['referenceNumber'] ?? null,
                    'items' => []
                ];

                foreach ($order['items'] as $menuId => $item) {
                    if (isset($menuItems[$menuId])) {
                        $menuItem = $menuItems[$menuId];
                        $orderDetails['items'][] = [
                            'name' => $menuItem['name'],
                            'price' => $item['price'],
                            'quantity' => $item['quantity'],
                            'feedback' => $item['feedback'],
                            'rating' => $item['rating'],
                            'imageUrl' => !empty($menuItem['imageUrl']) ? $menuItem['imageUrl'][0] : null
                        ];
                    }
                }

                $filteredOrders[] = $orderDetails;
            }
        }

        if (!empty($filteredOrders)) {
            usort($filteredOrders, function ($a, $b) {
                return strtotime($b['orderDate']) <=> strtotime($a['orderDate']);
            });
        }

        $currentPage = $page;
        $currentPageOrders = array_slice($filteredOrders, ($currentPage - 1) * $perPage, $perPage);
        $paginator = new LengthAwarePaginator($currentPageOrders, count($filteredOrders), $perPage, $currentPage, [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);

        return $paginator;
    }



    public function getOrderDetailsById($orderId)
    {
        $ordersRef = $this->database->getReference('orders/' . $orderId);
        $usersRef = $this->database->getReference('users');
        $paymentsRef = $this->database->getReference('payments');
        $menuRef = $this->database->getReference('menu');


        $orderSnapshot = $ordersRef->getValue();
        if (!$orderSnapshot) {
            return null;
        }


        $usersSnapshot = $usersRef->getValue();
        $paymentsSnapshot = $paymentsRef->getValue();
        $menuSnapshot = $menuRef->getValue();


        $user = $usersSnapshot[$orderSnapshot['userId']] ?? null;

        $paymentInfo = collect($paymentsSnapshot)->firstWhere('orderId', $orderId);
        $paymentMode = $paymentInfo['paymentMode'] ?? null;


        $itemsWithDetails = [];
        foreach ($orderSnapshot['items'] as $menuId => $item) {
            $menuItem = $menuSnapshot[$menuId] ?? null;
            $itemsWithDetails[] = [
                'menuId' => $menuId,
                'name' => $menuItem['name'] ?? 'Unknown Menu Item',
                'description' => $menuItem['description'] ?? '',
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'rating' => $item['rating'] ?? null,
                'feedback' => $item['feedback'] ?? null,
                'averageRating' => $menuItem['averageRating'] ?? null,
                'totalRatings' => $menuItem['totalRatings'] ?? 0,
                'imageUrl' => $menuItem['imageUrl'][0] ?? null,
            ];
        }


        $orderDetails = [
            'orderId' => $orderId,
            'totalPrice' => $orderSnapshot['totalPrice'],
            'status' => $orderSnapshot['status'],
            'orderDate' => $orderSnapshot['orderDate'],
            'sitioStreet' => $orderSnapshot['address']['sitioStreet'],
            'barangay' => $orderSnapshot['address']['barangay'],
            'city' => $orderSnapshot['address']['city'],
            'items' => $itemsWithDetails,
            'request' => $orderSnapshot['request'],
            'user' => [
                'username' => $user['username'] ?? 'Unknown User',
                'fullname' => $user['fullname'] ?? 'Unknown User',
                'profileImage' => $user['profileImage'] ?? 'null',
                'email' => $user['email'] ?? null,
                'phone' => $user['phone'] ?? null,
                'address' => $user['address'] ?? null,
            ],
            'payment' => [
                'totalAmount' => $paymentInfo['totalAmount'] ?? 0,
                'paymentMode' => $paymentInfo['paymentMode'] ?? 0,
                'paymentStatus' => $paymentInfo['paymentStatus'] ?? 'Unknown',
                'paymentDate' => $paymentInfo['paymentDate'] ?? null,
                'receiptImageUrl' => ($paymentMode === 'GCash') ? $paymentInfo['receiptImageUrl'] : 'null',
            ]
        ];

        return $orderDetails;
    }

    public function sendNotificationToUser($fcmToken, $notificationData)
    {
        // Create a test message to check the validity of the FCM token
        $testMessage = CloudMessage::withTarget('token', $fcmToken)
            ->withNotification([
                'title' => 'Test Notification',
                'body' => 'This is a test notification to validate the FCM token.',
                'sound' => 'default',
            ]);

        try {
            // Attempt to send the test message
            $this->messaging->send($testMessage);

            // If the test message is successfully sent, proceed with the actual notification
            $message = CloudMessage::withTarget('token', $fcmToken)
                ->withNotification([
                    'title' => $notificationData['title'],
                    'body' => $notificationData['body'],
                    'sound' => 'default',
                ])
                ->withData([
                    'orderId' => $notificationData['orderId'],
                ]);

            // Send the actual notification
            $this->messaging->send($message);
        } catch (\Kreait\Firebase\Exception\MessagingException $e) {
            // Check if the error is due to an invalid token
            if (
                $e->getCode() === 'messaging/invalid-registration-token' ||
                $e->getCode() === 'messaging/not-found'
            ) {
                // Remove the invalid token from your database
                $this->removeInvalidFcmToken($fcmToken);
            } else {
                throw new \Exception('FCM Send Error: ' . $e->getMessage());
            }
        }
    }

    private function removeInvalidFcmToken($fcmToken)
    {
        // Logic to remove the invalid token from your database
        $userId = $this->getUserIdFromToken($fcmToken); // Retrieve the user ID associated with the token
        if ($userId) {
            $userReference = $this->database->getReference("users/{$userId}");
            $userReference->update([
                'fcmToken' => null // Set fcmToken to null or remove it
            ]);
        }
    }

    private function getUserIdFromToken($fcmToken)
    {
        // Logic to retrieve the user ID associated with the FCM token from your database
        $users = $this->database->getReference('users')->getValue();

        foreach ($users as $userId => $user) {
            if (isset($user['fcmToken']) && $user['fcmToken'] === $fcmToken) {
                return $userId; // Return the user ID if the FCM token matches
            }
        }

        return null; // Return null if no user is found with the given token
    }


    public function getMenuItemById($menuId)
    {
        $menuItemRef = $this->database->getReference('menu/' . $menuId);

        $menuItem = $menuItemRef->getValue();

        if ($menuItem) {

            return $menuItem;
        }


        return null;
    }

    public function updateMenuItem($menuId, $data)
    {
        $menuItemRef = $this->database->getReference('menu/' . $menuId);

        $menuItemRef->update($data);
    }

    public function insertData($table, $data)
    {
        return $this->database->getReference($table)->push($data);
    }

    public function getData($table)
    {
        return $this->database->getReference($table)->getValue();
    }

    public function deleteData($table, $id)
    {
        return $this->database->getReference($table . '/' . $id)->remove();
    }

    public function getPromotions()
    {
        $promotionsRef = $this->database->getReference('promotions');
        $promotions = $promotionsRef->getValue();

        return $promotions;
    }
}
