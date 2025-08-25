<?php

// app/Http/Controllers/OrderController.php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // Added this import for Storage facade

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('customer') // Mengambil relasi customer untuk efisiensi query
                       ->latest() // Mengurutkan dari yang terbaru
                       ->paginate(10); // Membatasi 10 data per halaman

        return view('orders.index', compact('orders'));
    }

    /**
     * Menampilkan form untuk membuat order baru.
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        return view('orders.create', compact('customers', 'products'));
    }

    /**
     * Menyimpan order baru ke database.
     */
    public function store(Request $request)
    {
        // Debug: Log request data
        \Log::info('Order creation request:', [
            'product_type' => $request->product_type,
            'product_name' => $request->product_name,
            'custom_product_specification' => $request->custom_product_specification,
            'fixed_product_specification' => $request->fixed_product_specification,
            'has_custom_image' => $request->hasFile('custom_image'),
            'custom_image_name' => $request->file('custom_image') ? $request->file('custom_image')->getClientOriginalName() : null,
            'custom_image_size' => $request->file('custom_image') ? $request->file('custom_image')->getSize() : null,
            'custom_image_mime' => $request->file('custom_image') ? $request->file('custom_image')->getMimeType() : null,
            'custom_image_error' => $request->file('custom_image') ? $request->file('custom_image')->getError() : null,
            'all_inputs' => $request->all(),
        ]);

        // 1. Validasi dasar
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_type' => 'required|in:tetap,custom',
            'order_date' => 'required|date',
            'deadline' => 'nullable|date|after_or_equal:order_date',
            'quantity' => 'required|integer|min:1',
        ]);
        
        // 2. Validasi tambahan berdasarkan jenis produk
        if ($request->product_type === 'tetap') {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'fixed_product_specification' => 'nullable|string',
            ]);
        } else {
            $validationRules = [
                'product_name' => 'required|string|max:255',
                'custom_product_specification' => 'nullable|string',
            ];
            
            // Tambahkan validasi gambar hanya jika ada file yang diupload
            if ($request->hasFile('custom_image') && $request->file('custom_image')->isValid()) {
                $validationRules['custom_image'] = 'image|mimes:jpeg,png,jpg,gif,webp|max:5120';
            }
            
            $request->validate($validationRules, [
                'custom_image.image' => 'File harus berupa gambar.',
                'custom_image.mimes' => 'Format gambar harus: jpeg, png, jpg, gif, atau webp.',
                'custom_image.max' => 'Ukuran gambar maksimal 5MB.',
            ]);
        }
        
        DB::beginTransaction();
        try {
            // 3. Membuat Nomor Order dengan retry logic
            $today = now()->format('Ymd');
            $maxRetries = 10;
            $orderNumber = null;
            
            for ($i = 0; $i < $maxRetries; $i++) {
                // Cari semua order untuk hari ini dan ambil nomor tertinggi
                $todayOrders = Order::where('order_number', 'like', "SO-{$today}-%")->get();
                $maxNumber = 0;
                
                foreach ($todayOrders as $order) {
                    $number = (int)substr($order->order_number, -4);
                    if ($number > $maxNumber) {
                        $maxNumber = $number;
                    }
                }
                
                $nextNumber = $maxNumber + 1;
                $orderNumber = "SO-{$today}-" . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                
                // Cek apakah nomor order sudah ada
                $existingOrder = Order::where('order_number', $orderNumber)->first();
                if (!$existingOrder) {
                    break; // Nomor order unik, keluar dari loop
                }
                
                \Log::warning('Duplicate order number detected, retrying:', [
                    'attempt' => $i + 1,
                    'order_number' => $orderNumber,
                    'max_retries' => $maxRetries
                ]);
            }
            
            if (!$orderNumber) {
                throw new \Exception('Gagal generate nomor order yang unik setelah ' . $maxRetries . ' percobaan.');
            }
            
            \Log::info('Generated order number:', ['order_number' => $orderNumber]);

            // 4. Menyiapkan dan Membuat Order
            $orderData = [
                'order_number' => $orderNumber,
                'customer_id' => $validated['customer_id'],
                'product_type' => $validated['product_type'],
                'order_date' => $validated['order_date'],
                'deadline' => $validated['deadline'],
                'quantity' => $validated['quantity'],
                'product_name' => $request->product_type === 'tetap' ? Product::find($request->product_id)->name : $request->product_name,
                'product_specification' => $request->product_type === 'custom' ? $request->custom_product_specification : $request->fixed_product_specification,
                'status' => 'Menunggu Produksi',
            ];
            
            // Tambahkan product_id jika produk tetap
            if ($request->product_type === 'tetap') {
                $orderData['product_id'] = $request->product_id;
            }
            
            // 5. Handle image upload untuk produk custom
            if ($request->product_type === 'custom' && $request->hasFile('custom_image') && $request->file('custom_image')->isValid()) {
                try {
                    \Log::info('Uploading custom image:', [
                        'original_name' => $request->file('custom_image')->getClientOriginalName(),
                        'size' => $request->file('custom_image')->getSize(),
                        'mime_type' => $request->file('custom_image')->getMimeType(),
                        'error' => $request->file('custom_image')->getError(),
                    ]);
                    
                    // Pastikan direktori ada
                    $uploadPath = 'custom-products';
                    if (!Storage::disk('public')->exists($uploadPath)) {
                        Storage::disk('public')->makeDirectory($uploadPath);
                    }
                    
                    $imagePath = $request->file('custom_image')->store($uploadPath, 'public');
                    $orderData['image'] = $imagePath;
                    
                    \Log::info('Image uploaded successfully:', ['path' => $imagePath]);
                } catch (\Exception $e) {
                    \Log::error('Image upload failed:', ['error' => $e->getMessage()]);
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['custom_image' => 'Gagal mengupload gambar: ' . $e->getMessage()]);
                }
            } else if ($request->product_type === 'custom' && $request->hasFile('custom_image') && !$request->file('custom_image')->isValid()) {
                \Log::warning('Invalid file uploaded:', [
                    'error' => $request->file('custom_image')->getError(),
                    'original_name' => $request->file('custom_image')->getClientOriginalName(),
                ]);
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['custom_image' => 'File yang diupload tidak valid. Pastikan file adalah gambar dengan format yang benar.']);
            }
            
            // Debug: Log order data before creation
            \Log::info('Order data before creation:', $orderData);
            
            $order = Order::create($orderData);
            
            // Debug: Log created order
            \Log::info('Order created successfully:', [
                'order_id' => $order->id,
                'product_name' => $order->product_name,
                'product_specification' => $order->product_specification,
                'image' => $order->image,
            ]);

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order baru berhasil dibuat. Silakan input BOM di halaman detail.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order creation failed:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Gagal membuat order: ' . $e->getMessage())->withInput();
        }
    }
    
    public function show(Order $order)
    {
        // Eager load semua relasi yang akan kita butuhkan di view
        $order->load([
            'customer', 
            'purchases', 
            'productionCosts', 
            'incomes'
        ]);
        
        return view('orders.show', compact('order'));
    }
    // ... (method edit & update bisa kita urus nanti)

    /**
     * Mengupdate status order.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|string']);
        $order->update(['status' => $request->status]);
        return redirect()->route('orders.show', $order)->with('success', 'Status order berhasil diubah.');
    }

    /**
     * Menghapus item dari BOM sebuah order.
     */
    public function destroyBomItem(OrderBom $orderBom)
{
    $order = $orderBom->order; // Ambil order dari relasi
    $orderBom->delete();
    if ($order) {
        return redirect()->route('orders.show', $order)->with('success', 'Item BOM berhasil dihapus.');
    } else {
        return redirect()->route('orders.index')->with('success', 'Item BOM berhasil dihapus.');
    }
}

    /**
     * Update harga jual order.
     */
    public function updatePrice(Request $request, Order $order)
    {
        // Log that the method was called
        \Log::info('UpdatePrice - Method called', [
            'order_id' => $order->id,
            'request_method' => $request->method(),
            'request_url' => $request->url(),
            'all_inputs' => $request->all()
        ]);
        
        try {
            // Clean the formatted number input (remove commas and dots)
            $totalPrice = $request->input('total_price');
            
            // Debug logging
            \Log::info('UpdatePrice - Raw input:', ['total_price' => $totalPrice]);
            
            if ($totalPrice) {
                // Remove commas and dots, then convert to numeric
                $totalPrice = (float) str_replace(['.', ','], ['', ''], $totalPrice);
                
                // Debug logging
                \Log::info('UpdatePrice - Cleaned value:', ['cleaned_total_price' => $totalPrice]);
            }

            // Validate the cleaned value
            $request->merge(['total_price' => $totalPrice]);
            $validated = $request->validate([
                'total_price' => 'nullable|numeric|min:0',
            ]);

            // Update the order
            $result = $order->update(['total_price' => $totalPrice]);
            
            // Debug logging
            \Log::info('UpdatePrice - Order update result:', [
                'order_id' => $order->id, 
                'new_total_price' => $totalPrice,
                'update_result' => $result
            ]);
            
            if ($result) {
                return redirect()->route('orders.show', $order)->with('success', 'Harga jual order berhasil diupdate.');
            } else {
                return redirect()->back()->with('error', 'Gagal mengupdate harga jual order.');
            }
            
        } catch (\Exception $e) {
            \Log::error('UpdatePrice - Error:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Gagal mengupdate harga jual order: ' . $e->getMessage());
        }
    }

    /**
     * Update produk custom.
     */
    public function updateCustomProduct(Request $request, Order $order)
    {
        // Pastikan ini adalah produk custom
        if ($order->product_type !== 'custom') {
            return redirect()->back()->with('error', 'Hanya produk custom yang dapat diedit.');
        }

        try {
            // Validasi input
            $validated = $request->validate([
                'product_name' => 'required|string|max:255',
                'product_specification' => 'nullable|string',
                'custom_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            ], [
                'custom_image.image' => 'File harus berupa gambar.',
                'custom_image.mimes' => 'Format gambar harus: jpeg, png, jpg, gif, atau webp.',
                'custom_image.max' => 'Ukuran gambar maksimal 5MB.',
            ]);

            $updateData = [
                'product_name' => $validated['product_name'],
                'product_specification' => $validated['product_specification'] ?? null,
            ];

            // Handle image upload jika ada
            if ($request->hasFile('custom_image')) {
                try {
                    \Log::info('Updating custom product image:', [
                        'order_id' => $order->id,
                        'original_name' => $request->file('custom_image')->getClientOriginalName(),
                        'size' => $request->file('custom_image')->getSize(),
                        'mime_type' => $request->file('custom_image')->getMimeType(),
                    ]);

                    // Hapus gambar lama jika ada
                    if ($order->image) {
                        Storage::disk('public')->delete($order->image);
                    }

                    // Upload gambar baru
                    $imagePath = $request->file('custom_image')->store('custom-products', 'public');
                    $updateData['image'] = $imagePath;

                    \Log::info('Custom product image updated successfully:', ['path' => $imagePath]);
                } catch (\Exception $e) {
                    \Log::error('Custom product image update failed:', ['error' => $e->getMessage()]);
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['custom_image' => 'Gagal mengupload gambar: ' . $e->getMessage()]);
                }
            }

            // Update order
            $order->update($updateData);

            \Log::info('Custom product updated successfully:', [
                'order_id' => $order->id,
                'product_name' => $order->product_name,
                'product_specification' => $order->product_specification,
                'image' => $order->image,
            ]);

            return redirect()->route('products.index')->with('success', 'Produk custom berhasil diupdate.');

        } catch (\Exception $e) {
            \Log::error('Custom product update failed:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Gagal mengupdate produk custom: ' . $e->getMessage());
        }
    }
}