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
                <li>Admin</li>
                <li>Menu Items</li>
                <li>Add Menu Items</li>
            </ul>

        </div>
    </section>

    <section class="section main-section">

        @include('shared.success')

        <div class="card has-table">
            <header class="card-header">
                <p class="card-header-title">
                    <span class="icon"><i class="fa-solid fa-burger"></i></span>
                    Item
                </p>
            </header>
            <div class="card-content">
                <form class="px-7 pb-5" action="{{ route('menu-items.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div>
                        <div class="max-w-lg mb-4 cursor-pointer">
                            <label class="block text-sm font-medium text-gray-700 my-2">Upload Image(s)</label>
                            <div class="flex items-center justify-center w-full">
                                <label
                                    class="flex flex-col w-full h-32 border-4 border-blue-200 border-dashed hover:bg-gray-100 hover:border-gray-300 cursor-pointer">
                                    <div class="flex flex-col items-center justify-center pt-7">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400 group-hover:text-gray-600 cursor-pointer"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        <p class="font-poppins_bold font-medium pt-1 text-sm tracking-wider text-gray-400 group-hover:text-gray-600 cursor-pointer" id="fileText">
                                            Attach a file</p>
                                    </div>
                                    <input name="images[]" type="file" class="opacity-0" id="fileInput" multiple required />
                                </label>
                            </div>
                        </div>
                        @error('images')
                        <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>


                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Menu Name</label>
                        <input placeholder="e.g, adobo" type="text" name="name" id="name" class="bg-zinc-50 max-w-lg border py-3 px-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        @error('name')
                        <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="max-w-lg pb-4">
                        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                        <select id="category" name="category" class="border border-gray-300 p-2 rounded-lg">
                            <option value="" disabled selected>Select category</option>
                            @if (count($categories) > 0)
                            @foreach ($categories as $category)
                            <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                            @endforeach
                            @else
                            <option>No categories available</option>
                            @endif
                        </select>
                        @error('category')
                        <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3" class="py-3 px-2 bg-zinc-50 max-w-lg resize-none border mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required></textarea>
                        @error('description')
                        <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                        <input type="number" name="price" id="price" placeholder="e.g, 1234" class="bg-zinc-50 max-w-lg border py-3 px-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        @error('price')
                        <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex">
                        <button type="submit" class="button bg-blue-400 font-poppins_regular font-semibold">
                            Add Menu Item
                        </button>
                    </div>
                </form>
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