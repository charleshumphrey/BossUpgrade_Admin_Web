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
                <li>Category</li>
            </ul>

        </div>
    </section>

    <section class="section main-section">

        @include('shared.success')
        @include('shared.error')

        <div class="w-full flex justify-end py-2 shadow-sm bg-white rounded-sm px-3 mb-2 justify-between">
            <button class="button bg-accent-color --jb-modal" data-target="add-category-modal" type="button">
                Add New
            </button>
        </div>
        <div class="card has-table">
            <header class="card-header">
                <p class="card-header-title">
                    <span class="icon"><i class="fa-solid fa-layer-group"></i></span>
                    Categories
                </p>
                <a href="{{ route('categories.index') }}" class="card-header-icon bg-blue-200 rounded-sm">
                    <span class="icon"><i class="mdi mdi-reload"></i></span>
                </a>
            </header>
            <div class="card-content">
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-gray-600">Name</th>
                            <th class="text-gray-600">Created</th>
                            <th class="text-gray-600 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $key => $category)
                        <tr class="hover:cursor-pointer">
                            <td class="p-3 text-center">{{ $loop->iteration }}</td>
                            <td>{{ $category['categoryName'] ?? 'null' }}</td>
                            <td class="text-gray-500">
                                <small>{{ $category['added_date'] ?? 'null' }}</small>
                            </td>
                            <td>
                                <div class="justify-center flex gap-2">
                                    <button class="button small blue --jb-modal" data-target="add-modal" type="button">
                                        <span class="icon"><i class="mdi mdi-eye"></i></span>
                                    </button>
                                    <button class="button small green --jb-modal" data-target="sample-modal-2" type="button">
                                        <span class="icon"><i class="fa-solid fa-pen-to-square"></i></span>
                                    </button>
                                    <button class="button small red --jb-modal" data-target="delete-modal{{$category['categoryId']}}" type="button">
                                        <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <div id="delete-modal{{$category['categoryId']}}" class="modal">
                            <div class="modal-background --jb-modal-close"></div>
                            <div class="modal-card">
                                <header class="modal-card-head">
                                    <i class="fa-solid fa-trash-can text-red-500 mr-4"></i>
                                    <p class="modal-card-title text-black">Delete Category</p>
                                </header>
                                <section class="modal-card-body text-gray-700">
                                    <p>Are you sure you want to delete the category <b>{{ $category['categoryName'] }}</b>?</p>
                                    <p>If there are any menu items under this category, the deletion will not be allowed.</p>
                                </section>
                                <footer class="modal-card-foot justify-end">
                                    <button class="button --jb-modal-close">Cancel</button>
                                    <form action="{{ route('categories.delete', $category['categoryId']) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="button red">Confirm Delete</button>
                                    </form>
                                </footer>
                            </div>
                        </div>

                        @endforeach

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

    <div id="add-category-modal" class="modal">
        <div class="modal-background --jb-modal-close"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title text-black">Add Category</p>
            </header>
            <section class="modal-card-body text-gray-700">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <input name="category-name" class="input" type="text" placeholder="Category Name">
            </section>
            <footer class="modal-card-foot justify-end">
                <button class="button --jb-modal-close">Cancel</button>
                <button class="button blue --jb-modal-close" type="submit">Add</button>
                </form>
            </footer>
        </div>
    </div>
    </div>

    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">

</body>

</html>