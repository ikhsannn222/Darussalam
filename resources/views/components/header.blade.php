@php
    use Illuminate\Support\Facades\Auth;

    $name = Auth::user()->name ?? 'User';
    $role = Auth::user()->role ?? 'user';

    // Ambil inisial (maks 2 huruf)
    $initials = collect(explode(' ', $name))
        ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
        ->take(2)
        ->join('');

    // Warna avatar otomatis & konsisten
    $colors = ['#696cff', '#ff9f43', '#28c76f', '#ea5455', '#00cfe8'];
    $color = $colors[crc32($name) % count($colors)];
@endphp

<nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
    id="layout-navbar">

    <!-- Toggle Mobile -->
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="icon-base bx bx-menu icon-md"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">

        <!-- Search -->
        <div class="navbar-nav align-items-center me-auto">
            <div class="nav-item d-flex align-items-center">
                <i class="icon-base bx bx-search icon-md me-2"></i>
                <input type="text"
                    class="form-control border-0 shadow-none d-none d-md-block"
                    placeholder="Search..."
                    aria-label="Search" />
            </div>
        </div>
        <!-- /Search -->

        <ul class="navbar-nav flex-row align-items-center ms-md-auto">

            <!-- User Dropdown -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0"
                   href="javascript:void(0);"
                   data-bs-toggle="dropdown">

                    <!-- Avatar (Inisial) -->
                    <div class="avatar avatar-online">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                            style="width:40px;height:40px;background:{{ $color }};">
                            {{ $initials }}
                        </div>
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end">

                    <!-- User Info -->
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                                            style="width:40px;height:40px;background:{{ $color }};">
                                            {{ $initials }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $name }}</h6>
                                    <small class="text-body-secondary">
                                        {{ ucfirst($role) }}
                                    </small>
                                </div>
                            </div>
                        </a>
                    </li>

                    <li><div class="dropdown-divider my-1"></div></li>

                    <!-- Profile -->
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="icon-base bx bx-user icon-md me-3"></i>
                            <span>My Profile</span>
                        </a>
                    </li>

                    <!-- Settings -->
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="icon-base bx bx-cog icon-md me-3"></i>
                            <span>Settings</span>
                        </a>
                    </li>

                    <li><div class="dropdown-divider my-1"></div></li>

                    <!-- Logout -->
                    <li>
                        <form method="POST" action="{{ route('logout') }}"
                              onsubmit="return confirm('Yakin ingin logout?')">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="icon-base bx bx-power-off icon-md me-3"></i>
                                <span>Log Out</span>
                            </button>
                        </form>
                    </li>

                </ul>
            </li>
            <!-- /User -->

        </ul>
    </div>
</nav>
