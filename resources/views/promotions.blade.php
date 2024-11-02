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
                <li>Promotions</li>
            </ul>

        </div>
    </section>

    <section class="section main-section">

        @include('shared.success')
        @include('shared.error')

        <div class="card mb-4">
            <header class="card-header">
                <p class="card-header-title">
                    <span class="icon"><i class="fa-solid fa-plus"></i></i></span>
                    Add Promotional Image
                </p>
            </header>
            <div class="card-content bg-white p-6 rounded-lg shadow-lg">
                <div class="mt-6">
                    <form action="{{ route('promotions.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
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
                                        <input name="promotional_images[]" type="file" class="opacity-0" id="fileInput" multiple required />
                                    </label>
                                </div>
                            </div>
                            @error('promotional_images')
                            <p class="text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <button
                            type="submit"
                            class="bg-blue-500 text-white rounded-md px-4 py-2 hover:bg-blue-600 transition duration-200 ease-in-out">
                            Add Images
                        </button>
                </div>
                </form>
            </div>
        </div>
        </div>

        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    <span class="icon"><i class="fa-solid fa-rectangle-ad"></i></i></span>
                    Promotional Image
                </p>
            </header>
            <div class="card-content bg-white p-6 rounded-lg shadow-lg">
                <div class="mb-3">
                    <div class="image-slider flex items-center justify-center mt-4">
                        <button type="button" class="flex items-center justify-center w-10 h-10 bg-gray-300 text-gray-800 rounded-full hover:bg-gray-400 transition duration-200 ease-in-out shadow-md" id="prevBtn">
                            ❮
                        </button>

                        <div class="image-container mx-4 relative">
                            @if(is_array($promotions) && count($promotions) > 0)
                            <img src="{{ $promotions[0] }}" alt="Promotion Image" class="slider-image rounded-md shadow-lg transition-transform duration-300 ease-in-out transform hover:scale-105" id="sliderImage">
                            @else
                            <p class="text-gray-500">No promotions available.</p>
                            @endif
                        </div>

                        <button type="button" class="flex items-center justify-center w-10 h-10 bg-gray-300 text-gray-800 rounded-full hover:bg-gray-400 transition duration-200 ease-in-out shadow-md" id="nextBtn">
                            ❯
                        </button>
                    </div>
                    <div class="image-thumbnails mt-4 grid grid-cols-3 gap-2">
                        @if(is_array($promotions) && count($promotions) > 0)
                        @foreach($promotions as $key => $image)
                        <img src="{{ $image }}" alt="Thumbnail" class="thumbnail rounded-md border border-gray-300 hover:border-gray-500 transition duration-200 ease-in-out cursor-pointer" data-index="{{ $key }}">
                        @endforeach
                        @endif
                    </div>
                </div>

                <div class="flex flex-col space-y-4 p-6 bg-gray-50 rounded-lg">
                    @if(is_array($promotions) && count($promotions) > 0)
                    @foreach($promotions as $key => $promotion)
                    <div class="bg-white rounded-lg shadow-md p-4 flex items-center justify-between transition-transform duration-300 ease-in-out hover:shadow-xl hover:scale-105">
                        <img src="{{ $promotion }}" alt="Promotion Image" class="slider-image rounded-md shadow-lg w-32 h-32 object-cover ">
                        <div class="flex-shrink-0">
                            <form action="{{ route('promotions.destroy', $key) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="bg-red-500 text-white rounded-md px-4 py-2 hover:bg-red-600 transition duration-200 ease-in-out">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <p class="text-gray-500 text-center">No promotions available.</p>
                    @endif
                </div>


            </div>

        </div>
    </section>

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