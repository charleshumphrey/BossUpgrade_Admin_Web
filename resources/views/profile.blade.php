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
                <li>Profile</li>
            </ul>

        </div>
    </section>

    <section class="section main-section">
        @include('shared.success')

        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    <span class="icon"><i class="fa-solid fa-user-tag"></i></span>
                    Admin Profile
                </p>
            </header>
            <div class="card-content">
                <div class="field">
                    <label class="font-bold text-sm">Profile Picture</label>
                    <div class="profile-image-container mb-4">
                        <img src="{{ Session::get('user')['profileImage'] ?? asset('default-profile.jpg') }}" alt="Profile Image" class="rounded-full h-24 w-24 object-cover border border-gray-300">
                    </div>
                </div>

                <div class="field">
                    <label for="fullname" class="font-bold text-sm">Full Name</label>
                    <div class="control icons-left">
                        <input readonly name="fullname" id="fullname" class="input" type="text" placeholder="Full Name" value="{{ Session::get('user')['fullname'] }}">
                        <span class="icon left"><i class="mdi mdi-account"></i></span>
                    </div>
                    @error('fullname')
                    <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label for="username" class="font-bold text-sm">Username</label>
                    <div class="control icons-left">
                        <input readonly value="{{ Session::get('user')['username'] }}" name="username" id="username" class="input" type="text" placeholder="e.g., example123">
                        <span class="icon left"><i class="mdi mdi-account"></i></span>
                    </div>
                    @error('username')
                    <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label for="email" class="font-bold text-sm">Email</label>
                    <div class="control icons-left icons-right">
                        <input readonly value="{{ Session::get('user')['email'] }}" name="email" id="email" class="input" type="email" placeholder="e.g., example@gmail.com">
                        <span class="icon left"><i class="mdi mdi-mail"></i></span>
                        <span class="icon right"><i class="mdi mdi-check"></i></span>
                    </div>
                    @error('email')
                    <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label for="phone" class="font-bold text-sm">Phone</label>
                    <div class="control icons-left">
                        <input readonly value="{{ Session::get('user')['phone'] }}" name="phone" id="phone" class="input" type="tel" placeholder="Enter phone number">
                        <span class="icon left"><i class="mdi mdi-phone"></i></span>
                    </div>
                    @error('phone')
                    <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <hr class="my-4">

                <div class="field grouped">
                    <div class="control">
                        <a class="button blue" href="{{ route('profile_update') }}">
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </section>
    </div>
    <!-- Icons below are for demo only. Feel free to use any icon pack. Docs: https://bulma.io/documentation/elements/icon/ -->
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">

</body>

</html>