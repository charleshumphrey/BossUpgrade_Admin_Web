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
                <li>Confirmed Orders</li>
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
                        @forelse($pendingOrders as $order)
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
                                <form action="{{ route('orders.delivered', $order['orderId']) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Delivered</button>
                                </form>
                                <a class="button bg-blue-500 text-white px-4 py-2 rounded" href="{{ route('order_details.show', $order['orderId']) }}">
                                    View Details
                                </a>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4  text-center">No pending orders available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="table-pagination">
                    @if (count($pendingOrders) > 0)
                    <div class="w-full mt-5 ">{{ $pendingOrders->links('vendor.pagination.tailwind') }}</div>
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

</body>

</html>