<?php

// app/Http/Controllers/MaterialController.php
namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::latest()->paginate(10);
        return view('materials.index', compact('materials'));
    }

    public function create()
    {
        return view('materials.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50', // cth: pcs, meter, kg
            'category' => 'nullable|string|max:100',
        ]);

        Material::create($validatedData);

        return redirect()->route('materials.index')->with('success', 'Bahan baku berhasil ditambahkan.');
    }

    public function edit(Material $material)
    {
        return view('materials.edit', compact('material'));
    }

    public function update(Request $request, Material $material)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'category' => 'nullable|string|max:100',
        ]);

        $material->update($validatedData);

        return redirect()->route('materials.index')->with('success', 'Data bahan baku berhasil diubah.');
    }

    public function destroy(Material $material)
    {
        $material->delete();
        return redirect()->route('materials.index')->with('success', 'Bahan baku berhasil dihapus.');
    }
}