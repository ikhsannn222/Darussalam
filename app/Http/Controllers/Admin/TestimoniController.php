<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use Illuminate\Http\Request;

class TestimoniController extends Controller
{
    public function index()
    {
        $testimonis = Testimoni::latest()->get();
        return view('testimoni.index', compact('testimonis'));
    }

    public function create()
    {
        return view('testimoni.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'role'    => 'required|string|max:100',
            'message' => 'required|string',
            'rating'  => 'required|integer|min:1|max:5',
        ]);

        Testimoni::create([
            'user_id'     => null, // admin input manual
            'name'        => $request->name,
            'role'        => $request->role,
            'message'     => $request->message,
            'rating'      => $request->rating,
            'is_approved' => true, // admin auto approved
        ]);

        return redirect()->route('testimoni.index')
            ->with('success', 'Testimoni berhasil ditambahkan.');
    }

    public function show(Testimoni $testimoni)
    {
        return view('testimoni.show', compact('testimoni'));
    }

    public function edit(Testimoni $testimoni)
    {
        return view('testimoni.edit', compact('testimoni'));
    }

    public function update(Request $request, Testimoni $testimoni)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'role'    => 'required|string|max:100',
            'message' => 'required|string',
            'rating'  => 'required|integer|min:1|max:5',
        ]);

        $testimoni->update($request->all());

        return redirect()->route('testimoni.index')
            ->with('success', 'Testimoni berhasil diperbarui.');
    }

    public function destroy(Testimoni $testimoni)
    {
        $testimoni->delete();

        return redirect()->route('testimoni.index')
            ->with('success', 'Testimoni berhasil dihapus.');
    }
}
