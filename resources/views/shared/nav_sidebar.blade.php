<div class="menu is-menu-main py-2 bg-black-erie">
    @php $permissions = Session::get('permissions'); @endphp
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
        @if (isset($permissions['manage_administration']) && $permissions['manage_administration'])
        <li class="--set-active-tables-html">
            <a class="dropdown">
                <span class="icon"><i class="mdi mdi-account-multiple"></i></span>
                <span class="menu-item-label">Manage Roles/Staff</span>
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
        @endif

        @if (isset($permissions['manage_menu']) && $permissions['manage_menu'])
        <li class="--set-active-forms-html">
            <a href="{{ route('menu-items') }}">
                <span class="icon"><i class="fa-solid fa-burger"></i></span>
                <span class="menu-item-label">Menu Items</span>
            </a>
        </li>
        @endif

        @if (isset($permissions['manage_category']) && $permissions['manage_category'])
        <li>
            <a href="{{ route('categories.index') }}">
                <span class="icon"><i class="fa-solid fa-layer-group"></i></span>
                <span class="menu-item-label">Category</span>
            </a>
        </li>
        @endif

        @if (isset($permissions['manage_archives']) && $permissions['manage_archives'])
        <li class="--set-active-profile-html">
            <a href="{{ route('archive.index') }}">
                <span class="icon">
                    <i class="fa-solid fa-box-archive"></i>
                </span>
                <span class="menu-item-label">Archives</span>
            </a>
        </li>
        @endif

        @if (isset($permissions['manage_pending_orders']) && $permissions['manage_pending_orders'])
        <li>
            <a href="{{ route('pending_orders.paginated') }}">
                <span class="icon"><i class="fa-solid fa-clock"></i></span>
                <span class="menu-item-label">Pending Orders</span>
            </a>
        </li>
        @endif

        @if (isset($permissions['manage_confirmed_orders']) && $permissions['manage_confirmed_orders'])
        <li>
            <a href="{{ route('confirmed_orders.paginated') }}">
                <span class="icon"><i class="fa-solid fa-square-check"></i></i></span>
                <span class="menu-item-label">Confirmed Orders</span>
            </a>
        </li>
        @endif

        @if (isset($permissions['manage_on_preparation_orders']) && $permissions['manage_on_preparation_orders'])
        <li>
            <a href="{{ route('on_preparation_orders.paginated') }}">
                <span class="icon"><span class="mdi mdi-grill-outline"></span></span>
                <span class="menu-item-label">On Preparation Orders</span>
            </a>
        </li>
        @endif

        @if (isset($permissions['manage_for_delivery_orders']) && $permissions['manage_for_delivery_orders'])
        <li>
            <a href="{{ route('for_delivery_orders.paginated') }}">
                <span class="icon"><i class="fa-solid fa-truck-fast"></i></span>
                <span class="menu-item-label">For Delivery Orders</span>
            </a>
        </li>
        @endif

        @if (isset($permissions['manage_for_cancelled_orders']) && $permissions['manage_for_cancelled_orders'])
        <li>
            <a href="{{ route('cancelled_orders.paginated') }}">
                <span class="icon"><i class="fa-solid fa-xmark"></i></span>
                <span class="menu-item-label">Cancelled Orders</span>
            </a>
        </li>
        @endif

        @if (isset($permissions['manage_messages']) && $permissions['manage_messages'])
        <li>
            <a href="{{ route('chats.show') }}">
                <span class="icon"><i class="fa-solid fa-comments"></i></span>
                <span class="menu-item-label">Messages</span>
            </a>
        </li>
        @endif

        @if (isset($permissions['manage_feedback']) && $permissions['manage_feedback'])
        <li>
            <a href="{{ route('feedbacks.show') }}">
                <span class="icon"><i class="fa-solid fa-comment-dots"></i></span>
                <span class="menu-item-label">Feedbacks</span>
            </a>
        </li>
        @endif

        @if (isset($permissions['manage_promotions']) && $permissions['manage_promotions'])
        <li>
            <a href="{{ route('promotions.index') }}">
                <span class="icon"><i class="fa-solid fa-rectangle-ad"></i></span>
                <span class="menu-item-label">Promotions</span>
            </a>
        </li>
        @endif

        @if (isset($permissions['manage_faqs']) && $permissions['manage_faqs'])
        <li>
            <a href="{{ route('faq.index') }}">
                <span class="icon"><i class="fa-solid fa-circle-question"></i></span>
                <span class="menu-item-label">FAQs</span>
            </a>
        </li>
        @endif
    </ul>
</div>