# Dokumentasi Halaman Kegiatan - Sipekan Frontend

## 📋 Ringkasan

Halaman Kegiatan sudah sepenuhnya terintegrasi antara **sipekan** (backend Laravel) dan **sipekan-frontend** (frontend React). Halaman ini menampilkan daftar kegiatan kesehatan dari database dengan fitur pencarian, filter, dan detail view.

---

## 🏗️ Arsitektur Sistem

### Backend (Laravel - Sipekan)

- **Model**: `app/Models/Kegiatan.php`
- **Controller**: `app/Http/Controllers/Api/KegiatanController.php`
- **Route API**: `GET /api/public/kegiatan` (public access, tidak perlu autentikasi)
- **Database**: Tabel `kegiatans` dengan field:
  - `id`, `nama_kegiatan`, `tanggal`, `waktu_mulai`, `waktu_selesai`
  - `lokasi`, `kategori_kegiatan`, `status`, `deskripsi`
  - `posyandu`, `pemateri`, `target_peserta`

### Frontend (React - Sipekan-Frontend)

- **Halaman**: `src/pages/Kegiatan.jsx`
- **Service**: `src/services/publicService.js` → method `getKegiatan()`
- **Config**: `src/config/api.js` → axios instance dengan base URL dari `.env`
- **Route**: `http://localhost:5173/kegiatan`

---

## 🔧 File-File Kunci

### 1. Backend - Model Kegiatan

**Path**: `sipekan/app/Models/Kegiatan.php`

```php
class Kegiatan extends Model
{
    protected $fillable = [
        'posyandu', 'nama_kegiatan', 'tanggal', 'waktu_mulai',
        'waktu_selesai', 'lokasi', 'kategori_kegiatan', 'status',
        'deskripsi', 'pemateri', 'target_peserta',
    ];

    // Scope untuk kegiatan yang akan datang
    public function scopeUpcoming($query) { ... }
}
```

### 2. Backend - API Controller

**Path**: `sipekan/app/Http/Controllers/Api/KegiatanController.php`

Endpoints yang tersedia:

- `GET /api/public/kegiatan` - Daftar kegiatan (public)
- `GET /api/public/kegiatan/{id}` - Detail kegiatan (public)
- `GET /api/kegiatan` - Daftar kegiatan (autentikasi)
- `POST /api/kegiatan` - Buat kegiatan baru (autentikasi)
- `PUT /api/kegiatan/{id}` - Update kegiatan (autentikasi)
- `DELETE /api/kegiatan/{id}` - Hapus kegiatan (autentikasi)

**Query Parameters** untuk `/api/public/kegiatan`:

- `?tanggal=2025-11-12` - Filter by tanggal
- `?kategori=imunisasi` - Filter by kategori (imunisasi, penimbangan, penyuluhan, posyandu)
- `?search=keyword` - Cari di nama_kegiatan, deskripsi, lokasi, pemateri
- `?only_future=true` - Hanya kegiatan dengan tanggal >= hari ini

### 3. Frontend - Service API

**Path**: `sipekan-frontend/src/services/publicService.js`

```javascript
export const publicService = {
  async getKegiatan(params = {}) {
    const response = await publicApi.get('/public/kegiatan', { params });
    return {
      success: true,
      data: response.data.data,
      message: response.data.message
    };
  },

  async getKegiatanById(id) {
    const response = await publicApi.get(`/public/kegiatan/${id}`);
    return { ... };
  }
};
```

### 4. Frontend - Halaman Kegiatan

**Path**: `sipekan-frontend/src/pages/Kegiatan.jsx`

Fitur:

- ✅ List kegiatan dengan card yang informatif
- ✅ Search kegiatan (nama, deskripsi, lokasi, pemateri)
- ✅ Filter by tanggal (date picker)
- ✅ Filter by kategori (dropdown select)
- ✅ Detail view (expandable card)
- ✅ Loading state dengan spinner
- ✅ Error handling dengan fallback data
- ✅ Empty state jika tidak ada data
- ✅ Reset filter button

### 5. Frontend - Routing

**Path**: `sipekan-frontend/src/App.jsx`

