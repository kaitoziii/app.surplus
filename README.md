# Surplus API - Food Waste Reduction Platform

Surplus adalah backend API untuk platform manajemen stok makanan yang bertujuan mengurangi pemborosan makanan (food waste) melalui teknologi **Dynamic Discounting** dan pencarian berbasis lokasi (**Geo-discovery**).

## Fitur Utama

- **Real-time Dynamic Discounting**: Menghitung harga diskon secara otomatis berdasarkan sisa waktu (expiry), jumlah stok, dan kategori produk.
- **Geo-discovery Engine**: Memungkinkan pengguna menemukan toko terdekat yang memiliki surplus makanan dalam radius tertentu.
- **Product Management**: Katalog produk dengan filter urgensi (mendekati batas waktu penjemputan).
- **Cart System**: API lengkap untuk manajemen keranjang belanja.

## Teknologi

- **Backend**: Laravel 11/13
- **Database**: MySQL/PostgreSQL
- **AI/ML Logic**:
  - `DynamicDiscountService`: Algoritma diskon multi-faktor.
  - `GeospatialService`: Spherical distance (Haversine) calculation.

## Instalasi

1. **Clone repositori**:
   ```bash
   git clone <repository-url>
   cd surplus-api
   ```

2. **Install dependensi**:
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**:
   ```bash
   cp .env.example .env
   # Update DB_DATABASE, DB_USERNAME, DB_PASSWORD di .env
   ```

4. **Generate Key & Migrate**:
   ```bash
   php artisan key:generate
   php artisan migrate --seed
   ```

5. **Jalankan Aplikasi**:
   ```bash
   php artisan serve
   ```

## Dokumentasi AI

### 1. Dynamic Discounting AI
Logic ini secara otomatis menurunkan harga produk saat mendekati batas waktu penjemputan (*pickup deadline*).
- **Path**: [`app/Services/DynamicDiscountService.php`](file:///home/kaito/Documents/capstone%20surplus%20v2/surplus-api/app/Services/DynamicDiscountService.php)
- **Cara Kerja**:
  - `Time Factor`: Diskon meningkat secara eksponensial seiring waktu berjalan menuju deadline.
  - `Stock Factor`: Stok melimpah mendapatkan diskon tambahan untuk mempercepat penjualan.
  - `Category Multiplier`: Kategori sensitif (sapi, susu, ikan) mendapatkan prioritas diskon lebih tinggi.

### 2. Geo-discovery Engine
Menemukan toko terdekat dalam radius tertentu menggunakan koordinat GPS.
- **Path**: [`app/Services/GeospatialService.php`](file:///home/kaito/Documents/capstone%20surplus%20v2/surplus-api/app/Services/GeospatialService.php)
- **Cara Kerja**: Menggunakan formula Haversine untuk menghitung jarak di atas permukaan bumi yang melengkung.

## API Endpoints Utama

### Dashboard (Consumer)
- `GET /api/dashboard/consumer`: Memberikan statistik penghematan, dampak lingkungan (CO2), transaksi terbaru, dan rekomendasi produk terdekat.
  - *Note*: Memerlukan autentikasi (Bearer Token).

### Products
- `GET /api/products/urgent`: Daftar produk paling mendesak (deadline < 3 jam).
- `GET /api/products/{id}`: Detail produk dengan breakdown diskon.

### Stores
- `GET /api/stores/nearby?lat={lat}&lon={lon}&radius={km}`: Daftar toko terdekat.

### AI Specialized
- `POST /api/ai/discount-calculator`: Simulasi perhitungan diskon tanpa perlu database.
- `GET /api/ai/paths`: List path implementasi AI dalam project.

---
Dikembangkan untuk Capstone Project Team CC26-PS097.
