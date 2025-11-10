# 🚀 Quick Start - Testing Phase 3 API

## 📝 Prerequisites
- Server sudah running: `php artisan serve`
- Database sudah di-migrate
- Punya tool untuk testing API (Postman, Insomnia, atau curl)

---

## 🧪 Test API Endpoints

### 1. Register User (Optional)
```http
POST http://localhost:8000/api/auth/register
Content-Type: application/json

{
    "name": "Admin Test",
    "email": "admin@test.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "admin"
}
```

**Expected Response:**
```json
{
    "success": true,
    "message": "Registrasi berhasil",
    "data": {
        "user": {
            "id": 1,
            "name": "Admin Test",
            "email": "admin@test.com",
            "role": "admin"
        },
        "token": "1|xxxxxxxxxxxxxxxxxxxx"
    }
}
```

---

### 2. Login
```http
POST http://localhost:8000/api/auth/login
Content-Type: application/json

{
    "email": "admin@test.com",
    "password": "password123"
}
```

**Expected Response:**
```json
{
    "success": true,
    "message": "Login berhasil",
    "data": {
        "user": {
            "id": 1,
            "name": "Admin Test",
            "email": "admin@test.com",
            "role": "admin"
        },
        "token": "2|xxxxxxxxxxxxxxxxxxxx"
    }
}
```

**⚠️ IMPORTANT:** Copy token dari response untuk digunakan di request berikutnya!

---

### 3. Get Current User (Protected)
```http
GET http://localhost:8000/api/auth/user
Authorization: Bearer {your_token_here}
```

**Expected Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Admin Test",
        "email": "admin@test.com",
        "role": "admin",
        "kader_info": null
    }
}
```

---

### 4. Logout (Protected)
```http
POST http://localhost:8000/api/auth/logout
Authorization: Bearer {your_token_here}
```

**Expected Response:**
```json
{
    "success": true,
    "message": "Logout berhasil"
}
```

---

## 📦 Test Balita Endpoints (Protected)

### 5. List All Balita
```http
GET http://localhost:8000/api/balita
Authorization: Bearer {your_token_here}
```

### 6. Create Balita
```http
POST http://localhost:8000/api/balita
Authorization: Bearer {your_token_here}
Content-Type: application/json

{
    "nama": "Andi Pratama",
    "jenis_kelamin": "L",
    "tanggal_lahir": "2023-05-15",
    "nama_orang_tua": "Budi Santoso",
    "nik_orang_tua": "3201234567890123",
    "alamat": "Jl. Merdeka No. 123",
    "rt_rw": "001/002",
    "no_telepon_ortu": "081234567890"
}
```

### 7. Get Balita by ID
```http
GET http://localhost:8000/api/balita/1
Authorization: Bearer {your_token_here}
```

### 8. Search Balita by Code
```http
GET http://localhost:8000/api/balita/search?code=BSY-20231115-001
Authorization: Bearer {your_token_here}
```

---

## 🔑 Authentication Flow

1. **Register** (optional) atau gunakan user yang sudah ada
2. **Login** → dapat token
3. Gunakan token di header `Authorization: Bearer {token}` untuk semua protected endpoints
4. **Logout** ketika selesai

---

## 🛠️ Testing Tools

### Using curl (Command Line)
```bash
# Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@test.com","password":"password123"}'

# Get User (dengan token)
curl -X GET http://localhost:8000/api/auth/user \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Using Postman
1. Import collection atau buat manual
2. Set BASE_URL: `http://localhost:8000`
3. Untuk protected routes:
   - Tab **Authorization** → Type: **Bearer Token**
   - Paste token dari login response

---

## ⚠️ Common Issues

### 1. "Unauthenticated"
- Token tidak di-set atau salah
- Token sudah expired (jika ada expiration)
- Token sudah di-logout

### 2. "CSRF token mismatch"
- Untuk API, pastikan request dari domain yang di-allow di `config/sanctum.php`
- Atau gunakan token-based auth (bukan session)

### 3. "The given data was invalid"
- Cek validation error di response
- Pastikan semua field required terisi

---

## ✅ Phase 3 Checklist

- [ ] Server running (`php artisan serve`)
- [ ] Database migrated (`php artisan migrate`)
- [ ] Bisa register user
- [ ] Bisa login dan dapat token
- [ ] Bisa akses GET /api/auth/user dengan token
- [ ] Bisa logout
- [ ] Bisa CRUD balita dengan authentication

---

**Ready for Phase 4!** 🎉
