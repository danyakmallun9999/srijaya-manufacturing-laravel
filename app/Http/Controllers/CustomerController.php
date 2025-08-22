<?php

// app/Http/Controllers/CustomerController.php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Menampilkan daftar semua customer.
     */
    public function index()
    {
        $customers = Customer::latest()->paginate(10); // Ambil 10 data terbaru
        return view('customers.index', compact('customers'));
    }

    /**
     * Menampilkan form untuk membuat customer baru.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Menyimpan customer baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        // 2. Simpan data ke database
        Customer::create($validatedData);

        // 3. Redirect ke halaman index dengan pesan sukses
        return redirect()->route('customers.index')->with('success', 'Customer berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit customer.
     */
    public function edit(Customer $customer)
    {
        // Laravel's Route Model Binding akan otomatis mencari customer berdasarkan ID
        return view('customers.edit', compact('customer'));
    }

    /**
     * Mengupdate data customer di database.
     */
    public function update(Request $request, Customer $customer)
    {
        // 1. Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        // 2. Update data
        $customer->update($validatedData);

        // 3. Redirect ke halaman index dengan pesan sukses
        return redirect()->route('customers.index')->with('success', 'Data customer berhasil diubah.');
    }

    /**
     * Menghapus customer dari database.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer berhasil dihapus.');
    }
}
