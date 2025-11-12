# 📝 Panduan Menambah Data Kegiatan - Sipekan

## 3 Cara Menambah Data Kegiatan

---

## 1️⃣ Via Filament Admin Panel (Recommended)

### Lokasi Admin Panel

```
URL: http://127.0.0.1:8000/admin
Login: gunakan credentials yang terdaftar
```

### Langkah-Langkah:

1. Login ke admin panel
2. Pergi ke menu **"Kegiatan"** atau **"Kelola Kegiatan"**
3. Klik tombol **"Tambah Kegiatan"** / **"New Kegiatan"**
4. Isi form dengan data kegiatan:
   - **Nama Kegiatan**: Judul kegiatan (contoh: "Imunisasi Batch 1")
   - **Tanggal**: Pilih tanggal kegiatan
   - **Waktu Mulai**: Jam mulai kegiatan
   - **Waktu Selesai**: Jam selesai kegiatan
   - **Lokasi**: Tempat kegiatan (contoh: "Jl. Merdeka No. 45")
   - **Posyandu**: Nama posyandu (contoh: "Posyandu Belwis")
   - **Kategori Kegiatan**: Pilih dari dropdown:
     - Imunisasi
     - Penimbangan
     - Penyuluhan
     - Posyandu
   - **Pemateri**: Nama pembicara/pengajar
   - **Target Peserta**: Jumlah peserta yang ditargetkan
   - **Status**: Pilih dari dropdown:
     - Terjadwal
     - Selesai
     - Dibatalkan
   - **Deskripsi**: Penjelasan detail kegiatan (opsional)
5. Klik **"Simpan"** / **"Save"**

### Keuntungan:

- ✅ User-friendly interface
- ✅ Validation otomatis
- ✅ Real-time preview
- ✅ Tidak perlu technical knowledge

---

## 2️⃣ Via API dengan cURL/Postman (Developer)

### URL Endpoint

```
POST http://127.0.0.1:8000/api/kegiatan
```

### Headers Required

```
Content-Type: application/json
Accept: application/json
Authorization: Bearer {sanctum_token}
```

### Request Body (JSON)

```json
{
  "nama_kegiatan": "Imunisasi Batch 1",
  "tanggal": "2025-11-20",
  "waktu_mulai": "09:00:00",
  "waktu_selesai": "12:00:00",
  "lokasi": "Jl. Merdeka No. 45, Tangerang",
  "posyandu": "Posyandu Belwis",
  "kategori_kegiatan": "imunisasi",
  "pemateri": "Dr. Siti Nurhaliza",
  "target_peserta": "50",
  "status": "terjadwal",
  "deskripsi": "Memberikan imunisasi lengkap untuk anak-anak..."
}
```

### cURL Example

```bash
curl -X POST http://127.0.0.1:8000/api/kegiatan \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "nama_kegiatan": "Imunisasi Batch 1",
    "tanggal": "2025-11-20",
    "waktu_mulai": "09:00:00",
    "waktu_selesai": "12:00:00",
    "lokasi": "Jl. Merdeka No. 45, Tangerang",
    "posyandu": "Posyandu Belwis",
    "kategori_kegiatan": "imunisasi",
    "pemateri": "Dr. Siti Nurhaliza",
    "target_peserta": "50",
    "status": "terjadwal",
    "deskripsi": "Memberikan imunisasi lengkap untuk anak-anak..."
  }'
```

### Postman Setup

1. Method: **POST**
2. URL: `http://127.0.0.1:8000/api/kegiatan`
3. Tab **Headers**:
   - Key: `Authorization` → Value: `Bearer {your_token}`
   - Key: `Content-Type` → Value: `application/json`
4. Tab **Body**:
   - Select: **raw** → **JSON**
   - Paste JSON request body
5. Click **Send**

### Response Success (201 Created)

```json
{
  "success": true,
  "message": "Kegiatan berhasil dibuat",
  "data": {
    "id": 5,
    "nama_kegiatan": "Imunisasi Batch 1",
    "tanggal": "2025-11-20",
    "waktu_mulai": "09:00:00",
    "waktu_selesai": "12:00:00",
    "lokasi": "Jl. Merdeka No. 45, Tangerang",
    "posyandu": "Posyandu Belwis",
    "kategori_kegiatan": "imunisasi",
    "pemateri": "Dr. Siti Nurhaliza",
    "target_peserta": "50",
    "status": "terjadwal",
    "deskripsi": "Memberikan imunisasi lengkap untuk anak-anak...",
    "created_at": "2025-11-12T10:30:45.000000Z",
    "updated_at": "2025-11-12T10:30:45.000000Z"
  }
}
```

