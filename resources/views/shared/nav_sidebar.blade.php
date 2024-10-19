<div class="menu is-menu-main py-2 bg-black-erie">
    <p class="menu-label">General</p>
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
            <a href="{{ route('archive.index') }}">
                <span class="icon"><i class="mdi mdi-package-down"></i></span>
                <span class="menu-item-label">Archives</span>
            </a>
        </li>
        <li>
            <a href="{{ route('pending_orders.paginated') }}">
                <span class="icon"><i class="fa-solid fa-clock"></i></span>
                <span class="menu-item-label">Pending Orders</span>
            </a>
        </li>
        <li>
            <a href="{{ route('confirmed_orders.paginated') }}">
                <span class="icon"><i class="fa-solid fa-square-check"></i></i></span>
                <span class="menu-item-label">Confirmed Orders</span>
            </a>
        </li>
        <li>
            <a href="{{ route('on_preparation_orders.paginated') }}">
                <span class="icon"><span class="mdi mdi-grill-outline"></span></span>
                <span class="menu-item-label">On Preparation Orders</span>
            </a>
        </li>
        <li>
            <a href="{{ route('for_delivery_orders.paginated') }}">
                <span class="icon"><i class="fa-solid fa-truck-fast"></i></span>
                <span class="menu-item-label">For Delivery Orders</span>
            </a>
        </li>
        <li>
            <a href="{{ route('faq.index') }}">
                <span class="icon"><i class="fa-solid fa-circle-question"></i></span>
                <span class="menu-item-label">FAQs</span>
            </a>
        </li>
        <li>
            <a href="{{ route('promotions.index') }}">
                <span class="icon"><i class="fa-solid fa-rectangle-ad"></i></span>
                <span class="menu-item-label">Promotions</span>
            </a>
        </li>
        <li>
            <a href="{{ route('for_delivery_orders.paginated') }}">
                <span class="icon"><i class="fa-solid fa-comment-dots"></i></i></span>
                <span class="menu-item-label">Feedbacks</span>
            </a>
        </li>
    </ul>
</div>