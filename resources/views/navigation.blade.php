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
                'url' => '/pos',
                'name' => 'POS',
                'label' => 'Point of Sale',
                'icon' => 'bx bx-calculator',
                'roles' => [],
            ],
            [
                'url' => '/items',
                'name' => 'item',
                'label' => 'Item',
                'icon' => 'bx bx-shopping-bag',
                'roles' => [],
                'children' => [
                    [
                        'url' => '/food-categories',
                        'name' => 'food-categories',
                        'label' => 'Categories',
                        'icon' => 'bx bxs-area',
                        'roles' => [],
                    ],
                    [
                        'url' => '/foods',
                        'name' => 'foods',
                        'label' => 'List',
                        'icon' => 'bx bx-list-ol',
                        'roles' => [],
                    ],
                ]
            ],
            [
                'url' => '/Orders',
                'name' => 'Orders',
                'label' => 'Orders',
                'icon' => 'bx bx-food-menu',
                'roles' => [],
                'children' => [
                    [
                        'url' => '/hold-orders',
                        'name' => 'hold-orders',
                        'label' => 'Hold',
                        'icon' => 'bx bx-purchase-tag-alt',
                        'roles' => [12],
                    ],
                    [
                        'url' => '/quotation-orders',
                        'name' => 'quotation-orders',
                        'label' => 'Quotation',
                        'icon' => 'bx bxs-quote-right',
                        'roles' => [12],
                    ],
                    [
                        'url' => '/reports',
                        'name' => 'reports',
                        'label' => 'Reports',
                        'icon' => 'bx bxs-report',
                        'roles' => [],
                    ],
                ]
            ],
            [
                'url' => '/users',
                'name' => 'user-management',
                'label' => 'User Control',
                'icon' => 'bx bx-group',
                'roles' => [12],
                'children' => [
                    [
                        'url' => '/users',
                        'name' => 'user-management',
                        'label' => 'User ',
                        'icon' => 'bx bx-user-circle',
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
                        'url' => '/drivers',
                        'name' => 'drivers',
                        'label' => 'Drivers',
                        'icon' => 'bx bx-smile',
                        'roles' => [12],
                    ],
                ]
            ],
            [
                'url' => '/settings',
                'name' => 'settings',
                'label' => 'Settings',
                'icon' => 'bx bx-cog',
                'roles' => [12],
                'children' => [
                   
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
                        'url' => '/activity-log',
                        'name' => 'activity-log',
                        'label' => 'Activity Log',
                        'icon' => 'bx bx-history',
                        'roles' => [],
                    ],
                    [
                        'url' => '/option',
                        'name' => 'option',
                        'label' => 'options',
                        'icon' => 'bx bx-cog',
                        'roles' => [12],
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
                                        <span class="links_name">
                                            {{ $child['label'] }}

                                            @php
                                                $badgeMap = [
                                                    'hold-orders' => 'hold-count',
                                                    'quotation-orders' => 'quotation-count',
                                                    'foods' => 'list-count',
                                                    'food-categories' => 'list-categories',
                                                    'customers' => 'customer-count',
                                                    'user-management' => 'user-count',
                                                    'drivers' => 'driver-count',
                                                    'discounts' => 'discount-count',
                                                    'taxes' => 'tax-count',
                                                ];
                                            @endphp

                                            @if (isset($badgeMap[$child['name']]))
                                                <span class="badge {{ $badgeMap[$child['name']] }}" style="display:none;">0</span>
                                            @endif
                                        </span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            </li>
        @endif
    @endforeach

</ul>

    <script>
        async function fetchCountsAndUpdateBadges() {
        try {
            const response = await fetch('/api/counts');
            if (!response.ok) throw new Error('Failed to fetch counts');
    
            const result = await response.json();
            if (!result.status) throw new Error('API returned an error');
    
            const counts = result.data;
    
            // Map badge classes to their count keys
            const badgeMapping = {
                'hold-count': counts.countHold,
                'quotation-count': counts.countQuotation,
                'list-count': counts.countFood,
                'list-categories': counts.countCategory,
                'customer-count': counts.countCustomer,
                'driver-count': counts.countDriver,
                'discount-count': counts.countDiscount,
                'tax-count': counts.countTax,
                'user-count': counts.countUser,
            };
    
            Object.entries(badgeMapping).forEach(([className, count]) => {
            document.querySelectorAll(`.badge.${className}`).forEach(span => {
                span.textContent = count ?? 0;
                span.style.display = count > 0 ? 'inline-block' : 'none';
            });
            });
        } catch (error) {
            console.error('Error fetching counts:', error);
        }
        }
    
        document.addEventListener('DOMContentLoaded', fetchCountsAndUpdateBadges);
    </script>
  
    