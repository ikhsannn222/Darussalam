<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::latest()->get();
        return view('program.index', compact('programs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('program-logos', 'public');
        }

        Program::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'logo' => $logoPath,
            'is_open_registration' => $request->has('is_open_registration') ? 1 : 0,
        ]);

        return redirect()->route('program.index')->with('success', 'Program berhasil ditambahkan');
    }

    public function show($id)
    {
        $program = Program::findOrFail($id);
        $program->logo_url = $program->logo ? asset('storage/' . $program->logo) : null;

        return response()->json($program);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
        ]);

        $program = Program::findOrFail($id);

        // update logo jika ada file baru
        if ($request->hasFile('logo')) {
            if ($program->logo && Storage::disk('public')->exists($program->logo)) {
                Storage::disk('public')->delete($program->logo);
            }
            $program->logo = $request->file('logo')->store('program-logos', 'public');
        }

        $program->name = $validated['name'];
        $program->description = $validated['description'];
        $program->is_open_registration = $request->has('is_open_registration') ? 1 : 0;
        $program->save();

        return redirect()->route('program.index')->with('success', 'Program berhasil diupdate');
    }

    public function destroy($id)
    {
        $program = Program::findOrFail($id);

        if ($program->logo && Storage::disk('public')->exists($program->logo)) {
            Storage::disk('public')->delete($program->logo);
        }

        $program->delete();

        return response()->json([
            'success' => true,
            'message' => 'Program berhasil dihapus'
        ]);
    }
}
