<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use Illuminate\Http\Request;

class FasilitasController extends Controller
{
    public function index()
    {
        $fasilitas = Fasilitas::latest()->get();
        return view('fasilitas.index', compact('fasilitas'));
    }

    // tambah data
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        Fasilitas::create([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
        ]);

        return redirect()->route('fasilitas.index')
            ->with('success', 'Fasilitas berhasil ditambahkan');
    }

    // ambil 1 data (AJAX show / edit)
    public function show($id)
    {
        $fasilitas = Fasilitas::findOrFail($id);
        return response()->json($fasilitas);
    }

    // update data
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $fasilitas = Fasilitas::findOrFail($id);

        $fasilitas->update([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
        ]);

        return redirect()->route('fasilitas.index')
            ->with('success', 'Fasilitas berhasil diupdate');
    }

    // hapus data (AJAX)
    public function destroy($id)
    {
        $fasilitas = Fasilitas::findOrFail($id);
        $fasilitas->delete();

        return response()->json([
            'success' => true,
            'message' => 'Fasilitas berhasil dihapus'
        ]);
    }
}
