# Revisi Sistem Laravel

## Perubahan yang Dilakukan

### ✅ 1. Menghilangkan Field Harga dari Produk

#### A. Form Tambah Produk:
- **Dihapus**: Field "Harga Dasar" (`base_price`)
- **Dihapus**: Field "Kategori Produk" (pilihan tetap/custom)
- **Ditambahkan**: Hidden input `product_category = 'tetap'`
- **Alasan**: Produk yang ditambahkan hanya untuk produk tetap, harga akan ditentukan saat order

#### B. Form Edit Produk:
- **Dihapus**: Field "Harga Dasar" (`base_price`)
- **Dihapus**: Field "Kategori Produk" (pilihan tetap/custom)
- **Ditambahkan**: Hidden input `product_category = 'tetap'`
- **Dihapus**: Script formatNumber untuk format harga

#### C. Tabel Produk (Index):
- **Dihapus**: Kolom "Harga Dasar"
- **Diupdate**: Colspan dari 8 menjadi 7

#### D. Database:
- **Migration**: `2025_08_25_000002_remove_base_price_from_products_table.php`
- **Menghapus**: Field `base_price` dari tabel products

#### E. Model & Controller:
- **Product Model**: Menghapus `base_price` dari fillable dan casts
- **ProductController**: Menghapus validasi dan handling `base_price`
- **ProductController**: Mengubah validasi `product_category` dari required menjadi nullable dengan default 'tetap'

### ✅ 2. Fitur Produk Custom yang Diperbaiki

#### A. Form Create Order - Produk Custom:
- ✅ **Ditambahkan**: Field upload gambar untuk produk custom (opsional)
- ✅ **Tetap**: Field nama produk custom
- ✅ **Tetap**: Field spesifikasi produk custom
- ✅ **Diperbaiki**: Duplikasi field product_specification di form
- ✅ **Diperbaiki**: Field names yang berbeda untuk custom dan fixed products
- ✅ **Diperbaiki**: Form enctype untuk file upload
- ✅ **Diperbaiki**: Validasi file upload yang lebih robust
- ✅ **Alasan**: Produk custom dapat memiliki gambar referensi

#### B. Halaman Produk dengan Tab:
- ✅ **Tab Produk Tetap**: Menampilkan produk dari tabel products
- ✅ **Tab Produk Custom**: Menampilkan produk custom dari tabel orders
- ✅ **Tidak ada tombol tambah** di tab produk custom
- ✅ **Tabel produk custom** menampilkan: Gambar, Nama Produk, No Order, Customer, Spesifikasi
- ✅ **Tombol Edit** untuk produk custom dengan modal

#### C. Database & Model:
- **Migration**: `2025_08_25_000003_add_image_to_orders_table.php`
- **Ditambahkan**: Field `image` ke tabel orders
- **Order Model**: Menambahkan `image` ke fillable dan method `getImageUrlAttribute()`

#### D. OrderController:
- ✅ **Diupdate**: Menangani upload gambar untuk produk custom
- ✅ **Diupdate**: Menyimpan gambar ke folder `custom-products`
- ✅ **Ditambahkan**: Method `updateCustomProduct()` untuk edit produk custom
- ✅ **Diperbaiki**: Debugging log untuk troubleshooting
- ✅ **Diperbaiki**: Field names yang berbeda untuk custom dan fixed products
- ✅ **Diperbaiki**: Validasi yang terpisah untuk setiap jenis produk
- ✅ **Diperbaiki**: Validasi file upload yang lebih robust dengan `isValid()` check
- ✅ **Diperbaiki**: Error handling untuk file upload yang tidak valid
- ✅ **Diperbaiki**: Generate nomor order yang unik dengan retry logic

#### E. ProductController:
- ✅ **Diupdate**: Method index untuk menampilkan produk tetap dan custom
- ✅ **Diupdate**: Pagination terpisah untuk masing-masing jenis produk

#### F. Fitur Edit Produk Custom:
- ✅ **Modal Edit**: Form untuk mengubah nama dan spesifikasi produk custom
- ✅ **Upload Gambar**: Kemampuan untuk mengganti gambar produk custom
- ✅ **Route**: `/orders/{order}/update-custom-product`
- ✅ **Validasi**: Validasi input dan gambar yang sama dengan create

#### G. Perbaikan Field Names:
- ✅ **Custom Product**: `custom_product_specification` untuk spesifikasi
- ✅ **Fixed Product**: `fixed_product_specification` untuk spesifikasi tambahan
- ✅ **Menghindari konflik**: Field names yang unik untuk setiap jenis produk
- ✅ **Controller Logic**: Menangani field yang berbeda berdasarkan product_type

#### H. Perbaikan Generate Nomor Order:
- ✅ **Retry Logic**: Sistem mencoba hingga 10 kali untuk generate nomor unik
- ✅ **Max Number Detection**: Mencari nomor tertinggi dari semua order hari ini
- ✅ **Duplicate Prevention**: Cek apakah nomor order sudah ada sebelum create
- ✅ **Error Handling**: Exception jika gagal generate nomor unik
- ✅ **Logging**: Log untuk debugging dan monitoring

