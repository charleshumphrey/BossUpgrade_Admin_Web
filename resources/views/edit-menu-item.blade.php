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
                <form class="px-7 pb-5" action="{{ route('menu-items.update', $menuItem['menuId']) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label text-lg font-semibold">Menu Item Image(s)</label>
                        <div class="image-slider flex items-center justify-center mt-4">
                            <button type="button" class="flex items-center justify-center w-10 h-10 bg-gray-300 text-gray-800 rounded-full hover:bg-gray-400 transition duration-200 ease-in-out shadow-md" id="prevBtn">
                                ❮
                            </button>

                            <div class="image-container mx-4 relative">
                                @if(is_array($menuItem['imageUrl']) && count($menuItem['imageUrl']) > 0)
                                <img src="{{ $menuItem['imageUrl'][0] }}" alt="Menu Image" class="slider-image rounded-md shadow-lg transition-transform duration-300 ease-in-out transform hover:scale-105">
                                @else
                                <p class="text-gray-500">No images uploaded.</p>
                                @endif
                            </div>
                            <button type="button" class="flex items-center justify-center w-10 h-10 bg-gray-300 text-gray-800 rounded-full hover:bg-gray-400 transition duration-200 ease-in-out shadow-md" id="nextBtn">
                                ❯
                            </button>
                        </div>

                        <div class="image-thumbnails mt-4 grid grid-cols-3 gap-2">
                            @if(is_array($menuItem['imageUrl']) && count($menuItem['imageUrl']) > 0)
                            @foreach($menuItem['imageUrl'] as $key => $image)
                            <img src="{{ $image }}" alt="Thumbnail" class="thumbnail rounded-md border border-gray-300 hover:border-gray-500 transition duration-200 ease-in-out cursor-pointer" data-index="{{ $key }}">
                            @endforeach
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="mb-4 cursor-pointer">
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
                                    <input name="images[]" type="file" class="opacity-0" id="fileInput" multiple />
                                </label>
                            </div>
                        </div>
                        @error('images')
                        <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4 w-full">
                        <label for="name" class="block text-sm font-medium text-gray-700">Menu Name</label>
                        <input value="{{ old('name', $menuItem['name']) }}" placeholder="e.g, adobo" type="text" name="name" id="name" class="bg-zinc-50 border py-3 px-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        @error('name')
                        <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pb-4">
                        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                        <select class="border border-gray-300 p-2 rounded-lg w-full" id="category" name="categoryId" required>
                            @foreach($categories as $category)
                            <option value="{{ $category['id'] }}" {{ $selectedCategoryId == $category['id'] ? 'selected' : '' }}>
                                {{ $category['name'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3" class="py-3 px-2 resize-none h-auto border mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        {{ old('description', $menuItem['menuDescription']) }}
                        </textarea>
                        @error('description')
                        <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                        <input value="{{ old('price', $menuItem['price']) }}" step="0.01" type="number" name="price" id="price" placeholder="e.g, 1234" class="bg-zinc-50 border py-3 px-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        @error('price')
                        <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update Menu Item</button>
                    <a href="{{ route('menu-items') }}" class="btn btn-secondary">Cancel</a>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const thumbnails = document.querySelectorAll('.thumbnail');
            const sliderImage = document.querySelector('.slider-image');
            let currentIndex = 0;

            if (thumbnails.length > 0) {
                sliderImage.src = thumbnails[currentIndex].src;


                function updateImage() {
                    sliderImage.src = thumbnails[currentIndex].src;
                }

                document.getElementById('prevBtn').addEventListener('click', function() {
                    currentIndex = (currentIndex > 0) ? currentIndex - 1 : thumbnails.length - 1;
                    updateImage();
                });

                document.getElementById('nextBtn').addEventListener('click', function() {
                    currentIndex = (currentIndex < thumbnails.length - 1) ? currentIndex + 1 : 0;
                    updateImage();
                });

                thumbnails.forEach((thumbnail, index) => {
                    thumbnail.addEventListener('click', function() {
                        currentIndex = index;
                        updateImage();
                    });
                });
            } else {
                console.warn("No images available to display.");
            }
        });
    </script>

</body>

</html>