<div id="app">
    <nav id="navbar-main" class="navbar is-fixed-top">
        <div class="navbar-brand">
            <a class="navbar-item mobile-aside-button">
                <span class="icon"><i class="mdi mdi-forwardburger mdi-24px"></i></span>
            </a>
        </div>
        <div class="navbar-brand is-right">
            <a class="navbar-item --jb-navbar-menu-toggle" data-target="navbar-menu">
                <span class="icon"><i class="mdi mdi-dots-vertical mdi-24px"></i></span>
            </a>
        </div>
        <div class="navbar-item dropdown has-divider has-user-avatar text-black bg-white">
            <a class="navbar-link bg-white">
                <div class="user-avatar bg-gray-100 rounded-full">
                    <img src="{{ asset('build/assets/images/avatar-default-symbolic-svgrepo-com.svg') }}" alt="Profile_Image" class="rounded-full">
                </div>
                <div class="is-user-name"><span>Patatas</span></div>
                <span class="icon"><i class="mdi mdi-chevron-down"></i></span>
            </a>
            <div class="navbar-dropdown bg-white text-black">
                <a href="profile.html" class="navbar-item hover:bg-gray-100">
                    <span class="icon"><i class="mdi mdi-account"></i></span>
                    <span>My Profile</span>
                </a>
                <a class="navbar-item hover:bg-gray-100">
                    <span class="icon"><i class="mdi mdi-settings"></i></span>
                    <span class="text-xs">Change Password</span>
                </a>
                <hr class="navbar-divider">
                <a class="navbar-item text-red-500 hover:bg-gray-100">
                    <span class="icon"><i class="mdi mdi-logout"></i></span>
                    <span>Log Out</span>
                </a>
            </div>
        </div>
    </nav>
</div>