---

## 3️⃣ Via Database Query Langsung (Advanced)

### Koneksi ke Database

```bash
# Via command line MySQL
mysql -u root -p nama_database_sipekan

# Atau gunakan phpMyAdmin
http://localhost/phpmyadmin
```

### Insert Query

```sql
INSERT INTO kegiatans
(nama_kegiatan, tanggal, waktu_mulai, waktu_selesai, lokasi, posyandu, kategori_kegiatan, pemateri, target_peserta, status, deskripsi, created_at, updated_at)
VALUES
('Imunisasi Batch 1', '2025-11-20', '09:00:00', '12:00:00', 'Jl. Merdeka No. 45', 'Posyandu Belwis', 'imunisasi', 'Dr. Siti Nurhaliza', 50, 'terjadwal', 'Memberikan imunisasi lengkap...', NOW(), NOW());
```

### Insert Multiple Data

```sql
INSERT INTO kegiatans (nama_kegiatan, tanggal, waktu_mulai, waktu_selesai, lokasi, posyandu, kategori_kegiatan, pemateri, target_peserta, status, deskripsi, created_at, updated_at) VALUES
('Imunisasi Batch 1', '2025-11-20', '09:00:00', '12:00:00', 'Jl. Merdeka No. 45', 'Posyandu Belwis', 'imunisasi', 'Dr. Siti Nurhaliza', 50, 'terjadwal', 'Imunisasi lengkap...', NOW(), NOW()),
('Penimbangan Rutin', '2025-11-21', '10:00:00', '11:30:00', 'Balai Desa', 'Posyandu Melati', 'penimbangan', 'Bidan Siti', 30, 'terjadwal', 'Penimbangan anak...', NOW(), NOW()),
('Edukasi Gizi', '2025-11-22', '14:00:00', '16:00:00', 'Puskesmas Kec A', 'Puskesmas', 'penyuluhan', 'Dr. Mira', 80, 'terjadwal', 'Edukasi gizi ibu hamil...', NOW(), NOW());
```

### Verify Data Inserted

```sql
SELECT * FROM kegiatans WHERE nama_kegiatan = 'Imunisasi Batch 1';
```

---

## 🔄 Update Data Kegiatan

### Via API

```bash
PUT http://127.0.0.1:8000/api/kegiatan/{id}
```

Request body sama seperti create, tapi hanya perlu field yang ingin diubah.

```bash
curl -X PUT http://127.0.0.1:8000/api/kegiatan/5 \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "nama_kegiatan": "Imunisasi Batch 1 - UPDATED",
    "status": "selesai"
  }'
```

### Via Database

```sql
UPDATE kegiatans
SET nama_kegiatan = 'Imunisasi Batch 1 - UPDATED',
    status = 'selesai',
    updated_at = NOW()
WHERE id = 5;
```

---

## 🗑️ Delete Data Kegiatan

### Via API

```bash
DELETE http://127.0.0.1:8000/api/kegiatan/{id}
```

