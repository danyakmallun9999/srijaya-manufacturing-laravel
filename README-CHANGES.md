# Perubahan pada Sistem Laravel

## 1. Pemasukan (Income)

### Perubahan yang Dilakukan:
- **Menghapus logika otomatis mengubah status order**: Status order tidak lagi otomatis berubah menjadi "Closed" ketika pemasukan sudah sesuai dengan harga jual.
- **Status order hanya diubah manual**: Perubahan status hanya dapat dilakukan melalui menu Info Order.

### File yang Diubah:
- `app/Http/Controllers/IncomeController.php`: Menghapus logika otomatis update status order

## 2. Produk (Product)

### Fitur Baru yang Ditambahkan:

#### A. Field Baru pada Tabel Products:
- `image`: Gambar produk (nullable)
- `stock`: Stok produk (default: 0)
- `model`: Model produk (nullable)
- `wood_type`: Jenis kayu (nullable)
- `details`: Detail tambahan produk (nullable)
- `product_category`: Kategori produk (enum: 'tetap', 'custom')
- `base_price`: Harga dasar produk (nullable)

#### B. Fitur Upload Gambar:
- Upload gambar produk dengan validasi format dan ukuran
- Penyimpanan gambar di storage/app/public/products/
- Placeholder SVG untuk produk tanpa gambar

#### C. Manajemen Stok:
- Update stok produk melalui modal popup
- Stok hanya berlaku untuk produk tetap (product_category = 'tetap')
- Produk custom tidak memiliki manajemen stok

#### D. Relasi dengan Order:
- Menambahkan field `product_id` pada tabel orders
- Relasi antara Order dan Product untuk produk tetap

### File yang Dibuat/Diupload:
- `database/migrations/2025_08_25_000000_add_fields_to_products_table.php`
- `database/migrations/2025_08_25_000001_add_product_id_to_orders_table.php`
- `database/seeders/ProductSeeder.php`
- `public/images/no-image.svg`

### File yang Diubah:
- `app/Models/Product.php`: Menambahkan field baru, method stok, dan relasi
- `app/Models/Order.php`: Menambahkan relasi dengan Product
- `app/Http/Controllers/ProductController.php`: Menangani upload gambar dan format harga
- `routes/web.php`: Menambahkan route update stok
- `resources/views/products/create.blade.php`: Form dengan field baru
- `resources/views/products/edit.blade.php`: Form edit dengan field baru
- `resources/views/products/index.blade.php`: Tampilan dengan field baru dan modal stok
- `database/seeders/DatabaseSeeder.php`: Menambahkan ProductSeeder

## 3. Mekanisme Stok

### Cara Kerja:
1. **Produk Tetap**: Memiliki stok yang dapat dikelola
   - Stok dapat diupdate melalui halaman produk
   - Stok tersisa dapat digunakan untuk produksi berikutnya
   - Tombol "Update" stok hanya muncul untuk produk tetap

2. **Produk Custom**: Tidak memiliki manajemen stok
   - Tidak ada field stok yang relevan
   - Setiap order custom dibuat secara individual

### Implementasi:
- Method `isFixed()` pada model Product untuk mengecek kategori produk
- Method `hasStock()`, `decreaseStock()`, `increaseStock()` untuk manajemen stok
- Modal popup untuk update stok di halaman index produk

## 4. Instalasi dan Setup

### Langkah-langkah:
1. **Jalankan Migration**:
   ```bash
   php artisan migrate
   ```

2. **Buat Symbolic Link Storage** (jika belum):
   ```bash
   php artisan storage:link
   ```

3. **Jalankan Seeder** (opsional):
   ```bash
   php artisan db:seed --class=ProductSeeder
   ```

### Catatan Penting:
- Pastikan folder `storage/app/public/products/` memiliki permission yang benar
- File placeholder `public/images/no-image.svg` harus ada
- Format harga menggunakan pemisah ribuan (contoh: 1.500.000)

## 5. Testing

### Yang Dapat Ditest:
1. **Upload Produk Baru**: 
   - Isi semua field termasuk gambar
   - Pilih kategori produk (tetap/custom)
   - Input harga dengan format ribuan

2. **Update Stok**:
   - Klik tombol "Update" pada produk tetap
   - Ubah nilai stok melalui modal
   - Verifikasi perubahan di database

3. **Pemasukan**:
   - Tambah pemasukan untuk order
   - Verifikasi status order tidak berubah otomatis
   - Ubah status manual melalui Info Order

4. **Relasi Produk-Order**:
   - Buat order dengan produk tetap
   - Verifikasi relasi product_id terisi
   - Test dengan produk custom

## 6. Struktur Database

### Tabel Products (Baru):
```sql
- id (primary key)
- name (string)
- description (text, nullable)
- image (string, nullable)
- stock (integer, default: 0)
- model (string, nullable)
- wood_type (string, nullable)
- details (text, nullable)
- product_category (enum: 'tetap', 'custom')
- base_price (decimal, nullable)
- bom_master (json, nullable)
- created_at, updated_at
```

### Tabel Orders (Diupdate):
```sql
- product_id (foreign key, nullable) - BARU
- ... field lainnya tetap sama
```

## 7. Keamanan dan Validasi

### Validasi Input:
- Gambar: hanya format jpeg, png, jpg, gif, maksimal 2MB
- Stok: integer positif
- Harga: format string dengan pemisah ribuan
- Kategori: enum dengan nilai yang valid

### Keamanan:
- Validasi file upload
- Sanitasi input harga
- Authorization untuk update stok
- Soft delete untuk gambar lama saat update
