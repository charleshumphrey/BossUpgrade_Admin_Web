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
                <li>Staff</li>
            </ul>

        </div>
    </section>

    <section class="section main-section">
        @include('shared.success')

        <div class="w-full flex justify-end py-2 shadow-sm bg-white rounded-sm px-3 mb-2 justify-between">
            <a class="button bg-accent-color" href="{{ route('staff.create') }}">Add New</a>
        </div>

        <div class="card has-table">
            <header class="card-header">
                <p class="card-header-title">
                    <span class="icon"><i class="fa-solid fa-user-tag"></i></span>
                    Staff
                </p>
                <a href="{{ route('staff.index') }}" class="card-header-icon bg-blue-200 rounded-sm">
                    <span class="icon"><i class="mdi mdi-reload"></i></span>
                </a>
            </header>
            <div class="card-content">
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-gray-600">Username</th>
                            <th class="text-gray-600">Profile Image</th>
                            <th class="text-gray-600">Role</th>
                            <th class="text-gray-600">Created At</th>
                            <th class="text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($staffs as $staff)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $staff['username'] ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if (!empty($staff['profileImage']))
                                <img class="w-12 h-12 object-cover" src="{{ $staff['profileImage'] }}" alt="staff_profile_image">
                                @else
                                <p>N/A</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $staff['roleName'] ?? 'N/A' }}</div>
                            </td>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $staff['created_at'] ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="#">
                                    <button class="button small green --jb-modal" type="button">
                                        <span class="icon"><i class="fa-solid fa-pen-to-square"></i></span>
                                    </button>
                                </a>
                                <form id="deleteForm" action="" method="POST" class="inline-block ml-4">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button small red --jb-modal" data-target="delete_modal" type="button">
                                        <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No staffs found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="table-pagination">
                    @if (count($staffs) > 0)
                    <div class="w-full mt-5 ">{{ $staffs->links('vendor.pagination.tailwind') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <div id="deleteModal" class="modal" style="display: none;">
        <div class="modal-background" onclick="closeDeleteModal()"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title text-black">Remove Role</p>
            </header>
            <section class="modal-card-body text-gray-700">
                <!-- Display the staff name dynamically -->
                <p>Are you sure you want to remove the staff <b id="roleNameInModal"></b>?</p>
            </section>
            <footer class="modal-card-foot">
                <button class="button" onclick="closeDeleteModal()">Cancel</button>
                <button class="button red" onclick="confirmDelete()">Confirm</button>
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