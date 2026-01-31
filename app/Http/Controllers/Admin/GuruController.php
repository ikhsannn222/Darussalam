<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index()
    {
        $teachers = Guru::latest()->get();
        return view('guru.index', compact('teachers'));
    }

    // simpan data
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'specialization' => 'required',
            'email' => 'nullable|email',
        ]);

        Guru::create([
            'name' => $request->name,
            'specialization' => $request->specialization,
            'biography' => $request->biography,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('guru.index')
            ->with('success', 'Data guru berhasil ditambahkan');
    }

    // show / edit (AJAX)
    public function show($id)
    {
        $teacher = Guru::findOrFail($id);
        return response()->json($teacher);
    }

    // update
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'specialization' => 'required',
            'email' => 'nullable|email',
        ]);

        $teacher = Guru::findOrFail($id);

        $teacher->update([
            'name' => $request->name,
            'specialization' => $request->specialization,
            'biography' => $request->biography,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('guru.index')
            ->with('success', 'Data guru berhasil diperbarui');
    }

    // delete (AJAX)
    public function destroy($id)
    {
        $teacher = Guru::findOrFail($id);
        $teacher->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil dihapus'
        ]);
    }
}
