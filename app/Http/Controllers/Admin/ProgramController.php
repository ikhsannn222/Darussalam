<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = DB::table('programs')
            ->orderByDesc('created_at')
            ->get();

        return view('program.index', compact('programs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required',
            'logo'        => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('program-logos', 'public');
        }

        DB::table('programs')->insert([
            'name'                  => $validated['name'],
            'description'           => $validated['description'],
            'logo'                  => $logoPath,
            'is_open_registration'  => $request->has('is_open_registration') ? 1 : 0,
            'created_at'            => now(),
            'updated_at'            => now(),
        ]);

        return redirect()->route('program.index')
            ->with('success', 'Program berhasil ditambahkan');
    }

    public function show($id)
    {
        $program = DB::table('programs')->where('id', $id)->first();
        abort_if(!$program, 404);

        $program->logo_url = $program->logo
            ? asset('storage/' . $program->logo)
            : null;

        return response()->json($program);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required',
            'logo'        => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
        ]);

        $program = DB::table('programs')->where('id', $id)->first();
        abort_if(!$program, 404);

        $logoPath = $program->logo;

        if ($request->hasFile('logo')) {
            if ($program->logo && Storage::disk('public')->exists($program->logo)) {
                Storage::disk('public')->delete($program->logo);
            }
            $logoPath = $request->file('logo')->store('program-logos', 'public');
        }

        DB::table('programs')->where('id', $id)->update([
            'name'                 => $validated['name'],
            'description'          => $validated['description'],
            'logo'                 => $logoPath,
            'is_open_registration' => $request->has('is_open_registration') ? 1 : 0,
            'updated_at'           => now(),
        ]);

        return redirect()->route('program.index')
            ->with('success', 'Program berhasil diupdate');
    }

    public function destroy($id)
    {
        $program = DB::table('programs')->where('id', $id)->first();
        abort_if(!$program, 404);

        if ($program->logo && Storage::disk('public')->exists($program->logo)) {
            Storage::disk('public')->delete($program->logo);
        }

        DB::table('programs')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Program berhasil dihapus'
        ]);
    }
}
