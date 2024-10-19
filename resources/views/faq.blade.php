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
                <li>Frequently Asked Questions</li>
            </ul>

        </div>
    </section>

    <section class="section main-section">

        @include('shared.success')

        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    <span class="icon"><i class="fa-solid fa-plus"></i></span>
                    Add New FAQ
                </p>
            </header>
            <div class="card-content bg-white p-6 rounded-lg shadow-lg">
                <form action="{{ route('faq.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="question" class="block text-sm font-semibold text-gray-700">Question</label>
                        <input
                            type="text"
                            id="question"
                            name="question"
                            class="form-input mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out"
                            placeholder="Enter your question here"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="answer" class="block text-sm font-semibold text-gray-700">Answer</label>
                        <textarea
                            id="answer"
                            name="answer"
                            rows="4"
                            class="form-input mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out"
                            placeholder="Enter the answer here"
                            required></textarea>
                    </div>

                    <button
                        type="submit"
                        class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-200 ease-in-out shadow-sm">
                        Add FAQ
                    </button>
                </form>
            </div>

        </div>

        <div class="card mt-4">
            <header class="card-header">
                <p class="card-header-title">
                    <span class="icon"><i class="fa-solid fa-circle-question"></i></span>
                    Frequently Asked Questions
                </p>
                <a href="{{ route('faq.index') }}" class="card-header-icon bg-blue-200 rounded-sm">
                    <span class="icon"><i class="mdi mdi-reload"></i></span>
                </a>
            </header>
            <div class="card-content">
                @if ($faqs)
                <div class="space-y-4">
                    @foreach ($faqs as $key => $faq)
                    <details class="border border-gray-200 rounded-lg">
                        <summary class="w-full p-4 font-bold bg-gray-100 cursor-pointer focus:outline-none">
                            {{ $faq['question'] }}
                        </summary>
                        <div class="faq-answer p-4 text-gray-700">
                            {{ $faq['answer'] }}
                        </div>

                        <!-- Delete FAQ Form -->
                        <div class="p-4">
                            <form action="{{ route('faqs.destroy', $key) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this FAQ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                    Remove
                                </button>
                            </form>
                        </div>
                    </details>
                    @endforeach
                </div>
                @else
                <p>No FAQs available.</p>
                @endif
            </div>


        </div>
    </section>

    <!-- Icons below are for demo only. Feel free to use any icon pack. Docs: https://bulma.io/documentation/elements/icon/ -->
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
</body>

</html>