<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-database.js"></script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>

<body>
    <div id="app">

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
                    <li>Dashboard</li>
                </ul>

            </div>
        </section>
        <section class="is-hero-bar">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
                <h1 class="title">
                    Dashboard
                </h1>
            </div>
        </section>
        <section class="section main-section">
            @include('shared.metrics')

            <div class="flex overflow-hidden w-full gap-5 md:flex-col">
                <div class="card my-5">
                    <header class="card-header">
                        <p class="card-header-title">
                            <span class="icon"><i class="mdi mdi-finance"></i></span>
                            Income
                        </p>
                        <a href="#" class="card-header-icon">
                            <span class="icon"><i class="mdi mdi-reload"></i></span>
                        </a>
                    </header>

                    <div class="card-content">
                        <div class="chart-area">
                            <div class="h-full">
                                <div class="chartjs-size-monitor">
                                    <div class="chartjs-size-monitor-expand">
                                        <div></div>
                                    </div>
                                    <div class="chartjs-size-monitor-shrink">
                                        <div></div>
                                    </div>
                                </div>
                                <canvas id="bar-chart" width="980" height="500" class="chartjs-render-monitor block" style="height: 400px; width: 784px; display: block;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card my-5 w-full">
                    <header class="card-header">
                        <p class="card-header-title">
                            <span class="icon"><i class="mdi mdi-finance"></i></span>
                            Best Sellers
                        </p>
                        <a href="#" class="card-header-icon">
                            <span class="icon"><i class="mdi mdi-reload"></i></span>
                        </a>
                    </header>

                    <div class="card-content">
                        <div class="chart-area">
                            <div class="h-full">
                                <div class="chartjs-size-monitor">
                                    <div class="chartjs-size-monitor-expand">
                                        <div></div>
                                    </div>
                                    <div class="chartjs-size-monitor-shrink">
                                        <div></div>
                                    </div>
                                </div>
                                <canvas id="chart" width="980" height="500" class="chartjs-render-monitor block w-full h-96"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>

    </div>
    </div>
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">

</body>

</html>