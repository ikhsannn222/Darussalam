<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Schema;

// Models yang SUDAH ADA
use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Program;
use App\Models\Fasilitas;
use App\Models\Pendaftaran;
use App\Models\Testimoni;
use App\Models\Kegiatan;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            // ===== STAT COUNT =====
            'guru'        => Schema::hasTable('gurus') ? Guru::count() : 0,
            'program'     => Schema::hasTable('programs') ? Program::count() : 0,

            // KELAS BELUM ADA → AMAN
            'kelas'       => 0,

            'fasilitas'   => Schema::hasTable('facilities') ? Fasilitas::count() : 0,
            'pendaftaran' => Schema::hasTable('pendaftarans') ? Pendaftaran::count() : 0,
            'testimoni'   => Schema::hasTable('testimonis') ? Testimoni::count() : 0,

            // ===== KEGIATAN TERBARU =====
            'kegiatan' => Schema::hasTable('kegiatans')
                ? Kegiatan::latest()->take(5)->get()
                : collect(),
        ]);
    }
}
