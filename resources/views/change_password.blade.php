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
                <li>Change Password</li>
            </ul>

        </div>
    </section>

    <section class="section main-section">
        @include('shared.success')
        @include('shared.error')

        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    <span class="icon"><i class="mdi mdi-lock"></i></span>
                    Change Password
                </p>
            </header>
            <div class="card-content">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <div class="field">
                        <label class="label">Current password</label>
                        <div class="control">
                            <input type="password" name="current-password" class="input" required="">
                        </div>
                        @if ($errors->has('current-password'))
                        <p class="help is-danger text-red-600">{{ $errors->first('current-password') }}</p>
                        @else
                        <p class="help">Required. Your current password</p>
                        @endif
                    </div>
                    <hr>
                    <div class="field">
                        <label class="label">New password</label>
                        <div class="control">
                            <input type="password" name="new-password" class="input" required="">
                        </div>
                        @if ($errors->has('new-password'))
                        <p class="help is-danger text-red-600">{{ $errors->first('new-password') }}</p>
                        @else
                        <p class="help">Required. New password</p>
                        @endif
                    </div>
                    <div class="field">
                        <label class="label">Confirm password</label>
                        <div class="control">
                            <input type="password" name="new-password-confirmation" class="input" required="">
                        </div>
                        @if ($errors->has('new-password-confirmation'))
                        <p class="help is-danger text-red-600">{{ $errors->first('new-password-confirmation') }}</p>
                        @else
                        <p class="help">Required. New password one more time</p>
                        @endif
                    </div>
                    <hr>
                    <div class="field">
                        <div class="control">
                            <button type="submit" class="button green">
                                Change Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>


        </div>

    </section>
    </div>
    <!-- Icons below are for demo only. Feel free to use any icon pack. Docs: https://bulma.io/documentation/elements/icon/ -->
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">

</body>

</html>