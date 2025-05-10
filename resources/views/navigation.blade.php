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
                'url' => '/users',
                'name' => 'user-management',
                'label' => 'User Management',
                'icon' => 'bx bx-user-circle',
                'roles' => [12],
            ],
            [
                'url' => '/reports',
                'name' => 'reports',
                'label' => 'Reports',
                'icon' => 'bx bxs-report',
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
                        'url' => '/food-categories',
                        'name' => 'food-categories',
                        'label' => 'Categories',
                        'icon' => 'bx bx-food-menu',
                        'roles' => [],
                    ],
                    [
                        'url' => '/food-categories',
                        'name' => 'food-categories',
                        'label' => 'Categories',
                        'icon' => 'bx bx-food-menu',
                        'roles' => [],
                    ],
                ],
            ],
        ];
    @endphp

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
