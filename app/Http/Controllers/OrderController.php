<?php

// app/Http/Controllers/OrderController.php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // 1. Validasi (tetap sama)
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_type' => 'required|in:tetap,custom',
            'order_date' => 'required|date',
            'deadline' => 'nullable|date|after_or_equal:order_date',
            'quantity' => 'required|integer|min:1',
        ]);
        if ($request->product_type === 'tetap') {
            $request->validate(['product_id' => 'required|exists:products,id']);
        } else {
            $request->validate(['product_name' => 'required|string|max:255', 'product_specification' => 'nullable|string']);
        }
        
        DB::beginTransaction();
        try {
            // 2. Membuat Nomor Order (tetap sama)
            $today = now()->format('Ymd');
            $latestOrder = Order::where('order_number', 'like', "SO-{$today}-%")->latest('id')->first();
            $nextNumber = $latestOrder ? (int)substr($latestOrder->order_number, -4) + 1 : 1;
            $orderNumber = "SO-{$today}-" . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            // 3. Menyiapkan dan Membuat Order (tetap sama)
            $orderData = [
                'order_number' => $orderNumber,
                'customer_id' => $validated['customer_id'], 'product_type' => $validated['product_type'],
                'order_date' => $validated['order_date'], 'deadline' => $validated['deadline'],
                'quantity' => $validated['quantity'],
                'product_name' => $request->product_type === 'tetap' ? Product::find($request->product_id)->name : $request->product_name,
                'product_specification' => $request->product_specification ?? null, 'status' => 'Menunggu Produksi',
            ];
            $order = Order::create($orderData);

            // =========================================================
            // BAGIAN KODE UNTUK GENERATE BOM OTOMATIS SEKARANG KITA HAPUS
            // =========================================================

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order baru berhasil dibuat. Silakan input BOM di halaman detail.');

        } catch (\Exception $e) {
            DB::rollBack();
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
        $request->validate([
            'total_price' => 'nullable|numeric|min:0',
        ]);

        $order->update(['total_price' => $request->total_price]);
        return redirect()->route('orders.show', $order)->with('success', 'Harga jual order berhasil diupdate.');
    }
}