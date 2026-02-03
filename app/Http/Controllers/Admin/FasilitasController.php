<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FasilitasController extends Controller
{
    public function index()
    {
        $fasilitas = DB::table('facilities')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($f) {
                $f->image_url = $f->image
                    ? asset('storage/' . $f->image)
                    : null;
                return $f;
            });

        return view('fasilitas.index', compact('fasilitas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('fasilitas-images', 'public');
        }

        DB::table('facilities')->insert([
            'name'        => $validated['name'],
            'description' => $validated['description'],
            'image'       => $imagePath,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return redirect()->route('fasilitas.index')
            ->with('success', 'Fasilitas berhasil ditambahkan');
    }

    // AJAX show / edit
    public function show($id)
    {
        $fasilitas = DB::table('facilities')->where('id', $id)->first();

        if (!$fasilitas) {
            abort(404);
        }

        $fasilitas->image_url = $fasilitas->image
            ? asset('storage/' . $fasilitas->image)
            : null;

        return response()->json($fasilitas);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $fasilitas = DB::table('facilities')->where('id', $id)->first();
        if (!$fasilitas) abort(404);

        $imagePath = $fasilitas->image;

        if ($request->hasFile('image')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('fasilitas-images', 'public');
        }

        DB::table('facilities')->where('id', $id)->update([
            'name'        => $validated['name'],
            'description' => $validated['description'],
            'image'       => $imagePath,
            'updated_at'  => now(),
        ]);

        return redirect()->route('fasilitas.index')
            ->with('success', 'Fasilitas berhasil diperbarui');
    }

    public function destroy($id)
    {
        $fasilitas = DB::table('facilities')->where('id', $id)->first();
        if (!$fasilitas) abort(404);

        if ($fasilitas->image && Storage::disk('public')->exists($fasilitas->image)) {
            Storage::disk('public')->delete($fasilitas->image);
        }

        DB::table('facilities')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Fasilitas berhasil dihapus'
        ]);
    }
}
