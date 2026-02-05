<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatans = DB::table('kegiatans')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($k) {
                $k->image_url = $k->image
                    ? asset('storage/' . $k->image)
                    : null;
                return $k;
            });

        return view('kegiatan.index', compact('kegiatans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'date'        => 'nullable|date',
            'description' => 'nullable',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('kegiatan-images', 'public');
        }

        DB::table('kegiatans')->insert([
            'title'       => $validated['title'],
            'date'        => $validated['date'] ?? null,
            'description' => $validated['description'] ?? null,
            'image'       => $imagePath,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return redirect()->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil ditambahkan');
    }

    public function show($id)
    {
        $kegiatan = DB::table('kegiatans')->where('id', $id)->first();
        if (!$kegiatan) abort(404);

        $kegiatan->image_url = $kegiatan->image
            ? asset('storage/' . $kegiatan->image)
            : null;

        return response()->json($kegiatan);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'date'        => 'nullable|date',
            'description' => 'nullable',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $kegiatan = DB::table('kegiatans')->where('id', $id)->first();
        if (!$kegiatan) abort(404);

        $imagePath = $kegiatan->image;

        if ($request->hasFile('image')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('kegiatan-images', 'public');
        }

        DB::table('kegiatans')->where('id', $id)->update([
            'title'       => $validated['title'],
            'date'        => $validated['date'] ?? null,
            'description' => $validated['description'] ?? null,
            'image'       => $imagePath,
            'updated_at'  => now(),
        ]);

        return redirect()->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $kegiatan = DB::table('kegiatans')->where('id', $id)->first();
        if (!$kegiatan) abort(404);

        if ($kegiatan->image && Storage::disk('public')->exists($kegiatan->image)) {
            Storage::disk('public')->delete($kegiatan->image);
        }

        DB::table('kegiatans')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kegiatan berhasil dihapus'
        ]);
    }
}
