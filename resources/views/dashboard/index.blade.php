@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  {{-- HEADER --}}
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">Dashboard Admin</h4>
      <small class="text-muted">Ringkasan data & aktivitas terbaru</small>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('guru.index') }}" class="btn btn-sm btn-primary">
        <i class="bx bx-user-plus"></i> Tambah Guru
      </a>
      <a href="{{ route('program.index') }}" class="btn btn-sm btn-success">
        <i class="bx bx-book-add"></i> Tambah Program
      </a>
    </div>
  </div>

  {{-- ================= STAT CARDS ================= --}}
  <div class="row g-3 mb-4">

    @php
      $cards = [
        ['title'=>'Guru','count'=>$guru,'icon'=>'bx-user','bg'=>'bg-primary'],
        ['title'=>'Program','count'=>$program,'icon'=>'bx-book-open','bg'=>'bg-success'],
        ['title'=>'Kelas','count'=>$kelas,'icon'=>'bx-chalkboard','bg'=>'bg-info'],
        ['title'=>'Fasilitas','count'=>$fasilitas,'icon'=>'bx-building-house','bg'=>'bg-warning'],
        ['title'=>'Pendaftaran','count'=>$pendaftaran,'icon'=>'bx-clipboard','bg'=>'bg-danger'],
        ['title'=>'Testimoni','count'=>$testimoni,'icon'=>'bx-chat','bg'=>'bg-secondary'],
      ];
    @endphp

    @foreach($cards as $c)
      <div class="col-xl-2 col-md-4 col-6">
        <div class="card stat-card {{ $c['bg'] }} text-white h-100">
          <div class="card-body d-flex justify-content-between align-items-center">

            <div>
              <h4 class="fw-bold mb-0">{{ $c['count'] }}</h4>
              <small>{{ $c['title'] }}</small>

              @if($c['title']==='Kelas' && $c['count']==0)
                <div class="mt-1">
                  <span class="badge bg-light text-dark">Belum dibuat</span>
                </div>
              @endif
            </div>

            <i class="bx {{ $c['icon'] }} fs-1 opacity-50"></i>
          </div>
        </div>
      </div>
    @endforeach

  </div>

  {{-- ================= CONTENT 50 : 50 ================= --}}
  <div class="row g-3">

    {{-- KEGIATAN --}}
    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Kegiatan Terbaru</h5>
          <i class="bx bx-calendar text-primary"></i>
        </div>

        <div class="card-body p-2 activity-scroll">

          @forelse($kegiatan as $k)
            <div class="d-flex align-items-center p-2 mb-2 rounded activity-item">
              <img
                src="{{ $k->image_url ?? 'https://via.placeholder.com/70x60' }}"
                class="rounded me-3"
                style="width:70px;height:60px;object-fit:cover"
              >
              <div class="flex-grow-1">
                <h6 class="mb-0">{{ $k->title }}</h6>
                <small class="text-muted">
                  {{ \Carbon\Carbon::parse($k->date)->format('d M Y') }}
                </small>
              </div>
              <i class="bx bx-chevron-right text-muted"></i>
            </div>
          @empty
            <div class="text-center text-muted py-5">
              <i class="bx bx-calendar-x fs-1 mb-2"></i>
              <p>Belum ada kegiatan</p>
            </div>
          @endforelse

        </div>
      </div>
    </div>

    {{-- MAPS --}}
    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-header">
          <h5 class="mb-0">Lokasi Lembaga</h5>
        </div>
        <div class="card-body p-2">
          <iframe
            class="rounded w-100 h-100"
            src="https://www.google.com/maps?q=Lembaga+Pendidikan+Al-Qur'an+Darussalam&output=embed"
            style="border:0;min-height:320px"
            loading="lazy">
          </iframe>
        </div>
      </div>
    </div>

  </div>

</div>

{{-- STYLE --}}
<style>
  .stat-card {
    border: none;
    transition: .25s ease;
  }
  .stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0,0,0,.15);
  }
  .activity-scroll {
    max-height: 320px;
    overflow-y: auto;
  }
  .activity-item {
    background: #f8f9fa;
    transition: .2s;
  }
  .activity-item:hover {
    background: #eef1f5;
  }
</style>
@endsection
