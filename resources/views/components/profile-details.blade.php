<div class="dropdown profile-details">
    <a href="#" class="d-flex align-items-center dropdown-toggle text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="{{ auth()->user()->image ? asset('storage/' . auth()->user()->image) : 'https://media.licdn.com/dms/image/v2/C5603AQF1WA6mvPPN7g/profile-displayphoto-shrink_400_400/profile-displayphoto-shrink_400_400/0/1655827862331?e=2147483647&v=beta&t=A0HGyBn7tNazpYnwQoiEMf4K_-fa9AZXAOLuQ-wXg0A' }}" 
             alt="Profile" class="rounded-circle border border-2 border-primary shadow-sm" width="40" height="40">
        <span class="ms-2 admin_name fw-semibold text-dark">
            {{ auth()->check() ? auth()->user()->firstname . ' ' . auth()->user()->lastname : '' }}
        </span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end shadow-sm mt-2 rounded-3">
        <li>
            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('profile.view') }}">
                <i class="bi bi-person-circle"></i> Edit Profile
            </a>
        </li>
        <li>
            <a class="dropdown-item d-flex align-items-center gap-2"  href="{{ route('password.change') }}">
                <i class="bi bi-shield-lock-fill"></i> Change Password
            </a>
        </li>
    </ul>
</div>
