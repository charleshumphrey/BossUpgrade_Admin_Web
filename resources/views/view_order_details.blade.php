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

        <div class="bg-white p-5 rounded shadow-md">
            <h2 class="text-xl font-semibold">Order ID: {{ $orderDetails['orderId'] }}</h2>
            <p><strong>Total Price:</strong> ₱{{ number_format($orderDetails['totalPrice'], 2) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($orderDetails['status']) }}</p>
            <p><strong>Order Date:</strong> {{ \Carbon\Carbon::parse($orderDetails['orderDate'])->format('F d, Y H:i') }}</p>

            <h3 class="mt-4 text-lg font-semibold font-poppins_bold bg-gray-100 border-t">Items</h3>
            <ul class="bg-gray-50">
                @foreach ($orderDetails['items'] as $item)
                <li class="flex justify-between border-b py-2">
                    <div class="flex gap-2 justify-center px-3">
                        <img src="{{  asset($item['imageUrl']) }}" alt="Menu Item Image" class="w-10 h-10 rounded-sm">
                        <strong>{{ $item['name'] }}</strong>
                        <p class="text-gray-600">₱{{ number_format($item['price']   , 2) }}</p>
                        (x{{ $item['quantity'] }})
                        <p class="text-gray-600">{{ $item['description'] }}</p>
                    </div>
                    <span class="mr-3">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                </li>
                @endforeach

            </ul>

            <div clas="flex flex-col">
                <h3 class="font-semibold">Request</h3>
                <textarea name="" id="" readonly class="resize-none border-1 p-2">
                {{ $orderDetails['request'] }}
                </textarea>
            </div>

            <div class="py-3 mt-3">
                <h3 class=" text-lg font-semibold font-poppins_bold bg-gray-100 border-t">Payment Information</h3>
                <div class="bg-gray-50 border-b px-3 py-2">
                    <p><strong>Total Amount:</strong> ₱{{ number_format($orderDetails['payment']['totalAmount'], 2) }}</p>
                    <p><strong>Paymen Method:</strong> {{ ucfirst($orderDetails['payment']['paymentMode']) }}</p>
                    @if ($orderDetails['payment']['receiptImageUrl'] !== 'null')
                    <p><strong>Receipt:</strong></p>
                    <img src="{{ asset($orderDetails['payment']['receiptImageUrl']) }}" alt="Receipt Image" class="mt-2" width="200">
                    @else
                    <p><strong>Receipt:</strong> No receipt available for Cash on Delivery.</p>
                    @endif
                </div>

            </div>

            <hr>

            <div class="py-3">
                <h3 class="text-lg font-semibold font-poppins_bold">User Information</h3>
                <div class="flex items-center mb-4">
                    @if ($orderDetails['user']['profileImage'] != 'null')
                    <img src="{{ asset($orderDetails['user']['profileImage']) }}" alt="Profile Image" class="w-12 h-12 rounded-full mr-3 ">
                    @else
                    <img src="{{ asset('build/assets/images/avatar-default-symbolic-svgrepo-com.svg') }}" alt="Profile Image" class="w-12 h-12 rounded-full mr-3 bg-gray-100">
                    @endif

                    <div>
                        <strong>{{ $orderDetails['user']['username'] }}</strong>
                        <p><strong>Email: </strong> {{ $orderDetails['user']['email'] }}</p>
                        <p><strong>Phone:</strong> {{ $orderDetails['user']['phone'] }}</p>
                        <p><strong>Address: </strong> {{ $orderDetails['sitioStreet'] }}, {{ $orderDetails['barangay'] }}, {{ $orderDetails['city'] }}, Oriental Mindoro, Philippines</p>
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