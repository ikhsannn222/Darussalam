<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PrestasiController extends Controller
{
    public function index()
    {
        $prestasi = DB::table('prestasises')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($p) {
                $p->image_url = $p->image
                    ? asset('storage/' . $p->image)
                    : null;
                return $p;
            });

        return view('prestasi.index', compact('prestasi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'tanggal'            => 'required|date',
            'tingkat_kejuaraan'  => 'required|string|max:255',
            'description'        => 'required',
            'image'              => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('prestasi-images', 'public');
        }

        DB::table('prestasises')->insert([
            'name'               => $validated['name'],
            'tanggal'            => $validated['tanggal'],
            'tingkat_kejuaraan'  => $validated['tingkat_kejuaraan'],
            'description'        => $validated['description'],
            'image'              => $imagePath,
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        return redirect()->route('prestasi.index')
            ->with('success', 'Prestasi berhasil ditambahkan');
    }

    public function show($id)
    {
        $prestasi = DB::table('prestasises')->where('id', $id)->first();
        if (!$prestasi) abort(404);

        $prestasi->image_url = $prestasi->image
            ? asset('storage/' . $prestasi->image)
            : null;

        return response()->json($prestasi);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'tanggal'            => 'required|date',
            'tingkat_kejuaraan'  => 'required|string|max:255',
            'description'        => 'required',
            'image'              => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $prestasi = DB::table('prestasises')->where('id', $id)->first();
        if (!$prestasi) abort(404);

        $imagePath = $prestasi->image;

        if ($request->hasFile('image')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('prestasi-images', 'public');
        }

        DB::table('prestasises')->where('id', $id)->update([
            'name'               => $validated['name'],
            'tanggal'            => $validated['tanggal'],
            'tingkat_kejuaraan'  => $validated['tingkat_kejuaraan'],
            'description'        => $validated['description'],
            'image'              => $imagePath,
            'updated_at'         => now(),
        ]);

        return redirect()->route('prestasi.index')
            ->with('success', 'Prestasi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $prestasi = DB::table('prestasises')->where('id', $id)->first();
        if (!$prestasi) abort(404);

        if ($prestasi->image && Storage::disk('public')->exists($prestasi->image)) {
            Storage::disk('public')->delete($prestasi->image);
        }

        DB::table('prestasises')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Prestasi berhasil dihapus'
        ]);
    }
}
