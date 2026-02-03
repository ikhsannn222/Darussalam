<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GuruController extends Controller
{
    public function index()
    {
        $teachers = DB::table('teachers')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($t) {
                $t->photo_url = $t->photo
                    ? asset('storage/' . $t->photo)
                    : null;
                return $t;
            });

        return view('guru.index', compact('teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'biography'  => 'nullable',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'email'      => 'nullable|email',
            'phone'      => 'nullable|string|max:50',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('teacher-photos', 'public');
        }

        DB::table('teachers')->insert([
            'name'       => $validated['name'],
            'birth_date' => $validated['birth_date'] ?? null,
            'biography'  => $validated['biography'] ?? null,
            'photo'      => $photoPath,
            'email'      => $validated['email'] ?? null,
            'phone'      => $validated['phone'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('guru.index')
            ->with('success', 'Data guru berhasil ditambahkan');
    }

    public function show($id)
    {
        $teacher = DB::table('teachers')->where('id', $id)->first();
        if (!$teacher) abort(404);

        $teacher->photo_url = $teacher->photo
            ? asset('storage/' . $teacher->photo)
            : null;

        return response()->json($teacher);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'biography'  => 'nullable',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'email'      => 'nullable|email',
            'phone'      => 'nullable|string|max:50',
        ]);

        $teacher = DB::table('teachers')->where('id', $id)->first();
        if (!$teacher) abort(404);

        $photoPath = $teacher->photo;

        if ($request->hasFile('photo')) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('teacher-photos', 'public');
        }

        DB::table('teachers')->where('id', $id)->update([
            'name'       => $validated['name'],
            'birth_date' => $validated['birth_date'] ?? null,
            'biography'  => $validated['biography'] ?? null,
            'photo'      => $photoPath,
            'email'      => $validated['email'] ?? null,
            'phone'      => $validated['phone'] ?? null,
            'updated_at' => now(),
        ]);

        return redirect()->route('guru.index')
            ->with('success', 'Data guru berhasil diperbarui');
    }

    public function destroy($id)
    {
        $teacher = DB::table('teachers')->where('id', $id)->first();
        if (!$teacher) abort(404);

        if ($teacher->photo && Storage::disk('public')->exists($teacher->photo)) {
            Storage::disk('public')->delete($teacher->photo);
        }

        DB::table('teachers')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil dihapus'
        ]);
    }
}
