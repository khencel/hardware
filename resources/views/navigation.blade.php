<ul class="nav-links">
    @php
        $userRoles = auth()->user()->roles()->pluck('role_id')->toArray();
    @endphp

    @php
        $routes = [
            // [
            //     'url' => '/dashboard',
            //     'name' => 'cms.dashboard',
            //     'label' => 'Dashboard',
            //     'icon' => 'bx bx-grid-alt',
            //     'roles' => [12, 2, 11],
            // ],
            // [
            //     'url' => '/user-management',
            //     'name' => 'user-management',
            //     'label' => 'User Management',
            //     'roles' => [],
            //     'icon' => 'bx bx-user',
            // ],
            // [
            //     'url' => '/room-category',
            //     'name' => 'room-category',
            //     'label' => 'Room Category',
            //     'icon' => 'bx bx-box',
            //     'roles' => [12, 11],
            // ],
            // [
            //     'url' => '/rooms',
            //     'name' => 'rooms',
            //     'label' => 'Rooms',
            //     'icon' => 'bx bx-list-ul',
            //     'roles' => [12, 11],
            // ],
            // [
            //     'url' => '/package',
            //     'name' => 'package',
            //     'label' => 'Package',
            //     'icon' => 'bx bx-pie-chart-alt-2',
            //     'roles' => [12, 11],
            // ],
            // [
            //     'url' => '/leisures-add-ons',
            //     'name' => 'leisures-add-ons',
            //     'label' => 'Leisures/Add-ons',
            //     'icon' => 'bx bx-coin-stack',
            //     'roles' => [12, 11],
            // ],
            // [
            //     'url' => '/booking',
            //     'name' => 'booking',
            //     'label' => 'Booking',
            //     'icon' => 'bx bx-heart',
            //     'roles' => [12, 2, 11],
            // ],
            [
                'url' => '/food-categories',
                'name' => 'food-categories',
                'label' => 'Item Categories',
                'icon' => 'bx bx-food-menu',
                'roles' => [],
            ],
            [
                'url' => '/foods',
                'name' => 'foods',
                'label' => 'Item',
                'icon' => 'bx bx-food-menu',
                'roles' => [],
            ],
            [
                'url' => '/customers',
                'name' => 'customers',
                'label' => 'Customers',
                'icon' => 'bx bx-user',
                'roles' => [], // Adjust roles as needed
            ],
            [
                'url' => '/pos',
                'name' => 'POS',
                'label' => 'Point of Sale',
                'icon' => 'bx bx-calculator',
                'roles' => [],
            ],
            [
                'url' => '/users',
                'name' => 'user-management',
                'label' => 'User Management',
                'icon' => 'bx bx-user-circle',
                'roles' => [],
            ],
            [
                'url' => '/reports',
                'name' => 'reports',
                'label' => 'Reports',
                'icon' => 'bx bxs-report',
                'roles' => [],
            ],
            // [
            //     'url' => '/payment-methods',
            //     'name' => 'payment-methods',
            //     'label' => 'Payment Methods',
            //     'icon' => 'bx bx-calculator',
            //     'roles' => [12, 11],
            // ],
            // [
            //     'url' => '/inventories',
            //     'name' => 'inventories',
            //     'label' => 'Inventories',
            //     'icon' => 'bx bx-box',
            //     'roles' => [12, 11],
            // ],
            // [
            //     'url' => '/discounts',
            //     'name' => 'discounts',
            //     'label' => 'Discounts',
            //     'icon' => 'bx bx-purchase-tag',
            //     'roles' => [12, 11],
            // ],
        ];
    @endphp

    @foreach ($routes as $route)
        @if (collect($userRoles)->intersect($route['roles'])->isNotEmpty() || collect($route['roles'])->isEmpty())
            <li>
                <a href="{{ url($route['url']) }}" class="{{ request()->is(ltrim($route['url'], '/')) ? 'active' : '' }}">
                    <i class='{{ $route['icon'] }}'></i>
                    <span class="links_name">{{ $route['label'] }}</span>
                </a>
            </li>
        @endif
    @endforeach

    <form action="{{ route('auth.logout') }}" method="POST" id="logoutForm">
        @csrf
        @method('POST')
        <li class="log_out">
            <!-- Logout Form -->


            <a href="#">
                <button type="submit" style="background: none; border: none; padding: 0;">
                    <i class='bx bx-log-out'></i>
                    <span class="links_name">Log out</span>
                </button>
            </a>

        </li>
    </form>
</ul>
