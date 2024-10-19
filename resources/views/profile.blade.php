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
                    Profile
                </p>
            </header>
            <div class="card-content">
                <!-- <form method="post" action="{{ route('staff.store') }}" enctype="multipart/form-data">
                    @csrf
                    @method('POST') -->
                <!-- <div class="field">
                    <label class="font-bold text-sm" for="input_image">Profile <span class="text-gray-400">(Optional)</span></label>
                    <input name="input_image" class="block w-full text-lg text-gray-900 border border-gray-300 rounded-sm cursor-pointer bg-gray-50 focus:outline-none" id="input_image" type="file">
                    @error('input_image')
                    <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div> -->

                <div class="field">

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
                        <input readonly value="{{ Session::get('user')['username'] }}" name=" username" id="username" class="input" type="text" placeholder="e.g, example123">
                        <span class="icon left"><i class="mdi mdi-account"></i></span>
                    </div>
                    @error('username')
                    <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label for="email" class="font-bold text-sm">Email</label>
                    <div class="control icons-left icons-right">
                        <input readonly value="{{ Session::get('user')['email'] }}" name="email" id="email" class="input" type="email" placeholder="e.g, example@gmail.com">
                        <span class="icon left"><i class="mdi mdi-mail"></i></span>
                        <span class="icon right"><i class="mdi mdi-check"></i></span>
                    </div>
                    @error('email')
                    <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label for="phone" class="font-bold text-sm">Phone</label>
                    <div class="field-body">
                        <div class="field">
                            <div class="field addons">
                                <!-- <div class="control">
                                    <input class="input" value="+63" size="3" readonly="">
                                </div> -->
                                <div class="control expanded">
                                    <input readonly value="{{ Session::get('user')['phone'] }}" name=" phone" id="phone" class="input" type="tel" placeholder="Enter phone number">
                                </div>
                            </div>
                            <p class="help">Do not enter the first zero</p>
                        </div>
                    </div>
                    @error('phone')
                    <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <hr>
                <div class="field">
                    <label for="password" class="font-bold text-sm">Password</label>
                    <div class="control icons-left">
                        <input name="password" id="password" class="input" type="password" placeholder="●●●●●●●●">
                        <span class="icon left"><i class="mdi mdi-lock"></i></span>
                    </div>
                    @error('password')
                    <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field">
                    <label for="retypepass" class="font-bold text-sm">Retype Password</label>
                    <div class="control icons-left">
                        <input name="retypepass" id="retypepass" class="input" type="password" placeholder="●●●●●●●●">
                        <span class="icon left"><i class="mdi mdi-lock"></i></span>
                    </div>
                    @error('retypepass')
                    <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field grouped">
                    <div class="control">
                        <button type="submit" class="button green">
                            Add
                        </button>
                    </div>
                    <div class="control">
                        <button type="reset" class="button border border-gray-300">
                            Cancel
                        </button>
                    </div>
                </div>
                <!-- </form> -->
            </div>
        </div>
        </div>
    </section>
    </div>
    <!-- Icons below are for demo only. Feel free to use any icon pack. Docs: https://bulma.io/documentation/elements/icon/ -->
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">

</body>

</html>