#### I. Penghapusan ProductSeeder:
- ✅ **Dihapus**: File `database/seeders/ProductSeeder.php`
- ✅ **Diupdate**: `DatabaseSeeder.php` untuk menghapus referensi ke ProductSeeder
- ✅ **Alasan**: Produk sekarang dibuat manual melalui interface, tidak perlu seeder

#### J. Fitur Metode Pembayaran pada Pemasukan:
- ✅ **Migration**: `2025_08_25_043925_add_payment_method_to_incomes_table.php`
- ✅ **Ditambahkan**: Kolom `payment_method` ke tabel incomes
- ✅ **Model**: Income model dengan constants dan accessor untuk payment methods
- ✅ **Controller**: IncomeController dengan validasi payment_method
- ✅ **View**: Tabel dan form pemasukan dengan kolom metode pembayaran
- ✅ **Opsi Pembayaran**: Transfer, Cash, Transfer BCA, Transfer BRI, Transfer Mandiri, Transfer PayPal, E-Wallet

#### K. Fitur Input Multiple Pembelian Material:
- ✅ **Controller**: PurchaseController dengan method `storeMultiple()` untuk handle multiple purchases
- ✅ **Route**: Route baru untuk multiple purchases (`purchases.storeMultiple`)
- ✅ **View**: Form dinamis dengan Alpine.js untuk tambah/hapus baris pembelian
- ✅ **Fitur**: 
  - Toggle antara Single Input dan Multiple Input
  - Dynamic rows dengan tombol tambah/hapus
  - Real-time total calculation
  - Reset form functionality
  - Upload foto nota untuk semua pembelian sekaligus

#### L. Fitur Invoice Fleksibel:
- ✅ **Migration**: `2025_08_25_083110_add_flexible_fields_to_invoices_table.php`
- ✅ **Ditambahkan**: 25 field baru untuk invoice fleksibel
- ✅ **Model**: Invoice model dengan methods untuk payment tracking dan revision
- ✅ **Controller**: InvoiceController dengan fitur generate, update, dan revise
- ✅ **View**: Form wizard dengan 5 section (Dasar, Perusahaan, Pengiriman, Pembayaran, Kustom)
- ✅ **PDF**: Template PDF yang menggunakan field dinamis dari database
- ✅ **Fitur**:
  - Invoice dapat dibuat kapan saja setelah harga ditentukan
  - Otomatis sync dengan data pemasukan (DP/Lunas)
  - Dapat direvisi jika ada perubahan harga
  - Include foto produk dan logo perusahaan
  - Tracking pembayaran real-time
  - Form wizard yang user-friendly

#### M. Fitur Invoice Produk Custom:
- ✅ **Controller**: InvoiceController dengan auto-calculation HPP + margin
- ✅ **View**: Form dengan margin calculator dan HPP breakdown
- ✅ **PDF**: Template dengan breakdown HPP untuk produk custom
- ✅ **Fitur**:
  - Auto-calculate harga dari total biaya produksi + margin
  - Margin percentage yang dapat dikustomisasi (default 30%)
  - HPP breakdown yang detail (pembelian + biaya produksi)
  - Invoice dapat dibuat tanpa perlu set harga manual
  - PDF menampilkan breakdown HPP yang transparan

### ✅ 3. Menyederhanakan Form Tambah Order

#### A. Menyederhanakan Form (Dengan Tetap Mendukung Produk Custom):
- ✅ **Tetap**: Radio button pilihan "Produk Tetap" dan "Produk Custom"
- ✅ **Tetap**: Alpine.js logic untuk toggle form
- ✅ **Tetap**: Dropdown pilihan produk untuk produk tetap
- ✅ **Tetap**: Field nama produk custom untuk produk custom
- ✅ **Tetap**: Field spesifikasi untuk kedua jenis produk

#### B. OrderController:
- ✅ **Diupdate**: Validasi untuk kedua jenis produk (tetap dan custom)
- ✅ **Diupdate**: Logic untuk menangani product_type sesuai pilihan
- ✅ **Diupdate**: Mengambil nama produk dari relasi Product untuk produk tetap
- ✅ **Diupdate**: Menggunakan nama custom untuk produk custom

### ✅ 4. File yang Diubah

#### Form Views:
- `resources/views/products/create.blade.php` - Menghapus field harga
- `resources/views/products/edit.blade.php` - Menghapus field harga
- `resources/views/products/index.blade.php` - Menghapus kolom harga
- `resources/views/orders/create.blade.php` - Menyederhanakan form

#### Controllers:
- `app/Http/Controllers/ProductController.php` - Menghapus handling harga
- `app/Http/Controllers/OrderController.php` - Menyederhanakan logic order

#### Models:
- `app/Models/Product.php` - Menghapus base_price dari fillable

