<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  {{-- BRAND --}}
  <div class="app-brand demo">
    <a href="{{ url('/') }}" class="app-brand-link">
      <span class="app-brand-logo demo text-primary">
        <svg width="25" viewBox="0 0 25 42" xmlns="http://www.w3.org/2000/svg">
          <path fill="currentColor"
            d="M13.79.36L3.4 7.44C.57 9.69-.38 12.48.56 15.8c.13.43.54 1.99 2.56 3.43
            .69.49 2.2 1.15 4.53 1.98l-.05.04-4.96 3.3C.45 26.3.09 28.51
            1.56 31.17c1.27 1.65 3.64 2.09 5.53 1.37
            1.25-.48 4.36-2.54 9.33-6.17
            1.62-1.88 2.28-3.92 1.99-6.14
            -.44-2.7-2.23-4.66-5.36-5.86
            l-2.13-.9 7.7-5.49L13.79.36z"/>
        </svg>
      </span>
      <span class="app-brand-text demo menu-text fw-bold ms-2">Darussalam</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="bx bx-chevron-left d-block d-xl-none align-middle"></i>
    </a>
  </div>

  <div class="menu-divider mt-0"></div>
  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">

    {{-- ================= DASHBOARD ================= --}}
    <li class="menu-item {{ request()->is('/') ? 'active' : '' }}">
      <a href="{{ route('dashboard.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-smile"></i>
        <div>Dashboard</div>
      </a>
    </li>

    {{-- ================= AKADEMIK ================= --}}
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Akademik</span>
    </li>

    <li class="menu-item {{ request()->is('program*') ? 'active' : '' }}">
      <a href="{{ route('program.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-book-open"></i>
        <div>Program / Kelas</div>
      </a>
    </li>

    <li class="menu-item {{ request()->is('guru*') ? 'active' : '' }}">
      <a href="{{ route('guru.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-user"></i>
        <div>Guru</div>
      </a>
    </li>

    <li class="menu-item {{ request()->is('pendaftaran*') ? 'active' : '' }}">
      <a href="{{ route('pendaftaran.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-clipboard"></i>
        <div>Pendaftaran</div>
      </a>
    </li>

    {{-- ================= KONTEN ================= --}}
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Konten Website</span>
    </li>

    <li class="menu-item {{ request()->is('kegiatan*') ? 'active' : '' }}">
      <a href="{{ route('kegiatan.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-calendar-event"></i>
        <div>Kegiatan</div>
      </a>
    </li>

    <li class="menu-item {{ request()->is('testimoni*') ? 'active' : '' }}">
      <a href="{{ route('testimoni.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-chat"></i>
        <div>Testimoni</div>
      </a>
    </li>

    <li class="menu-item {{ request()->is('fasilitas*') ? 'active' : '' }}">
      <a href="{{ route('fasilitas.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-building-house"></i>
        <div>Fasilitas</div>
      </a>
    </li>

    {{-- ================= PENGATURAN ================= --}}
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Pengaturan</span>
    </li>

    <li class="menu-item {{ request()->is('maps*') ? 'active' : '' }}">
      <a href="{{ route('maps.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-map"></i>
        <div>Lokasi & Peta</div>
      </a>
    </li>

     <li class="menu-item {{ request()->is('maps*') ? 'active' : '' }}">
      <a href="{{ route('prestasi.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-map"></i>
        <div>Prestasi</div>
      </a>
    </li>

  </ul>
</aside>
