<ul class="nav-links">
    @php
        $userRoles = auth()->user()->roles()->pluck('role_id')->toArray();

        $routes = [
            [
                'url' => '/dashboard',
                'name' => 'dashboard',
                'label' => 'Dashboard',
                'icon' => 'bx bx-grid-alt',
                'roles' => [],
            ],
            [
                'url' => '/items',
                'name' => 'item',
                'label' => 'Item Details',
                'icon' => 'bx bx-food-menu',
                'roles' => [],
                'children' => [
                    [
                        'url' => '/food-categories',
                        'name' => 'food-categories',
                        'label' => 'Categories',
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
                ]
            ],
            [
                'url' => '/customers',
                'name' => 'customers',
                'label' => 'Customers',
                'icon' => 'bx bx-user',
                'roles' => [12],
            ],
            [
                'url' => '/pos',
                'name' => 'POS',
                'label' => 'Point of Sale',
                'icon' => 'bx bx-calculator',
                'roles' => [],
            ],
            [
                'url' => '/hold-orders',
                'name' => 'hold-orders',
                'label' => 'Hold Orders',
                'icon' => 'bx bx-purchase-tag-alt',
                'roles' => [12],
            ],
            [
                'url' => '/users',
                'name' => 'user-management',
                'label' => 'User Management',
                'icon' => 'bx bx-user-circle',
                'roles' => [12],
            ],
            [
                'url' => '/settings',
                'name' => 'settings',
                'label' => 'Settings',
                'icon' => 'bx bx-cog',
                'roles' => [12],
                'children' => [
                    [
                        'url' => '/drivers',
                        'name' => 'drivers',
                        'label' => 'Drivers',
                        'icon' => 'bx bx-smile',
                        'roles' => [],
                    ],
                    [
                        'url' => '/discounts',
                        'name' => 'discounts',
                        'label' => 'Discount',
                        'icon' => 'bx bxs-discount',
                        'roles' => [],
                    ],
                    [
                        'url' => '/taxes',
                        'name' => 'taxes',
                        'label' => 'Tax',
                        'icon' => 'bx bx-infinite',
                        'roles' => [],
                    ],
                    [
                        'url' => '/reports',
                        'name' => 'reports',
                        'label' => 'Reports',
                        'icon' => 'bx bxs-report',
                        'roles' => [],
                    ],
                    [
                        'url' => '/activity-log',
                        'name' => 'activity-log',
                        'label' => 'Activity Log',
                        'icon' => 'bx bx-history',
                        'roles' => [],
                    ],
                ],
            ],
        ];
    @endphp

    {{--  https://themesbrand.com/skote-symfony/layouts/icons-boxicons.html  --}}

    @foreach ($routes as $route)
        @if (empty($route['roles']) || collect($userRoles)->intersect($route['roles'])->isNotEmpty())
            <li class="{{ !empty($route['children']) ? 'has-submenu' : '' }}">
                <a href="{{ empty($route['children']) ? url($route['url']) : '#' }}" 
                   class="menu-link {{ request()->is(ltrim($route['url'], '/')) ? 'active' : '' }}">
                    <i class="{{ $route['icon'] }}"></i>
                    <span class="links_name">{{ $route['label'] }}</span>
                    @if (!empty($route['children']))
                        <i class='bx bx-chevron-down arrow'></i>
                    @endif
                </a>

                @if (!empty($route['children']))
                    <ul class="submenu">
                        @foreach ($route['children'] as $child)
                            @if (empty($child['roles']) || collect($userRoles)->intersect($child['roles'])->isNotEmpty())
                                <li>
                                    <a href="{{ url($child['url']) }}" 
                                    class="{{ request()->is(ltrim($child['url'], '/')) ? 'active' : '' }}">
                                        <i class="{{ $child['icon'] ?? 'bx bx-dot' }}"></i>
                                        <span class="links_name">{{ $child['label'] }}</span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            
            </li>
        @endif
    @endforeach

    {{-- Logout --}}
    <form action="{{ route('auth.logout') }}" method="POST" id="logoutForm">
        @csrf
        @method('POST')
        <li class="log_out">
            <a href="#" onclick="document.getElementById('logoutForm').submit(); return false;">
                <i class='bx bx-log-out'></i>
                <span class="links_name">Log out</span>
            </a>
        </li>
    </form>
</ul>
