<ul class="nav-links">
    <li>
        <a href="{{ url('/user-management') }}" class="{{ request()->is('user-management') ? 'active' : '' }}">
            <i class='bx bx-user' style="color: black"></i>
            <span class="links_name">All Categories</span>
        </a>
    </li>
    <li>
        <a href="{{ url('/room-category') }}" class="{{ request()->is('room-category') ? 'active' : '' }}">
            <i class='bx bx-box' style="color: black"></i>
            <span class="links_name">Burger</span>
        </a>
    </li>
    <li>
        <a href="{{ url('/rooms') }}" class="{{ request()->is('rooms') ? 'active' : '' }}">
            <i class='bx bx-list-ul' style="color: black"></i>
            <span class="links_name">Sandwiches</span>
        </a>
    </li>
    <li>
        <a href="{{ url('/package') }}" class="{{ request()->is('package') ? 'active' : '' }}">
            <i class='bx bx-pie-chart-alt-2' style="color: black"></i>
            <span class="links_name">Snacks</span>
        </a>
    </li>
    <li>
        <a href="{{ url('/leisures-add-ons') }}" class="{{ request()->is('leisures-add-ons') ? 'active' : '' }}">
            <i class='bx bx-coin-stack' style="color: black"></i>
            <span class="links_name">Beverages</span>
        </a>
    </li>
    <li>
        <a href="{{ url('/booking') }}" class="{{ request()->is('POS') ? 'active' : '' }}">
            <i class='bx bx-heart' style="color: black"></i>
            <span class="links_name">Burritos</span>
        </a>
    </li>
   
    <li class="log_out">
        <form action="{{ route('auth.logout') }}" method="POST" id="logoutForm">
            @csrf
            @method('POST') 
            <button type="submit" style="background: none; border: none; padding: 0;">
                <a href="#">
                    <i class='bx bx-log-out' style="color: black"></i>
                    <span class="links_name">Log out</span>
                </a>
            </button>
        </form>
    </li>
</ul>
