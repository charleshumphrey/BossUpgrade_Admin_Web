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
                <li>Edit Profile</li>
            </ul>

        </div>
    </section>

    <section class="section main-section">
        @include('shared.success')

        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    <span class="icon"><i class="fa-solid fa-user-tag"></i></span>
                    Edit Profile
                </p>
            </header>
            <div class="card-content">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    <!-- Profile Picture -->
                    <div class="field">
                        <label class="font-bold text-sm">Profile Picture</label>
                        <div class="profile-image-container mb-4">
                            <img src="{{ Session::get('user')['profileImage'] ?? asset('default-profile.jpg') }}"
                                alt="Profile Image"
                                class="rounded-full h-24 w-24 object-cover border border-gray-300">
                        </div>
                        <input type="file" name="profileImage" class="input">
                        <p class="help">Upload a new profile picture if you want to update it.</p>
                    </div>

                    <!-- Full Name -->
                    <div class="field">
                        <label for="fullname" class="font-bold text-sm">Full Name</label>
                        <div class="control icons-left">
                            <input name="fullname" id="fullname" class="input" type="text"
                                placeholder="Full Name" value="{{ Session::get('user')['fullname'] }}">
                            <span class="icon left"><i class="mdi mdi-account"></i></span>
                        </div>
                        @error('fullname')
                        <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div class="field">
                        <label for="username" class="font-bold text-sm">Username</label>
                        <div class="control icons-left">
                            <input name="username" id="username" class="input" type="text"
                                placeholder="e.g., example123" value="{{ Session::get('user')['username'] }}">
                            <span class="icon left"><i class="mdi mdi-account"></i></span>
                        </div>
                        @error('username')
                        <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="field">
                        <label for="email" class="font-bold text-sm">Email</label>
                        <div class="control icons-left icons-right">
                            <input name="email" id="email" class="input" type="email"
                                placeholder="e.g., example@gmail.com" value="{{ Session::get('user')['email'] }}">
                            <span class="icon left"><i class="mdi mdi-mail"></i></span>
                        </div>
                        @error('email')
                        <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="field">
                        <label for="phone" class="font-bold text-sm">Phone</label>
                        <div class="control icons-left">
                            @php
                            $phone = Str::replaceFirst('+63', '', Session::get('user')['phone']);
                            @endphp
                            <input name="phone" id="phone" class="input" type="tel"
                                placeholder="Enter phone number" value="{{ $phone }}">
                            <span class="icon left"><i class="mdi mdi-phone"></i></span>
                        </div>
                        <p class="help text-gray-500">Do not enter the first zero</p>
                        @error('phone')
                        <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <!-- Update Button -->
                    <div class="field grouped">
                        <div class="control">
                            <button type="submit" class="button green">
                                Update Profile
                            </button>
                        </div>
                    </div>
                </form>
            </div>


        </div>


        </div>
    </section>
    </div>
    <!-- Icons below are for demo only. Feel free to use any icon pack. Docs: https://bulma.io/documentation/elements/icon/ -->
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">

</body>

</html>