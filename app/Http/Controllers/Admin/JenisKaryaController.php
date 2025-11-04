<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisKarya;
use Illuminate\Http\Request;

class JenisKaryaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jenisKaryas = JenisKarya::orderBy('nama')->paginate(10);
        
        // Statistik untuk cards
        $totalJenisKarya = JenisKarya::count();
        $activeJenisKarya = JenisKarya::where('is_active', true)->count();
        $inactiveJenisKarya = JenisKarya::where('is_active', false)->count();
        
        return view('admin.jenis-karyas.index', compact(
            'jenisKaryas', 
            'totalJenisKarya', 
            'activeJenisKarya', 
            'inactiveJenisKarya'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.jenis-karyas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:jenis_karyas,nama',
        ]);

        JenisKarya::create([
            'nama' => $request->nama,
            'is_active' => true,
        ]);

        return redirect()->route('admin.jenis-karyas.index')
            ->with('success', 'Jenis karya berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisKarya $jenisKarya)
    {
        return view('admin.jenis-karyas.show', compact('jenisKarya'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JenisKarya $jenisKarya)
    {
        return view('admin.jenis-karyas.edit', compact('jenisKarya'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisKarya $jenisKarya)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:jenis_karyas,nama,' . $jenisKarya->id,
            'is_active' => 'nullable|boolean',
        ]);

        $jenisKarya->update([
            'nama' => $request->nama,
            'is_active' => (bool) $request->input('is_active', false),
        ]);

        return redirect()->route('admin.jenis-karyas.index')
            ->with('success', 'Jenis karya berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisKarya $jenisKarya)
    {
        // Cek apakah jenis karya ini digunakan dalam submissions
        if ($jenisKarya->submissions()->count() > 0) {
            return redirect()->route('admin.jenis-karyas.index')
                ->with('error', 'Jenis karya tidak dapat dihapus karena masih digunakan dalam submission.');
        }

        $jenisKarya->delete();

        return redirect()->route('admin.jenis-karyas.index')
            ->with('success', 'Jenis karya berhasil dihapus.');
    }
}
