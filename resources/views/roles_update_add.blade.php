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
                <li>Administration</li>
                <li>Role</li>
                <li>Create Role</li>
            </ul>

        </div>
    </section>

    <section class="section main-section">
        @include('shared.success')

        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    <span class="icon"><i class="fa-solid fa-user-tag"></i></span>
                    @if ($editing ?? false)
                    Update Role
                    @else
                    Create Role
                    @endif
                </p>

            </header>
            <div class="card-content">
                @if ($editing ?? false)
                <form action="{{ route('roles.update', ['role' => $role->id ?? null]) }}" method="POST">
                    @csrf
                    @if(isset($role))
                    @method('PUT')
                    @else
                    @method('POST')
                    @endif

                    <h3>{{ isset($role) ? 'Edit Role: ' . $role->name : 'Create New Role' }}</h3>

                    <div>
                        <label for="roleName">Role Name:</label>
                        <input type="text" id="roleName" name="name" value="{{ $role->name ?? '' }}" required class="border border-gray-300 p-2 rounded-lg">
                    </div>

                    <h4>Permissions</h4>
                    <div>
                        <label>
                            <input type="checkbox" name="permissions[view_dashboard]" value="1" {{ $rolePermissions['view_dashboard'] ?? false ? 'checked' : '' }}>
                            View Dashboard
                        </label>
                    </div>

                    <div>
                        <label>
                            <input type="checkbox" name="permissions[manage_users]" value="1" {{ $rolePermissions['manage_users'] ?? false ? 'checked' : '' }}>
                            Manage Users
                        </label>
                    </div>

                    <div>
                        <label>
                            <input type="checkbox" name="permissions[manage_orders]" value="1" {{ $rolePermissions['manage_orders'] ?? false ? 'checked' : '' }}>
                            Manage Orders
                        </label>
                    </div>

                    <button type="submit">{{ isset($role) ? 'Update Role' : 'Create Role' }}</button>
                </form>
                @else
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 font-medium">Role Name</label>
                        <input type="text" name="name" id="name" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                            placeholder="Enter role name">
                    </div>

                    <div class="mb-4">
                        <label for="permissions" class="block text-gray-700 font-medium mb-2">Permissions</label>

                        <div class="flex flex-col space-y-2">
                            <div>
                                <input type="checkbox" name="permissions[view_dashboard]" id="view_dashboard" value=true>
                                <label for="view_dashboard" class="text-gray-600">View Dashboard</label>
                            </div>

                            <div>
                                <input type="checkbox" name="permissions[manage_administration]" id="manage_users" value=true>
                                <label for="manage_users" class="text-gray-600">Manage Roles/Staff</label>
                            </div>

                            <div>
                                <input type="checkbox" name="permissions[manage_menu]" id="manage_menu" value=true>
                                <label for="manage_menu" class="text-gray-600">Manage Menu Items</label>
                            </div>
                            <div>
                                <input type="checkbox" name="permissions[manage_category]" id="manage_category" value=true>
                                <label for="manage_category" class="text-gray-600">Manage Category</label>
                            </div>
                            <div>
                                <input type="checkbox" name="permissions[manage_archives]" id="manage_archives" value=true>
                                <label for="manage_archives" class="text-gray-600">Manage Archives</label>
                            </div>
                            <div>
                                <input type="checkbox" name="permissions[manage_pending_orders]" id="manage_pending_orders" value=true>
                                <label for="manage_pending_orders" class="text-gray-600">Manage Pending Orders</label>
                            </div>
                            <div>
                                <input type="checkbox" name="permissions[manage_confirmed_orders]" id="manage_confirmed_orders" value=true>
                                <label for="manage_confirmed_orders" class="text-gray-600">Manage Confirmed Orders</label>
                            </div>
                            <div>
                                <input type="checkbox" name="permissions[manage_on_preparation_orders]" id="manage_on_preparation_orders" value=true>
                                <label for="manage_on_preparation_orders" class="text-gray-600">Manage On Preparation Orders</label>
                            </div>
                            <div>
                                <input type="checkbox" name="permissions[manage_for_delivery_orders]" id="manage_for_delivery_orders" value=true>
                                <label for="manage_for_delivery_orders" class="text-gray-600">Manage For Delivery Orders</label>
                            </div>
                            <div>
                                <input type="checkbox" name="permissions[manage_messages]" id="manage_messages" value=true>
                                <label for="manage_messages" class="text-gray-600">Manage Messages</label>
                            </div>
                            <div>
                                <input type="checkbox" name="permissions[manage_faqs]" id="manage_faqs" value=true>
                                <label for="manage_faqs" class="text-gray-600">Manage FAQs</label>
                            </div>
                            <div>
                                <input type="checkbox" name="permissions[manage_promotions]" id="manage_promotions" value=true>
                                <label for="manage_promotions" class="text-gray-600">Manage Promotions</label>
                            </div>
                            <div>
                                <input type="checkbox" name="permissions[manage_feedback]" id="manage_feedback" value=true>
                                <label for="manage_feedback" class="text-gray-600">Manage Feedbacks</label>
                            </div>
                        </div>
                    </div>


                    <div class="mt-6">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                            Create Role
                        </button>
                    </div>
                </form>
                @endif

            </div>
        </div>
    </section>
    </div>
    <!-- Icons below are for demo only. Feel free to use any icon pack. Docs: https://bulma.io/documentation/elements/icon/ -->
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">

</body>

</html>