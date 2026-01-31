<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  <!-- Brand -->
  <div class="app-brand demo">
    <a href="{{ url('/') }}" class="app-brand-link">
      <span class="app-brand-logo demo">
        <span class="text-primary">
          <!-- Logo SVG -->
          <svg width="25" viewBox="0 0 25 42" xmlns="http://www.w3.org/2000/svg">
            <path fill="currentColor"
              d="M13.79.36L3.4 7.44C.57 9.69-.38 12.48.56 15.8c.13.43.54 1.99 2.56 3.43.69.49 2.2 1.15 4.53 1.98l-.05.04-4.96 3.3C.45 26.3.09 28.51 1.56 31.17c1.27 1.65 3.64 2.09 5.53 1.37 1.25-.48 4.36-2.54 9.33-6.17 1.62-1.88 2.28-3.92 1.99-6.14-.44-2.7-2.23-4.66-5.36-5.86l-2.13-.9 7.7-5.49L13.79.36z"/>
          </svg>
        </span>
      </span>
      <span class="app-brand-text demo menu-text fw-bold ms-2">Sneat</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="bx bx-chevron-left d-block d-xl-none align-middle"></i>
    </a>
  </div>

  <div class="menu-divider mt-0"></div>
  <div class="menu-inner-shadow"></div>

  <!-- Menu -->
  <ul class="menu-inner py-1">

    <!-- Dashboard -->
    <li class="menu-item {{ request()->is('/') ? 'active' : '' }}">
      <a href="{{ url('/') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-smile"></i>
        <div class="text-truncate">Dashboard</div>
      </a>
    </li>

    <!-- Program / Kelas -->
    <li class="menu-item {{ request()->is('program*') ? 'active' : '' }}">
      <a href="{{ route('program.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-book-open"></i>
        <div class="text-truncate">Program / Kelas</div>
      </a>
    </li>

    <!-- Contoh menu lain (opsional, bisa kamu hapus kalau belum dipakai) -->
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Manajemen</span>
    </li>

    <li class="menu-item">
      <a href="#" class="menu-link">
        <i class="menu-icon tf-icons bx bx-user"></i>
        <div class="text-truncate">Guru</div>
      </a>
    </li>

    <li class="menu-item">
      <a href="{{ route('fasilitas.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-building-house"></i>
        <div class="text-truncate">Fasilitas</div>
      </a>
    </li>

    <li class="menu-item">
      <a href="#" class="menu-link">
        <i class="menu-icon tf-icons bx bx-clipboard"></i>
        <div class="text-truncate">Pendaftaran</div>
      </a>
    </li>

  </ul>
</aside>
