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
                <li>Order Details</li>
            </ul>

        </div>
    </section>

    <section class="section main-section">

        @include('shared.success')

        <div class="bg-white p-8 rounded-lg shadow-xl transition-shadow hover:shadow-2xl duration-300 ease-in-out">
            <h2 class="text-2xl font-bold text-gray-800 border-b pb-2">Order ID: {{ $orderDetails['orderId'] }}</h2>
            <p class="mt-2 text-gray-700"><strong>Total Price:</strong> ₱{{ number_format($orderDetails['totalPrice'], 2) }}</p>
            <p class="text-gray-700"><strong>Status:</strong> <span class="font-semibold text-green-600">{{ ucfirst($orderDetails['status']) }}</span></p>
            <p class="text-gray-700"><strong>Order Date:</strong> {{ \Carbon\Carbon::parse($orderDetails['orderDate'])->format('F d, Y H:i') }}</p>

            <h3 class="mt-6 text-lg font-semibold text-gray-900 bg-gray-100 border-t py-2">Items</h3>
            <ul class="bg-gray-50 divide-y divide-gray-200 rounded-md shadow-sm">
                @foreach ($orderDetails['items'] as $item)
                <li class="flex justify-between items-center py-4 px-2 hover:bg-gray-100 transition duration-200">
                    <div class="flex gap-3 items-center">
                        <img src="{{  asset($item['imageUrl']) }}" alt="Menu Item Image" class="w-16 h-16 rounded-md shadow-lg">
                        <div>
                            <strong class="text-gray-800">{{ $item['name'] }}</strong>
                            <p class="text-gray-600">₱{{ number_format($item['price'], 2) }}</p>
                            <p class="text-gray-500">Quantity: (x{{ $item['quantity'] }})</p>
                        </div>
                    </div>
                    <span class="text-gray-800 font-semibold">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                </li>
                @endforeach
            </ul>

            <div class="mt-4">
                <h3 class="font-semibold text-gray-900">Request</h3>
                <textarea readonly class="resize-none border border-gray-300 rounded-md p-3 mt-1 w-full h-24 bg-gray-50 text-gray-800" rows="3">{{ $orderDetails['request'] }}</textarea>
            </div>

            <div class="py-4 mt-4">
                <h3 class="text-lg font-semibold text-gray-900 bg-gray-100 border-t py-2">Payment Information</h3>
                <div class="bg-gray-50 border-b px-4 py-3 rounded-md shadow-sm">
                    <p class="text-gray-700"><strong>Total Amount:</strong> ₱{{ number_format($orderDetails['payment']['totalAmount'], 2) }}</p>
                    <p class="text-gray-700"><strong>Payment Method:</strong> <span class="font-semibold text-blue-600">{{ ucfirst($orderDetails['payment']['paymentMode']) }}</span></p>
                    @if ($orderDetails['payment']['receiptImageUrl'] !== 'null')
                    <p class="text-gray-700"><strong>Receipt:</strong></p>
                    <img src="{{ asset($orderDetails['payment']['receiptImageUrl']) }}" alt="Receipt Image" class="mt-2 rounded-md border border-gray-300" width="500">
                    @else
                    <p class="text-gray-700"><strong>Receipt:</strong> No receipt available for Cash on Delivery.</p>
                    @endif
                    @if ($orderDetails['payment']['referenceNo'] !== 'null')
                    <p class="text-gray-700"><strong>Reference No.: </strong>{{$orderDetails['payment']['referenceNo']}}</p>
                    @else
                    <p class="text-gray-700"><strong>Reference No.:</strong> No reference number available for Cash on Delivery.</p>
                    @endif
                </div>
            </div>

            <hr class="my-4 border-gray-300">

            <div class="py-4">
                <h3 class="text-lg font-semibold text-gray-900">User Information</h3>
                <div class="flex items-center mb-4">
                    @if ($orderDetails['user']['profileImage'] != 'null')
                    <img src="{{ asset($orderDetails['user']['profileImage']) }}" alt="Profile Image" class="w-12 h-12 rounded-full border border-gray-300 shadow-md mr-3">
                    @else
                    <img src="{{ asset('build/assets/images/avatar-default-symbolic-svgrepo-com.svg') }}" alt="Profile Image" class="w-12 h-12 rounded-full border border-gray-300 bg-gray-100 shadow-md mr-3">
                    @endif

                    <div>
                        <strong class="text-gray-800">{{ $orderDetails['user']['username'] }}</strong>
                        <p class="text-gray-700"><strong>Email:</strong> {{ $orderDetails['user']['email'] }}</p>
                        <p class="text-gray-700"><strong>Phone:</strong> {{ $orderDetails['user']['phone'] }}</p>
                        <p class="text-gray-700"><strong>Address:</strong> {{ $orderDetails['sitioStreet'] }}, {{ $orderDetails['barangay'] }}, {{ $orderDetails['city'] }}, Oriental Mindoro, Philippines</p>
                    </div>
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