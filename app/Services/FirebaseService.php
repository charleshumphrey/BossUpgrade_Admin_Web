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
        $base64 = "ewogICAgInR5cGUiOiAic2VydmljZV9hY2NvdW50IiwKICAgICJwcm9qZWN0X2lkIjogImJvc3N1cGdyYWRlLTEwMSIsCiAgICAicHJpdmF0ZV9rZXlfaWQiOiAiZDg2ZGRmYjc4NDk3YTdjMmJkZTEwMWMxODljZjlmNzQ2ZmMwZTdhMSIsCiAgICAicHJpdmF0ZV9rZXkiOiAiLS0tLS1CRUdJTiBQUklWQVRFIEtFWS0tLS0tXG5NSUlFdlFJQkFEQU5CZ2txaGtpRzl3MEJBUUVGQUFTQ0JLY3dnZ1NqQWdFQUFvSUJBUURNOVRZaUV4YzllNytXXG5tb3luVERQcG1PdFVBTGtqQXRqdHdHcTNIeE0yV3FsN3AyTldHeWMyL2xubTVyYS9Rc1M3TFV0S1R3Nk80QTI2XG5TQzUwVzFjYkw0YW5aUEZKWG5oZC9uTzdzc2w5enQ3YkszNFkzcHFCZzdDOXpsdkJwZCtCUkxQL05XSlJzMkNCXG5XczVSWDRGeC9rN2N1cVFCYnZadjk3Zk5zdDVVRTZMYlFaYi9sY28rc2ptanV2M0lEWWRvZUN4VmJyanpkWW4rXG5WM0hQVHRNL3BlRy9vOG80MVJ3cFVvaTA3aU01azFCQVN4WDF3ZmRuZlNFTk4wQ1hxSHJQR1hqU1BvZG1nZEw3XG5sMTRqblVzR2s0TGNyTnh2b0wvWkJqc3F0T2tPZWt2aWNpQjdJV0RhQytoNzVXcU5OL0ljV1p1dXlMUHVIOHhqXG5ZUmUzVzNPbEFnTUJBQUVDZ2dFQURLdW1GcEl1a0I3NzE2QWpUTjVob1RIU2JvV0M3SDZyRVh1aFBVUGZDSyt2XG5KWlZTQjBzemIrdG1mR2RMNmVRRFRIeisyQSt1R3pnUHNUSHdLVUpTVHJ4djZ4NUs2bG04UURkUVNVekN0eFBZXG5aQjA2dE9maURscXBUUHFhWW96U0ZlZzg1bXlTOFBFUmFMM0gzbkQwakNzSURnNDl3RmJ2UmptK3M2azRwR0xiXG44WGFkaTR0SDZiOXo5M1ZZWmVEM2dVcWRGbnNZRm5ucTBCMmhnTWQ3a3pYZURIQUFra2RsbjJwN0luVXFRUzd1XG5IbmxjWURDMmlTTDloSitoTTlaVG9wUXM5THErWDJ2NC93RTlRM3YzRVJZUDVhMktJWldHR0ZUalNtc2dpQUVmXG5LWktMSjhySk8weGtLclRYd0Zabk9YbTlSMEdIcDNGc21aVHV3TjI0YXdLQmdRRDZrNWIwU2J4aEhGTzZpaE5kXG4zaFpHWTdSNFZ0c2lpZnppbXIwb0xpTXNBQTU4MHZHYi9wdFk4RnFNUFpGWnVPUWlXODYySDgwZlY3YkdLVDVZXG5JQm9TV2ZlMjd2VUNSaDJFR3lrQis3Y3Nod3B2TEsvaW1RTnY3aEx6bzRLakozR0s4UUhzL3Nmd1BMaW9FQlRsXG5FZ1ptZk1yZ3FTdTdJRVNIcGJSVjdvWmthd0tCZ1FEUlpOcmd2OWY5MzVib2tHVGdralZYajFNczlYOXRQZGM1XG55a0N1Z3pCL0RMTEU2c3dXSXJqbVpjcitoaERqQWd3RFAybVYrN3gvdklabWlvM1pmbkVYWFJuem55RENIMlAvXG5VNHErenJKZHZwNTNjWmgxVkJmUnhtMzYxUHZ2bE5tN2Z5OGk0TW00Zy9LMGRQblpHTjJaSnlCYi9XRWoxZmlWXG5sbThZN200TUx3S0JnR3NYTXJlNjM3aTlLNGVZeFVnangyOVQ0MmhlN282blhKM1lOaWw2OW1zeXB2NXdCUFIvXG5ieE1tdmZ3YndBZ1JmZWExUi9ZTVIxVXkzcGw0UFAzYTBtbXZhN0FTRmtKZmh2UFR5OFhlTjEzZlpQVTRxdjcwXG5HRVQ1QWI2QXppOCtoMGN6VTNoZk4wSVVJWE1xVkZQR1RWZjEwNXNLa2ZjS2hsOVcwV2k4WlZGN0FvR0JBTGpwXG4yc3pOUGgzcEhPaGwwMS9xWGdia0FDemM0OW9CMlpZa1FQaWJ5L0Qyc3N4cEdTK1hIRGdDdnNBRWZ0Y3AwSmZ4XG5vUG1kWkhwZGIwY1FwbkhpVWxheWxoOXBjRkZkelQ0R2RhbEkrWVVGdUFWOUlJakVhWUNNUGtWYlh0elZVMjViXG53N0dHVnoyRjhJaGlJTjRTTW1kMGdRUWJmM1JaR2Z4bjY5WlZnSU5yQW9HQUwvaURBdXpBb2VKVytRZGxKZ2RhXG5zSWZMYzVaNFNCQjlxOUdWNXBEbUdnRWlHZ3llek5lUG9aQ2cvSEh5K2FUUkdDd25UYzZUVDcyWFFvTWhLVFFTXG5RVEdya2NxWnZ4ZDYxdWRpU0QzeDFSaitiZFFOcm5KZW1XQ3UwMjJidFFZQitWU2JXNm5zc2djR3M4S0VkYkJrXG42WkN4UldnZHBmbDlCS3laUWVINE5sND1cbi0tLS0tRU5EIFBSSVZBVEUgS0VZLS0tLS1cbiIsCiAgICAiY2xpZW50X2VtYWlsIjogImZpcmViYXNlLWFkbWluc2RrLWVqbDQyQGJvc3N1cGdyYWRlLTEwMS5pYW0uZ3NlcnZpY2VhY2NvdW50LmNvbSIsCiAgICAiY2xpZW50X2lkIjogIjExMDAwMTg2NTg3MDAwNTY0MDE1NCIsCiAgICAiYXV0aF91cmkiOiAiaHR0cHM6Ly9hY2NvdW50cy5nb29nbGUuY29tL28vb2F1dGgyL2F1dGgiLAogICAgInRva2VuX3VyaSI6ICJodHRwczovL29hdXRoMi5nb29nbGVhcGlzLmNvbS90b2tlbiIsCiAgICAiYXV0aF9wcm92aWRlcl94NTA5X2NlcnRfdXJsIjogImh0dHBzOi8vd3d3Lmdvb2dsZWFwaXMuY29tL29hdXRoMi92MS9jZXJ0cyIsCiAgICAiY2xpZW50X3g1MDlfY2VydF91cmwiOiAiaHR0cHM6Ly93d3cuZ29vZ2xlYXBpcy5jb20vcm9ib3QvdjEvbWV0YWRhdGEveDUwOS9maXJlYmFzZS1hZG1pbnNkay1lamw0MiU0MGJvc3N1cGdyYWRlLTEwMS5pYW0uZ3NlcnZpY2VhY2NvdW50LmNvbSIsCiAgICAidW5pdmVyc2VfZG9tYWluIjogImdvb2dsZWFwaXMuY29tIgp9Cg==";
        $databaseUrl = config('firebase.database_url');

        if (!$base64) {
            throw new \Exception("Missing Firebase credentials");
        }

        // Decode base64 string
        $decodedJson = base64_decode($base64);

        // Convert JSON to array
        $credentialsArray = json_decode($decodedJson, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Invalid Firebase credentials JSON");
        }

        // Initialize Firebase using array credentials
        $firebase = (new Factory)
            ->withServiceAccount($credentialsArray)
            ->withDatabaseUri($databaseUrl);

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

                // Determine the order status
                $orderStatus = $order['status'];
                if (isset($payment['paymentMode']) && $payment['paymentMode'] === 'GCash' && $orderStatus === 'cancelled') {
                    $orderStatus = 'For Refund';
                }

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
                    'status' => $orderStatus, // Set the modified order status
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


        $orderStatus = $orderSnapshot['status'] ?? 'Unknown';
        $statusDisplay = ($orderStatus === 'cancelled' && $paymentMode === 'GCash') ? 'For Refund' : $orderStatus;


        $orderDetails = [
            'orderId' => $orderId,
            'totalPrice' => $orderSnapshot['totalPrice'] ?? 0,
            'status' => $statusDisplay,
            'orderDate' => $orderSnapshot['orderDate'] ?? 'N/A',
            'sitioStreet' => $orderSnapshot['address']['sitioStreet'] ?? 'N/A',
            'barangay' => $orderSnapshot['address']['barangay'] ?? 'N/A',
            'city' => $orderSnapshot['address']['city'] ?? 'N/A',
            'items' => $itemsWithDetails,
            'request' => $orderSnapshot['request'] ?? '',
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
                'paymentMode' => $paymentMode ?? 'Unknown',
                'paymentStatus' => $paymentInfo['paymentStatus'] ?? 'Unknown',
                'referenceNo' => $paymentInfo['referenceNumber'] ?? 'null',
                'paymentDate' => $paymentInfo['paymentDate'] ?? 'N/A',
                'receiptImageUrl' => ($paymentMode === 'GCash') ? ($paymentInfo['receiptImageUrl'] ?? 'null') : 'null',
            ]
        ];

        return $orderDetails;
    }


    public function sendNotificationToUser($fcmToken, $notificationData)
    {

        // $testMessage = CloudMessage::withTarget('token', $fcmToken)
        //     ->withNotification([
        //         'title' => 'Test Notification',
        //         'body' => 'This is a test notification to validate the FCM token.',
        //         'sound' => 'default',
        //     ]);

        try {

            // $this->messaging->send($testMessage);


            $message = CloudMessage::withTarget('token', $fcmToken)
                ->withNotification([
                    'title' => $notificationData['title'],
                    'body' => $notificationData['body'],
                    'sound' => 'default',
                ])
                ->withData([
                    'orderId' => $notificationData['orderId'],
                ]);


            $this->messaging->send($message);
        } catch (\Kreait\Firebase\Exception\MessagingException $e) {

            if (
                $e->getCode() === 'messaging/invalid-registration-token' ||
                $e->getCode() === 'messaging/not-found'
            ) {

                $this->removeInvalidFcmToken($fcmToken);
            } else {
                throw new \Exception('FCM Send Error: ' . $e->getMessage());
            }
        }
    }

    private function removeInvalidFcmToken($fcmToken)
    {

        $userId = $this->getUserIdFromToken($fcmToken);
        if ($userId) {
            $userReference = $this->database->getReference("users/{$userId}");
            $userReference->update([
                'fcmToken' => null
            ]);
        }
    }

    private function getUserIdFromToken($fcmToken)
    {

        $users = $this->database->getReference('users')->getValue();

        foreach ($users as $userId => $user) {
            if (isset($user['fcmToken']) && $user['fcmToken'] === $fcmToken) {
                return $userId;
            }
        }

        return null;
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