#### Database:
- `database/migrations/2025_08_25_000002_remove_base_price_from_products_table.php` - Migration baru
- `database/seeders/ProductSeeder.php` - Menghapus base_price, gunakan updateOrCreate

### ✅ 5. Alur Kerja Baru

#### A. Tambah Produk:
1. User mengisi form produk (tanpa harga)
2. Produk disimpan sebagai "produk tetap"
3. Stok dapat dikelola

#### B. Tambah Order:
1. User pilih customer
2. User pilih jenis produk:
   - **Produk Tetap**: Pilih dari dropdown produk yang sudah ada
   - **Produk Custom**: Isi nama produk custom dan spesifikasi
3. User isi quantity dan spesifikasi tambahan (opsional)
4. Order dibuat dengan `product_type` sesuai pilihan
5. Harga jual ditentukan nanti di halaman detail order

### ✅ 6. Keuntungan Perubahan

#### A. Kesederhanaan:
- Form lebih sederhana dan mudah dipahami
- Tidak ada kebingungan antara produk tetap dan custom
- Workflow yang lebih jelas

#### B. Fleksibilitas:
- Harga dapat ditentukan per order
- Spesifikasi tambahan dapat ditambahkan per order
- Stok tetap dapat dikelola

#### C. Konsistensi:
- Semua produk adalah produk tetap
- Semua order memiliki relasi dengan produk
- Data lebih terstruktur

### ✅ 7. Testing

#### Yang Dapat Ditest:
1. **Tambah Produk Baru**:
   - Isi semua field kecuali harga
   - **Tidak ada lagi pilihan kategori produk** (otomatis 'tetap')
   - Upload gambar
   - Verifikasi produk tersimpan tanpa harga dan dengan kategori 'tetap'

2. **Edit Produk**:
   - Form tidak menampilkan pilihan kategori produk
   - Semua field lain dapat diedit
   - Kategori tetap 'tetap' (hidden input)

3. **Tambah Order Baru - Produk Tetap**:
   - Pilih customer
   - Pilih "Produk Tetap"
   - Pilih produk dari dropdown
   - Isi quantity dan spesifikasi tambahan
   - Verifikasi order dibuat dengan product_type = 'tetap'

4. **Tambah Order Baru - Produk Custom**:
   - Pilih customer
   - Pilih "Produk Custom"
   - Isi nama produk custom
   - Isi spesifikasi produk custom
   - **Upload gambar produk custom (opsional)**
   - Isi quantity
   - Verifikasi order dibuat dengan product_type = 'custom'

5. **Halaman Produk - Tab Produk Tetap**:
   - Lihat daftar produk tetap
   - Tombol "Tambah Produk" tersedia
   - Update stok berfungsi

6. **Halaman Produk - Tab Produk Custom**:
   - Lihat daftar produk custom dari orders
   - **Tidak ada tombol tambah**
   - Tampilkan: Gambar, Nama, No Order, Customer, Spesifikasi
   - Link ke detail order berfungsi
   - **Tombol Edit** membuka modal untuk edit produk custom

7. **Edit Produk Custom**:
   - Klik tombol "Edit" pada produk custom
   - Modal terbuka dengan data produk yang ada
   - Edit nama produk dan spesifikasi
   - **Upload gambar baru** (opsional)
   - Submit form untuk update
   - Verifikasi perubahan tersimpan

8. **Update Stok**:
   - Klik tombol "Update" pada produk
   - Ubah nilai stok
   - Verifikasi perubahan

### ✅ 8. Catatan Penting

#### A. Data Existing:
- Produk yang sudah ada tetap berfungsi
- Order yang sudah ada tetap berfungsi
- Field base_price dihapus dari database

#### B. Kompatibilitas:
- Semua fitur lain tetap berfungsi
- Relasi antara Order dan Product tetap terjaga
- Manajemen stok tetap berfungsi

#### C. Migrasi:
- Migration sudah dijalankan
- Seeder sudah diupdate
- Data produk sudah diperbarui

## Kesimpulan

Revisi ini berhasil menyederhanakan dan memperbaiki sistem dengan:
- **Menghilangkan field harga** dari produk (harga ditentukan per order)
- **Menghilangkan pilihan kategori produk** dari form tambah/edit produk (otomatis 'tetap')
- **Mempertahankan fitur produk custom** di form tambah order untuk pesanan custom
- **Menambahkan upload gambar** untuk produk custom
- **Membuat halaman produk dengan 2 tab**: Produk Tetap dan Produk Custom
- **Mempertahankan fungsionalitas** manajemen stok dan relasi

Sistem sekarang lebih mudah digunakan dengan:
- **Form produk yang sederhana** (tanpa harga dan kategori)
- **Form order yang fleksibel** (mendukung produk tetap dan custom dengan gambar)
- **Halaman produk yang terorganisir** (tab terpisah untuk tetap dan custom)
- **Workflow yang jelas** untuk kedua jenis pesanan
- **Manajemen produk custom** yang terintegrasi dengan orders
