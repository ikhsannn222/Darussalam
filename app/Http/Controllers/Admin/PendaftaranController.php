<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PendaftaranController extends Controller
{
    public function index()
    {
        $pendaftaran = DB::table('registrations')
            ->join('users', 'registrations.user_id', '=', 'users.id')
            ->join('programs', 'registrations.program_id', '=', 'programs.id')
            ->leftJoin('classes', 'registrations.class_id', '=', 'classes.id')
            ->select(
                'registrations.*',
                'users.name as user_name',
                'programs.name as program_name',
                'classes.name as class_name'
            )
            ->orderByDesc('registrations.created_at')
            ->get();

        return view('pendaftaran.index', compact('pendaftaran'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'        => 'required|exists:users,id',
            'program_id'     => 'required|exists:programs,id',
            'class_id'       => 'nullable|exists:classes,id',
            'student_name'   => 'required|string|max:255',
            'birth_date'     => 'required|date',
            'gender'         => 'required|in:male,female',
            'address'        => 'required',
            'parent_name'    => 'required|string|max:255',
            'parent_phone'   => 'required|string|max:20',
            'parent_email'   => 'nullable|email',
        ]);

        DB::table('registrations')->insert([
            ...$validated,
            'status'     => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('pendaftaran.index')
            ->with('success', 'Pendaftaran berhasil ditambahkan');
    }

    // AJAX show / edit
    public function show($id)
    {
        $data = DB::table('registrations')->where('id', $id)->first();
        if (!$data) abort(404);

        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'program_id'   => 'required|exists:programs,id',
            'class_id'     => 'nullable|exists:classes,id',
            'status'       => 'required|in:pending,approved,rejected',
            'notes'        => 'nullable',
        ]);

        DB::table('registrations')->where('id', $id)->update([
            ...$validated,
            'updated_at' => now(),
        ]);

        return redirect()->route('pendaftaran.index')
            ->with('success', 'Status pendaftaran berhasil diperbarui');
    }

    public function destroy($id)
    {
        DB::table('registrations')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data pendaftaran berhasil dihapus'
        ]);
    }
}