```javascript
<Route path="/kegiatan" element={<Kegiatan />} />
```

---

## 🚀 Cara Mengakses

### Development

1. **Backend** sudah berjalan di: `http://127.0.0.1:8000`
2. **Frontend** sudah berjalan di: `http://localhost:5173`
3. **Halaman Kegiatan**: http://localhost:5173/kegiatan

### Test API Secara Langsung

```bash
# Get semua kegiatan
curl -X GET "http://127.0.0.1:8000/api/public/kegiatan"

# Get kegiatan yang akan datang saja
curl -X GET "http://127.0.0.1:8000/api/public/kegiatan?only_future=true"

# Filter by kategori
curl -X GET "http://127.0.0.1:8000/api/public/kegiatan?kategori=imunisasi"

# Search kegiatan
curl -X GET "http://127.0.0.1:8000/api/public/kegiatan?search=imunisasi"

# Get detail kegiatan (ID 1)
curl -X GET "http://127.0.0.1:8000/api/public/kegiatan/1"
```

---

## 📊 Struktur Data Response

### Response sukses `/api/public/kegiatan`

```json
{
  "success": true,
  "message": "Data kegiatan berhasil diambil",
  "data": [
    {
      "id": 1,
      "posyandu": "Posyandu Belwis",
      "nama_kegiatan": "Imunisasi",
      "tanggal": "2025-11-07",
      "waktu_mulai": "12:00:00",
      "waktu_selesai": "17:00:00",
      "lokasi": "Jalan Lapas Raya",
      "kategori_kegiatan": "imunisasi",
      "status": "terjadwal",
      "deskripsi": "Imunisasi anak...",
      "pemateri": "dr. Gilang Padang",
      "target_peserta": "1 peserta",
      "created_at": "2025-11-07T12:54:43.000000Z",
      "updated_at": "2025-11-07T12:54:43.000000Z"
    }
  ]
}
```

---

## ⚙️ Konfigurasi Environment

### File `.env` dan `.env.development`

**Path**: `sipekan-frontend/.env`

```properties
# API Base URL (tanpa /api suffix, akan ditambah otomatis)
VITE_API_URL=http://127.0.0.1:8000

# Admin features (untuk development)
VITE_ENABLE_ADMIN=true/false
```

---

## 🎨 Fitur-Fitur Halaman

### 1. Filter Pencarian

- **Search Box**: Cari kegiatan berdasarkan nama, deskripsi, lokasi, atau pemateri
- **Date Picker**: Filter kegiatan berdasarkan tanggal tertentu
- **Category Dropdown**: Filter berdasarkan kategori (Imunisasi, Penyuluhan, Penimbangan, Posyandu)
- **Reset Button**: Menghapus semua filter

### 2. Card Display

Setiap kegiatan ditampilkan dalam card yang menunjukkan:

- 🏷️ **Nama Kegiatan** + Badge kategori
- 📝 **Deskripsi** (short preview)
- 📅 **Tanggal & Jam**
- 📍 **Lokasi**
- 👥 **Target Peserta**

### 3. Expand Detail View

Klik pada card untuk membuka detail lengkap:

- Jadwal lengkap dengan waktu
- Lokasi/Tempat
- Penanggung jawab / Pemateri
- Target Peserta
- Status kegiatan

### 4. Loading & Error States

- **Loading**: Spinner animation saat fetch data
- **Error**: Pesan error dengan fallback data contoh
- **Empty**: State khusus jika tidak ada data yang cocok dengan filter

---

## 🔄 Alur Data (Data Flow)

```
User membuka http://localhost:5173/kegiatan
         ↓
   Halaman Kegiatan.jsx dimuat
         ↓
   useEffect → fetchKegiatan()
         ↓
   publicService.getKegiatan({ only_future: true })
         ↓
   Axios request ke: http://127.0.0.1:8000/api/public/kegiatan?only_future=true
         ↓
   Backend KegiatanController.index() di route /api/public/kegiatan
         ↓
   Query database table 'kegiatans'
         ↓
   Return JSON response
         ↓
   Frontend menyimpan di state kegiatanData
         ↓
   Render list kegiatan ke UI
```

