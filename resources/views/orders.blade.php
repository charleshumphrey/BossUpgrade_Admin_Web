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
                <a href="{{ route('dashboard') }}" class="flex justify-center orders-center p-2 h-16">
                    <img class="w-12" src={{ asset('build/assets/images/BossUpgrade_logo.jpg') }} alt="bossupgrade_logo">
                    <p class="appname font-bungee text-white font-poppins_bold font-bol">{{ config('app.name') }}</p>
                </a>
            </div>
        </div>
        @include('shared.nav_sidebar')
    </div>

    <section class="is-title-bar">
        <div class="flex flex-col md:flex-row orders-center justify-between space-y-6 md:space-y-0">
            <ul>
                <li>Admin</li>
                <li>Menu orders</li>
            </ul>

        </div>
    </section>

    <section class="section main-section">

        @include('shared.success')

        <div class="w-full flex justify-end py-2 shadow-sm bg-white rounded-sm px-3 mb-2 justify-between">
            <a class="button bg-accent-color" href="{{ route('add-menu-orders') }}">Add New</a>
            <div class="flex gap-1">
                <p class="content-center font-poppins_regular font-semibold">Select By</p>
                <form method="GET" action="{{ url()->current() }}">
                    <select id="category" name="category" onchange="this.form.submit()" class="border border-gray-300 p-2 rounded-md cursor-pointer bg-gray-100">
                        <option value="">All</option>
                        @if (count($categories) > 0)
                        @foreach ($categories as $category)
                        <option value="{{ $category['id'] }}" {{ $selectedCategory == $category['id'] ? 'selected' : '' }}>
                            {{ $category['name'] }}
                        </option>
                        @endforeach
                        @else
                        <option>No categories available</option>
                        @endif
                    </select>
                </form>
            </div>
        </div>

        <div class="card has-table">
            <header class="card-header">
                <p class="card-header-title">
                    <span class="icon"><i class="fa-solid fa-burger"></i></span>
                    orders
                </p>
                <a href="{{ route('menu-orders') }}" class="card-header-icon bg-blue-200 rounded-sm">
                    <span class="icon"><i class="mdi mdi-reload"></i></span>
                </a>
            </header>
            <div class="card-content">
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-gray-600">Username</th>
                            <th class="text-gray-600">Image</th>
                            <th class="text-gray-600">Description</th>
                            <th class="text-gray-600">Price</th>
                            <th class="text-gray-600">Created</th>
                            <th class="text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $key => $order)
                        <tr class="hover:cursor-pointer">
                            <td class="p-3 text-center">{{ $loop->iteration }}</td>
                            <td>{{ $order['username'] ?? 'null' }}</td>
                            <td>{{ $order['items'] }}</td>
                            <!-- <td class="image-cell">
                                <div>
                                    <img src="{{ is_array($order['imageUrl']) ? $order['imageUrl'][0] : ($order['imageUrl'] ?? 'default_image_path') }}" class="rounded-sm w-12 h-12 object-cover" alt="{{ $order['name'] }}">
                                </div>
                            </td>
                            <td class="truncate max-w-sm">{{ $order['menuDescription'] ?? 'null' }}</td>
                            <td>{{ $order['price'] ?? 'null' }}</td>
                            <td class="text-gray-500">
                                <small>{{ $order['time_added'] ?? 'null' }}</small>
                            </td> -->
                            <td class="actions-cell">
                                <div class="buttons right nowrap">
                                    <button class="button small blue --jb-modal" data-target="view-modal-{{ $key }}" type="button">
                                        <span class="icon"><i class="mdi mdi-eye"></i></span>
                                    </button>
                                    <button class="button small green --jb-modal" data-target="edit-modal-{{ $key }}" type="button">
                                        <span class="icon"><i class="fa-solid fa-pen-to-square"></i></span>
                                    </button>
                                    <button class="button small red --jb-modal" data-target="archive-modal{{ $order['menuId'] }}" type="button">
                                        <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                                    </button>
                                    <!-- <form id="archiveForm" action="{{ route('menu-orders.archive', ['id' => $order['menuId']]) }}" method="POST" class="inline-block ml-4">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="button small red --jb-modal" data-target="delete_modal" type="button">
                                            <span class="icon"><i class="mdi mdi-archive-arrow-down"></i></span>
                                        </button>
                                    </form> -->
                                </div>
                            </td>
                        </tr>

                        <div id="archive-modal{{ $order['menuId'] }}" class="modal hidden">
                            <div class="modal-background --jb-modal-close"></div>
                            <div class="modal-card">
                                <header class="modal-card-head">
                                    <p class="modal-card-title text-black">Confirm Archive</p>
                                </header>
                                <section class="modal-card-body text-gray-700">
                                    <p>Are you sure you want to archive this order?</p>
                                    <p>This action will move the order to the archives.</p>
                                </section>
                                <footer class="modal-card-foot justify-end">
                                    <button id="cancel-archive" class="button --jb-modal-close">Cancel</button>
                                    <button id="confirm-archive" class="button blue">Confirm</button>
                                </footer>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4  text-center">No orders available.</td>
                        </tr>

                        @endforelse
                    </tbody>
                </table>
                <div class="table-pagination">
                    @if (count($data) > 0)
                    <div class="w-full mt-5 ">{{ $data->links('vendor.pagination.tailwind') }}</div>
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