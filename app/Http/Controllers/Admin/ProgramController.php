<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::latest()->get();
        return view('program.index', compact('programs'));
    }

    // tambah data
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        Program::create([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'is_open_registration' => $request->has('is_open_registration') ? 1 : 0,
        ]);

        // redirect ke halaman index supaya SweetAlert di index tampil
        return redirect()->route('program.index')
            ->with('success', 'Program berhasil ditambahkan');
    }

    // ambil 1 data untuk view / edit (biasanya dipakai AJAX)
    public function show($id)
    {
        $program = Program::findOrFail($id);
        return response()->json($program);
    }

    // update data
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $program = Program::findOrFail($id);

        $program->update([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'is_open_registration' => $request->has('is_open_registration') ? 1 : 0,
        ]);

        return redirect()->route('program.index')
            ->with('success', 'Program berhasil diupdate');
    }

    // hapus data (dipanggil AJAX)
    public function destroy($id)
    {
        $program = Program::findOrFail($id);
        $program->delete();

        return response()->json([
            'success' => true,
            'message' => 'Program berhasil dihapus'
        ]);
    }
}
