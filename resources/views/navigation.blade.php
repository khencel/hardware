<ul class="nav-links">
    @php
        $userRoles = auth()->user()->roles()->pluck('role_id')->toArray();
    @endphp

    @php
        $routes = [
            [
                'url' => '/food-categories',
                'name' => 'food-categories',
                'label' => 'Item Categories',
                'icon' => 'bx bx-food-menu',
                'roles' => [12],
            ],
            [
                'url' => '/foods',
                'name' => 'foods',
                'label' => 'Item',
                'icon' => 'bx bx-food-menu',
                'roles' => [12],
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
