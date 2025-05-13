<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <style>
        @media print {

            /* Ensure only receipt content prints */
            body * {
                visibility: hidden;
            }

            #receipt-content {
                visibility: visible;
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    @include('shared.upper_nav')

    <div class="aside is-placed-left is-expanded bg-black-erie">
        <div class="aside-tools bg-black">
            <div>
                <a href="{{ route('dashboard') }}" class="flex justify-center items-center p-2 h-16">
                    <img class="w-12" src={{ asset('build/assets/images/BossUpgrade_logo.jpg') }} alt="bossupgrade_logo">
                    <p class="appname font-bungee text-white font-poppins_bold font-bol">{{ config('app.name') }}</p>
                </a>
            </div>
        </div>
        @include('shared.nav_sidebar')
    </div>

    <section class="is-title-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
            <ul>
                <li>For Delivery Orders</li>
            </ul>

        </div>
    </section>

    <section class="section main-section">

        @include('shared.success')

        <div class="card has-table">
            <header class="card-header">
                <p class="card-header-title">
                    <span class="icon"><i class="fa-solid fa-burger"></i></span>
                    Orders
                </p>
                <a href="{{ route('confirmed_orders.paginated') }}" class="card-header-icon bg-blue-200 rounded-sm">
                    <span class="icon"><i class="mdi mdi-reload"></i></span>
                </a>
            </header>
            <div class="card-content">
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th class="border border-gray-200">Username</th>
                            <th class="border border-gray-200">Total Price</th>
                            <th class="border border-gray-200">Order Date</th>
                            <th class="border border-gray-200">Items</th>
                            <th class="border border-gray-200">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr class="hover:cursor-pointer">
                            <td class="border border-gray-200 p-3 text-center">{{ $loop->iteration }}</td>
                            <td class="border border-gray-200">{{ $order['username'] }}</td>
                            <td class="border border-gray-200">{{ number_format($order['totalPrice'], 2) }}</td>
                            <td class="border border-gray-200"><small>{{ $order['orderDate'] }}</small></td>
                            <td class="border border-gray-200">
                                <ul>
                                    @foreach($order['items'] as $item)
                                    <li>
                                        <img src="{{ $item['imageUrl'] }}" alt="{{ $item['name'] }}" width="50" />
                                        <strong>{{ $item['name'] }}</strong> - {{ number_format($item['price'], 2) }} (Quantity: {{ $item['quantity'] }})
                                    </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="border border-gray-200">
                                <button type="submit" class="button bg-green-500 text-white px-4 py-2 rounded --jb-modal" data-target="confirm-modal{{$order['orderId']}}">Delivered</button>
                                <a class="button bg-blue-500 text-white px-4 py-2 rounded" href="{{ route('order_details.show', $order['orderId']) }}">
                                    View Details
                                </a>

                                <button onclick="printReceipt(`{{ $order['orderId'] }}`)" class="bg-green-500 text-white px-4 py-2 rounded">Print Receipt</button>

                                <div id="receipt-content-{{ $order['orderId'] }}" style="display: none;" class="mx-auto">
                                    <div class="p-4">

                                        <div class="text-center border-b border-gray-400 pb-2 mb-4">
                                            <h1 class="text-2xl font-bold">BossUpgrade Resto Bar</h1>
                                            <p class="text-sm">Poblacion, Bansud, Philippines</p>
                                            <p class="text-sm">Contact: 09214841286</p>
                                        </div>

                                        <h2 class="text-lg font-semibold text-center mb-4">Receipt</h2>

                                        <div class="mb-4 text-center">
                                            <p><strong>Order ID:</strong> {{ $order['orderId'] }}</p>
                                            <p><strong>Full Name:</strong> {{ $order['fullname'] }}</p>
                                            <p><strong>Address:</strong> {{ $order['sitioStreet'] }}, {{ $order['barangay'] }}, {{ $order['city'] }}, Oriental Mindoro</p>
                                            <p><strong>Payment Mode:</strong> {{ $order['paymentMode'] }}</p>
                                        </div>

                                        <div class="border-t border-gray-300 pt-4 mb-4 text-center">
                                            <h3 class="text-lg font-semibold">Order Details</h3>
                                            <p><strong>Order Date:</strong> {{ $order['orderDate'] }}</p>
                                            <ul class="mt-2 space-y-1">
                                                @foreach ($order['items'] as $item)
                                                <li class="flex justify-between items-center border-none">
                                                    <span class="flex-1 truncate">{{ $item['name'] }}</span>
                                                    <span class="mx-2">..............................</span>
                                                    <span>(x{{ $item['quantity'] }})</span>
                                                    <span>₱ {{ number_format($item['price'], 2) }}</span>
                                                </li>
                                                <hr class="border-dotted border-gray-300 my-2">
                                                @endforeach
                                            </ul>
                                            <p class="mt-4 font-semibold text-right">Total Price: ₱{{ number_format($order['totalPrice'], 2) }}</p>
                                        </div>

                                        <div class="text-center border-t border-gray-300 pt-4">
                                            <p>Thank you for ordering with us!</p>
                                            <p class="italic text-sm">We hope to see you again!</p>
                                        </div>
                                    </div>
                                </div>

                            </td>
                        </tr>

                        <div id="confirm-modal{{ $order['orderId'] }}" class="modal hidden">
                            <div class="modal-background --jb-modal-close"></div>
                            <div class="modal-card">
                                <header class="modal-card-head">
                                    <i class="mdi mdi-check-circle-outline text-green-500 mr-4"></i>
                                    <p class="modal-card-title text-black">Confirm Delivery for <span class="text-green-500">Order</span></p>
                                </header>
                                <section class="modal-card-body text-gray-700">
                                    <p>Are you sure you want to mark this order as <span class="text-green-500">delivered</span>?</p>
                                </section>
                                <footer class="modal-card-foot justify-end">
                                    <button id="cancel-archive" class="button --jb-modal-close">Cancel</button>
                                    <form action="{{ route('orders.delivered', $order['orderId']) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" id="confirm-archive" class="button green archive-confirm">Confirm</button>
                                    </form>
                                </footer>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4  text-center">No pending orders available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="table-pagination">
                    @if (count($orders) > 0)
                    <div class="w-full mt-5 ">{{ $orders->links('vendor.pagination.tailwind') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </section>


    <div id="sample-modal" class="modal">
        <div class="modal-background --jb-modal-close"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title text-black">Sample modal</p>
            </header>
            <section class="modal-card-body text-gray-700">
                <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
                <p>This is sample modal</p>
            </section>
            <footer class="modal-card-foot justify-end">
                <button class="button --jb-modal-close">Cancel</button>
                <button class="button red --jb-modal-close">Confirm</button>
            </footer>
        </div>
    </div>

    <div id="sample-modal-2" class="modal">
        <div class="modal-background --jb-modal-close"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title text-black">Sample modal</p>
            </header>
            <section class="modal-card-body text-gray-700">
                <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
                <p>This is sample modal</p>
            </section>
            <footer class="modal-card-foot justify-end">
                <button class="button --jb-modal-close">Cancel</button>
                <button class="button blue --jb-modal-close">Confirm</button>
            </footer>
        </div>
    </div>

    </div>
    <!-- Icons below are for demo only. Feel free to use any icon pack. Docs: https://bulma.io/documentation/elements/icon/ -->
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">

    <script>
        function printReceipt(orderId) {
            const receiptContent = document.getElementById(
                `receipt-content-${orderId}`
            ).innerHTML;
            const printWindow = window.open("", "_blank");
            printWindow.document.open();
            printWindow.document.write(`
                <html>
                <head>
                    <title>Order Receipt</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        h1 { text-align: center; font-size: 24px; }
                        p, h3 { margin: 5px 0; }
                        hr {margin: 0.5rem 0; }
                        ul { padding: 0; list-style-type: none; }
                    </style>
                </head>
                <body>${receiptContent}</body>
                </html>
            `);
            printWindow.document.close();
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 100);
        }
    </script>
</body>

</html>