---

## ✅ Testing Checklist

- [x] Backend API endpoint `/api/public/kegiatan` mengembalikan data
- [x] Frontend terhubung ke backend via publicService
- [x] Halaman Kegiatan loaded dan menampilkan data dari database
- [x] Filter pencarian bekerja
- [x] Filter kategori bekerja
- [x] Date picker filter bekerja
- [x] Expand detail view bekerja
- [x] Error handling & fallback data bekerja
- [x] Responsive design untuk mobile & desktop

---

## 🐛 Troubleshooting

### 1. Halaman tidak menampilkan data

**Solusi**:

- Pastikan backend Laravel sudah berjalan: `http://127.0.0.1:8000` (lihat indicator "Server running on...")
- Check console browser (F12 → Console) untuk error message
- Verifikasi VITE_API_URL di `.env.development` sudah correct: `http://127.0.0.1:8000`

### 2. Data tidak dari database tapi dari default data

**Solusi**:

- Lihat console browser untuk error API
- Pastikan tabel `kegiatans` punya data (minimum 1 record)
- Run migration jika diperlukan: `php artisan migrate`
- Check database via Filament admin atau phpmyadmin

### 3. Filter tidak bekerja

**Solusi**:

- Clear browser cache
- Cek tipe data field di halaman vs response API (contoh: tanggal harus format `YYYY-MM-DD`)
- Buka DevTools → Network untuk lihat response API apa yang diterima

### 4. CORS Error / Mixed Content Error

**Solusi**:

- Pastikan backend dan frontend menggunakan protocol yang sama (http atau https)
- Check CORS configuration di `config/cors.php` di backend
- Pastikan `VITE_API_URL` di `.env.development` benar

---

## 📝 Catatan Penting

1. **Public Access**: Halaman kegiatan bisa diakses tanpa login
2. **Data Source**: Semua data diambil dari tabel `kegiatans` di database
3. **Real-time**: Setiap refresh halaman akan fetch data terbaru dari backend
4. **Caching**: Tidak ada caching, setiap request ke API akan hit database
5. **Kategori Valid**: imunisasi, penimbangan, penyuluhan, posyandu

---

## 🔐 API Security

- ❌ Endpoint `/api/public/kegiatan` tidak memerlukan autentikasi
- ✅ Endpoint `/api/kegiatan` (dengan auth) memerlukan token Sanctum
- ⚠️ CORS harus dikonfigurasi di backend untuk allow frontend domain

---

## 📚 Referensi File

| File                                                      | Purpose             |
| --------------------------------------------------------- | ------------------- |
| `sipekan/app/Models/Kegiatan.php`                         | Model database      |
| `sipekan/app/Http/Controllers/Api/KegiatanController.php` | API logic           |
| `sipekan/routes/api.php`                                  | API routes          |
| `sipekan-frontend/src/pages/Kegiatan.jsx`                 | Halaman UI          |
| `sipekan-frontend/src/services/publicService.js`          | API service layer   |
| `sipekan-frontend/src/config/api.js`                      | Axios configuration |
| `sipekan-frontend/src/App.jsx`                            | Routing setup       |

---

## ✨ Fitur yang Bisa Ditambahkan Kedepannya

- [ ] Pagination untuk list kegiatan
- [ ] Sort by tanggal, nama, kategori
- [ ] Export kegiatan ke PDF/Excel
- [ ] Calendar view untuk kegiatan
- [ ] Notifikasi/reminder kegiatan
- [ ] Daftar peserta kegiatan
- [ ] Rating/review kegiatan
- [ ] Share kegiatan ke social media
- [ ] Map view untuk lokasi kegiatan
- [ ] Mobile app notification

---

## 📞 Support

Untuk pertanyaan atau issue, silakan cek:

1. **Backend logs**: `storage/logs/laravel.log`
2. **Browser console**: F12 → Console tab
3. **Network tab**: F12 → Network tab untuk lihat API calls

---

**Last Updated**: 12 November 2025  
**Status**: ✅ Production Ready
