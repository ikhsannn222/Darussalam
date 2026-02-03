<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuruController extends Controller
{
    public function index()
    {
        $teachers = Guru::latest()->get();
        return view('guru.index', compact('teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'biography' => ['nullable'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('teacher-photos', 'public');
        }

        Guru::create([
            'name' => $validated['name'],
            'birth_date' => $validated['birth_date'] ?? null,
            'biography' => $validated['biography'] ?? null, // HTML dari CKEditor
            'photo' => $photoPath,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
        ]);

        return redirect()->route('guru.index')
            ->with('success', 'Data guru berhasil ditambahkan');
    }

    public function show($id)
    {
        $teacher = Guru::findOrFail($id);

        // bantu frontend
        $teacher->photo_url = $teacher->photo_url;

        return response()->json($teacher);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'biography' => ['nullable'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        $teacher = Guru::findOrFail($id);

        // upload foto baru -> hapus foto lama
        if ($request->hasFile('photo')) {
            if ($teacher->photo && Storage::disk('public')->exists($teacher->photo)) {
                Storage::disk('public')->delete($teacher->photo);
            }
            $teacher->photo = $request->file('photo')->store('teacher-photos', 'public');
        }

        $teacher->name = $validated['name'];
        $teacher->birth_date = $validated['birth_date'] ?? null;
        $teacher->biography = $validated['biography'] ?? null;
        $teacher->email = $validated['email'] ?? null;
        $teacher->phone = $validated['phone'] ?? null;
        $teacher->save();

        return redirect()->route('guru.index')
            ->with('success', 'Data guru berhasil diperbarui');
    }

    public function destroy($id)
    {
        $teacher = Guru::findOrFail($id);

        if ($teacher->photo && Storage::disk('public')->exists($teacher->photo)) {
            Storage::disk('public')->delete($teacher->photo);
        }

        $teacher->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil dihapus'
        ]);
    }
}
