@extends('layouts.app')

@section('title', 'Lokasi LPQ Darussalam')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="mb-3">Lokasi LPQ Darussalam</h4>

    <div class="card">
        <div class="card-body">

            <p class="text-muted mb-3">
                Lembaga Pendidikan Al-Qur'an Darussalam
                (TKA, TPA, TQA, Tahfidz)
                <br>
                Cangkuang Kulon, Kabupaten Bandung, Jawa Barat
            </p>

            <div class="ratio ratio-16x9 rounded overflow-hidden">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.985699184884!2d107.5859356!3d-6.9740124!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e9b1da033491%3A0x669c97764e75e75d!2sLembaga%20Pendidikan%20Al-Qur&#39;an%20DARUSSALAM!5e0!3m2!1sid!2sid!4v1707060000000"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

        </div>
    </div>

</div>
@endsection