```bash
curl -X DELETE http://127.0.0.1:8000/api/kegiatan/5 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Via Database

```sql
DELETE FROM kegiatans WHERE id = 5;
```

---

## 📋 Field Reference

| Field               | Type         | Required | Example                   | Notes                                        |
| ------------------- | ------------ | -------- | ------------------------- | -------------------------------------------- |
| `nama_kegiatan`     | string (100) | ✅       | "Imunisasi Batch 1"       | Judul kegiatan                               |
| `tanggal`           | date         | ✅       | "2025-11-20"              | Format YYYY-MM-DD                            |
| `waktu_mulai`       | time         | ❌       | "09:00:00"                | Format HH:MM:SS                              |
| `waktu_selesai`     | time         | ❌       | "12:00:00"                | Format HH:MM:SS                              |
| `lokasi`            | string (100) | ✅       | "Jl. Merdeka No. 45"      | Alamat kegiatan                              |
| `posyandu`          | string (100) | ❌       | "Posyandu Belwis"         | Nama posyandu                                |
| `kategori_kegiatan` | enum         | ✅       | "imunisasi"               | imunisasi, penimbangan, penyuluhan, posyandu |
| `pemateri`          | string (100) | ❌       | "Dr. Siti"                | Nama pembicara                               |
| `target_peserta`    | int          | ❌       | 50                        | Jumlah target                                |
| `status`            | enum         | ❌       | "terjadwal"               | terjadwal, selesai, dibatalkan               |
| `deskripsi`         | text         | ❌       | "Memberikan imunisasi..." | Penjelasan detail                            |

---

## ✅ Validation Rules

```php
'nama_kegiatan' => 'required|string|max:200',
'deskripsi' => 'nullable|string',
'tanggal' => 'required|date',
'waktu_mulai' => 'nullable|date_format:H:i:s',
'waktu_selesai' => 'nullable|date_format:H:i:s',
'lokasi' => 'required|string|max:200',
'posyandu' => 'nullable|string|max:100',
'kategori_kegiatan' => 'required|in:imunisasi,penimbangan,penyuluhan,posyandu',
'pemateri' => 'nullable|string|max:100',
'target_peserta' => 'nullable|integer',
'status' => 'nullable|in:terjadwal,selesai,dibatalkan',
```

---

## 🔍 Troubleshooting

### Problem: "Validation failed"

**Solution**:

- Check required fields semua terisi
- Check enum values: hanya gunakan `imunisasi`, `penimbangan`, `penyuluhan`, `posyandu`
- Check date format: gunakan `YYYY-MM-DD` untuk tanggal
- Check string length: max 100-200 chars sesuai field

### Problem: "Unauthorized" (401)

**Solution**:

- Pastikan token Sanctum valid
- Check Authorization header: `Bearer {token}`
- Login dulu untuk get token

### Problem: "Data tidak tampil di frontend"

**Solution**:

- Tunggu beberapa detik (ada cache)
- Refresh halaman F5
- Clear browser cache Ctrl+Shift+Delete
- Check console untuk error

### Problem: "Duplicate entry error"

**Solution**:

- Ada constraint unique di database
- Check field mana yang conflict
- Gunakan data yang berbeda

---

## 📊 Sample Data untuk Testing

```json
{
  "nama_kegiatan": "Imunisasi Batch 1",
  "tanggal": "2025-11-20",
  "waktu_mulai": "09:00:00",
  "waktu_selesai": "12:00:00",
  "lokasi": "Jl. Merdeka No. 45, Kecamatan A, Kelurahan Indah Jaya, Kabupaten Tangerang",
  "posyandu": "Posyandu Belwis",
  "kategori_kegiatan": "imunisasi",
  "pemateri": "Dr. Siti Nurhaliza, Spesialis Anak",
  "target_peserta": "50",
  "status": "terjadwal",
  "deskripsi": "Memberikan imunisasi anti stunting dengan vaksin lengkap untuk mencegah penyakit menular dan memastikan pertumbuhan optimal anak-anak."
}
```

---

## 🎯 Best Practices

1. **Validate tanggal**: Pastikan tanggal tidak di masa lalu (untuk kegiatan terjadwal)
2. **Format waktu**: Gunakan 24-jam format (09:00 bukan 9:00 AM)
3. **Lokasi detail**: Tuliskan alamat lengkap dengan kecamatan & kabupaten
4. **Deskripsi jelas**: Jelaskan tujuan & manfaat kegiatan
5. **Target realistis**: Set target peserta yang feasible
6. **Status sesuai**: Update status saat kegiatan selesai/dibatalkan

---

## 📞 Quick Reference

| Action       | Method | Endpoint                      | Auth |
| ------------ | ------ | ----------------------------- | ---- |
| List semua   | GET    | `/api/public/kegiatan`        | ❌   |
| Detail satu  | GET    | `/api/public/kegiatan/{id}`   | ❌   |
| Create baru  | POST   | `/api/kegiatan`               | ✅   |
| Update       | PUT    | `/api/kegiatan/{id}`          | ✅   |
| Delete       | DELETE | `/api/kegiatan/{id}`          | ✅   |
| Kelola admin | Web UI | `http://127.0.0.1:8000/admin` | ✅   |

---

**Last Updated**: 12 November 2025  
**Status**: Ready for Production
