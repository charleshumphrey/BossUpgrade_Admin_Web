<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
                <li>Feedbacks</li>
            </ul>

        </div>
    </section>

    <section class="section main-section">
        @include('shared.success')
        @include('shared.error')

        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    <span class="icon"><i class="fa-solid fa-comment-dots"></i></span>
                    Feedbacks
                </p>
            </header>
            <div class="card-content p-4">
                @if ($orders->count() > 0) <!-- Check if there are any rated orders -->
                @foreach ($orders as $orderId => $order)
                @foreach ($order['items'] as $menuId => $item)
                @php
                $userId = $order['userId'];
                $user = $users[$userId] ?? null;
                $menuItem = $menu[$menuId] ?? null;
                @endphp

                @if ($user && $menuItem)
                <div class="feedback-item bg-white shadow-md rounded-lg p-4 mb-4 transition-transform transform hover:scale-105">
                    <div class="flex items-start">
                        <img src="{{ isset($user['profileImage']) ? $user['profileImage'] : asset('images/default_profile_image.jpg') }}"
                            alt="{{ $user['username'] }}'s profile image"
                            class="w-12 h-12 rounded-full mr-4">
                        <div class="flex-1">
                            <h5 class="font-bold text-lg text-gray-800">{{ $user['username'] }}</h5>
                            <p class="text-gray-700 mb-2">{{ $item['feedback'] }}</p>
                            <div class="flex items-center mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="text-yellow-500">
                                    <i class="fa{{ $i <= $item['rating'] ? ' fa-star' : ' fa-star-o' }}"></i>
                                    </span>
                                    @endfor
                            </div>
                            <p class="text-sm text-gray-500 italic">{{ $menuItem['name'] }}</p>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
                @endforeach
                @else
                <div class="bg-white shadow-md rounded-lg p-4 mb-4 text-center">
                    <p class="text-gray-600">No feedback available. Please check back later!</p>
                </div>
                @endif

                <div class="table-pagination">
                    @if ($orders->count() > 0)
                    <div class="w-full mt-5">{{ $orders->links('vendor.pagination.tailwind') }}</div>
                    @endif
                </div>
            </div>

        </div>


    </section>
    </div>
    <!-- Icons below are for demo only. Feel free to use any icon pack. Docs: https://bulma.io/documentation/elements/icon/ -->
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">

</body>

</html>