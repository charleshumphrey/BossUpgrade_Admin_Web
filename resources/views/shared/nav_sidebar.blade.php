<div class="menu is-menu-main py-2 bg-black-erie">
    <ul class="menu-list">
        <li class="active">
            <a href="{{ route('dashboard') }}">
                <span class="icon"><i class="mdi mdi-desktop-mac"></i></span>
                <span class="menu-item-label">Dashboard</span>
            </a>
        </li>
    </ul>
    <p class="menu-label">Menu</p>
    <ul class="menu-list">
        <li class="--set-active-tables-html">
            <a class="dropdown">
                <span class="icon"><i class="mdi mdi-account-multiple"></i></span>
                <span class="menu-item-label">Administration</span>
                <span class="icon"><i class="mdi mdi-plus"></i></span>
            </a>
            <ul>
                <li>
                    <a href="{{ route('roles.index') }}">
                        <span>Roles</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('staff.index') }}">
                        <span>Staff</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="--set-active-forms-html">
            <a href="{{ route('menu-items') }}">
                <span class="icon"><i class="fa-solid fa-burger"></i></span>
                <span class="menu-item-label">Menu Items</span>
            </a>
        </li>
        <li>
            <a href="{{ route('categories.index') }}">
                <span class="icon"><i class="fa-solid fa-layer-group"></i></span>
                <span class="menu-item-label">Category</span>
            </a>
        </li>
        <li class="--set-active-profile-html">
            <a href="{{ route('orders.paginated') }}">
                <span class="icon"><i class="mdi mdi-account-circle"></i></span>
                <span class="menu-item-label">Orders</span>
            </a>
        </li>
        <li class="--set-active-profile-html">
            <a href="{{ route('archive.index') }}">
                <span class="icon"><i class="mdi mdi-package-down"></i></span>
                <span class="menu-item-label">Archives</span>
            </a>
        </li>
    </ul>
</div>