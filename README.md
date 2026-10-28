# BTNG Backend — REST API Showroom Mobil

Tugas Akhir **BTNG Kelas Website Backend**.
RESTful API untuk manajemen data showroom mobil, dibangun dengan **Laravel 12** + **MySQL**, dilengkapi dokumentasi interaktif **Scramble** dan di-deploy ke **Clever Cloud**.

- **Nama:** `Edgar Lieno`
- **Live API:** `https://[app-name].cleverapps.io/api/mobil`
- **Dokumentasi (Scramble):** `https://[app-name].cleverapps.io/docs/api`

## Fitur

- CRUD lengkap entitas **Mobil** (5 endpoint RESTful)
- Respons JSON seragam via **API Resource** dengan amplop `message` + `data`
- Validasi `$request->validate()` dengan pesan Bahasa Indonesia + status code semantik (200, 201, 404, 422)
- Dokumentasi API interaktif (*Try it out*) via **Scramble** di `/docs/api`
- Migration, Seeder (10 data dummy), `.env.example`
- **Bonus:** pencarian & filter pada `GET /api/mobil`

## Setup Lokal

Prasyarat: PHP 8.2+, Composer, MySQL (Laragon / XAMPP).

```bash
git clone https://github.com/Edgar Lieno/BTNG-Backend-Edgar Lieno.git
cd BTNG-Backend-Edgar Lieno

composer install
cp .env.example .env
php artisan key:generate
```

Buat database kosong bernama `btng_showroom` di MySQL, sesuaikan `DB_*` di `.env`, lalu:

```bash
php artisan migrate --seed
php artisan serve
```

- API: http://localhost:8000/api/mobil
- Dokumentasi: http://localhost:8000/docs/api

## Daftar Endpoint

| Method    | Endpoint           | Deskripsi                         | Status Sukses |
|-----------|--------------------|-----------------------------------|---------------|
| GET       | `/api/mobil`       | Daftar seluruh mobil              | 200 OK        |
| GET       | `/api/mobil/{id}`  | Detail satu mobil                 | 200 OK        |
| POST      | `/api/mobil`       | Tambah mobil baru                 | 201 Created   |
| PUT/PATCH | `/api/mobil/{id}`  | Perbarui data mobil               | 200 OK        |
| DELETE    | `/api/mobil/{id}`  | Hapus mobil                       | 200 OK        |

Query parameter `GET /api/mobil`: `search`, `brand`, `status`, `transmisi`, `bahan_bakar`
Contoh: `/api/mobil?brand=Toyota&status=tersedia` atau `/api/mobil?search=Avanza`

### Atribut Mobil

| Field         | Tipe    | Keterangan                          |
|---------------|---------|-------------------------------------|
| `nama`        | string  | Model mobil                         |
| `brand`       | string  | Merk                                |
| `harga`       | integer | Harga dalam Rupiah                  |
| `tahun`       | integer | Tahun rakit                         |
| `transmisi`   | enum    | `Manual` / `Matic` / `CVT`          |
| `bahan_bakar` | enum    | `Bensin` / `Diesel` / `Hybrid` / `Listrik` |
| `status`      | enum    | `tersedia` / `sold`                 |
| `foto`        | string  | URL foto (opsional)                 |

### Contoh Respons

**201 Created** — `POST /api/mobil`

```json
{
  "message": "Data mobil berhasil ditambahkan",
  "data": {
    "id": 11,
    "nama": "Civic RS Turbo",
    "brand": "Honda",
    "harga": 615000000,
    "tahun": 2023,
    "transmisi": "CVT",
    "bahan_bakar": "Bensin",
    "status": "tersedia",
    "foto": null
  }
}
```

**422 Unprocessable Content** — validasi gagal

```json
{
  "message": "Kolom nama wajib diisi. (and 1 more error)",
  "errors": {
    "nama": ["Kolom nama wajib diisi."],
    "harga": ["Kolom harga harus berupa angka."]
  }
}
```

**404 Not Found**

```json
{ "message": "Data mobil tidak ditemukan" }
```

## Deployment (Clever Cloud)

1. Push repository ke GitHub, buat aplikasi **PHP** di Clever Cloud dan hubungkan ke repo.
2. Isi environment variable (Clever Cloud → Environment variables):

   ```
   APP_NAME=BTNG Showroom Mobil API
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=<hasil php artisan key:generate --show>
   APP_URL=https://[app-name].cleverapps.io
   DB_CONNECTION=mysql
   DB_HOST=...  DB_PORT=...  DB_DATABASE=...  DB_USERNAME=...  DB_PASSWORD=...
   SESSION_DRIVER=file
   CACHE_STORE=file
   CC_WEBROOT=/public
   CC_PRE_RUN_HOOK=php artisan migrate --force && php artisan db:seed --force
   ```
3. File `.clever.cloud/php.json` sudah mengatur webroot ke `/public`.
4. Deploy, lalu buka `/docs/api`.

## Struktur Utama

```
app/Http/Controllers/MobilController.php   # logika CRUD + validasi
app/Http/Resources/MobilResource.php       # format respons JSON
app/Models/Mobil.php                       # model Eloquent ($fillable)
app/Providers/AppServiceProvider.php       # Gate viewApiDocs (Scramble)
bootstrap/app.php                          # penanganan error JSON & trust proxy
database/migrations/                       # skema tabel mobils
database/seeders/MobilSeeder.php           # 10 data dummy
routes/api.php                             # apiResource('mobil')
.clever.cloud/php.json                     # konfigurasi deploy Clever Cloud
```
