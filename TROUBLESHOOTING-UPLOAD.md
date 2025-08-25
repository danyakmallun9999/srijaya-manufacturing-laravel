# Troubleshooting Upload Gambar Produk Custom

## Masalah yang Ditemui
Upload gambar pada halaman create order untuk produk custom ditolak dengan pesan "tidak sesuai aturan".

## Solusi yang Telah Diterapkan

### 1. **Perbaikan Validasi**
- ✅ **Ukuran maksimal**: Ditingkatkan dari 2MB ke 5MB
- ✅ **Format yang didukung**: jpeg, png, jpg, gif, webp
- ✅ **Error message yang lebih jelas** dalam bahasa Indonesia
- ✅ **Validasi terpisah** untuk error handling yang lebih baik

### 2. **Perbaikan Error Handling**
- ✅ **Debugging log** untuk melihat detail error
- ✅ **Try-catch** untuk menangani error upload
- ✅ **Error display** di form dengan styling yang sesuai
- ✅ **Pesan error yang informatif**

### 3. **Perbaikan Infrastruktur**
- ✅ **Direktori upload**: `storage/app/public/custom-products/`
- ✅ **Storage link**: Memastikan `public/storage` terhubung
- ✅ **Permission**: Direktori dengan permission yang benar

## Cara Test Upload

### 1. **Test dengan File Kecil**
```bash
# Buat file test kecil
echo "test" > test.jpg
# Upload file ini untuk test
```

### 2. **Cek Log Error**
```bash
# Monitor log real-time
tail -f storage/logs/laravel.log

# Atau cek log terakhir
tail -20 storage/logs/laravel.log
```

### 3. **Cek Konfigurasi PHP**
```bash
# Cek setting upload PHP
php -i | grep -E "(upload_max_filesize|post_max_size|max_file_uploads)"
```

## Format File yang Didukung

| Format | Extension | MIME Type |
|--------|-----------|-----------|
| JPEG | .jpg, .jpeg | image/jpeg |
| PNG | .png | image/png |
| GIF | .gif | image/gif |
| WebP | .webp | image/webp |

## Batasan File

- **Ukuran maksimal**: 5MB
- **Format**: Gambar saja (image/*)
- **Jumlah file**: 1 file per order

## Debugging Steps

### 1. **Cek Request Data**
Log akan menampilkan:
```php
[
    'product_type' => 'custom',
    'has_custom_image' => true,
    'custom_image_name' => 'filename.jpg',
    'custom_image_size' => 123456,
    'custom_image_mime' => 'image/jpeg'
]
```

### 2. **Cek Upload Process**
Log akan menampilkan:
```php
[
    'original_name' => 'filename.jpg',
    'size' => 123456,
    'mime_type' => 'image/jpeg'
]
```

### 3. **Cek Success/Error**
- **Success**: `Image uploaded successfully: ['path' => 'custom-products/filename.jpg']`
- **Error**: `Image upload failed: ['error' => 'Error message']`

## Common Issues & Solutions

### 1. **File Too Large**
**Error**: "Ukuran gambar maksimal 5MB"
**Solution**: Kompres gambar atau gunakan file yang lebih kecil

### 2. **Invalid Format**
**Error**: "Format gambar harus: jpeg, png, jpg, gif, atau webp"
**Solution**: Konversi ke format yang didukung

### 3. **Not an Image**
**Error**: "File harus berupa gambar"
**Solution**: Pastikan file adalah gambar yang valid

### 4. **Storage Permission**
**Error**: "Gagal mengupload gambar: Permission denied"
**Solution**: 
```bash
chmod -R 775 storage/app/public/
chown -R www-data:www-data storage/app/public/
```

### 5. **Directory Not Found**
**Error**: "Gagal mengupload gambar: Directory not found"
**Solution**:
```bash
mkdir -p storage/app/public/custom-products
php artisan storage:link
```

## Testing Checklist

- [ ] File format didukung (jpeg, png, jpg, gif, webp)
- [ ] File size < 5MB
- [ ] File adalah gambar valid
- [ ] Storage link terhubung
- [ ] Direktori custom-products ada
- [ ] Permission direktori benar
- [ ] Log error terlihat di `storage/logs/laravel.log`

## Jika Masih Bermasalah

1. **Cek log error** di `storage/logs/laravel.log`
2. **Test dengan file yang berbeda** (format dan ukuran)
3. **Cek browser console** untuk error JavaScript
4. **Cek network tab** di browser developer tools
5. **Test dengan file yang sangat kecil** (< 100KB)

## Contact Support

Jika masalah masih berlanjut, berikan informasi:
- Format file yang dicoba
- Ukuran file
- Error message lengkap
- Log error dari `storage/logs/laravel.log`
