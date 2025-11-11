# Environment Configuration Guide

## Overview
Proyek ini menggunakan **environment-based toggle** untuk mengontrol fitur admin mockup UI. Fitur ini memungkinkan kita menyembunyikan atau menampilkan komponen admin tanpa mengubah kode.

## File Environment

### `.env` (Default)
File konfigurasi utama yang digunakan saat development.
```bash
VITE_ENABLE_ADMIN=false  # Admin UI hidden (production-like)
VITE_API_URL=http://127.0.0.1:8000
```

### `.env.development`
Konfigurasi untuk development/testing.
```bash
VITE_ENABLE_ADMIN=true  # Admin UI visible untuk testing
VITE_API_URL=http://127.0.0.1:8000
```

### `.env.production`
Konfigurasi untuk production deployment.
```bash
VITE_ENABLE_ADMIN=false  # Admin UI hidden dari user
VITE_API_URL=http://127.0.0.1:8000  # Ganti dengan production URL
```

## Cara Menggunakan

### 1. Mengaktifkan Admin UI (Development)
Edit file `.env`:
```bash
VITE_ENABLE_ADMIN=true
```

Restart dev server:
```bash
npm run dev
```

**Hasil:**
- ✅ Tombol "Login Admin" muncul di Navbar
- ✅ Route `/login-admin` accessible
- ✅ Route `/admin/*` accessible

### 2. Menyembunyikan Admin UI (Production)
Edit file `.env`:
```bash
VITE_ENABLE_ADMIN=false
```

Build untuk production:
```bash
npm run build:laravel
```

**Hasil:**
- ❌ Tombol "Login Admin" tersembunyi
- ❌ Route `/login-admin` tidak accessible (404)
- ❌ Route `/admin/*` tidak accessible (404)

## Komponen yang Terpengaruh

### 1. `App.jsx`
```jsx
// Admin routes hanya muncul jika VITE_ENABLE_ADMIN=true
{ENABLE_ADMIN && (
  <>
    <Route path="/login-admin" element={<LoginAdmin />} />
    <Route path="/admin" element={<AdminLayout />}>
      {/* Semua admin routes */}
    </Route>
  </>
)}
```

### 2. `Navbar.jsx`
```jsx
// Tombol login hanya muncul jika VITE_ENABLE_ADMIN=true
{ENABLE_ADMIN && (
  <Link to="/login-admin" className="btn-login">
    Login Admin
  </Link>
)}
```

## Best Practices

### ✅ DO:
- Gunakan `.env.development` untuk testing admin UI
- Gunakan `.env.production` dengan `VITE_ENABLE_ADMIN=false` untuk deployment
- Commit file `.env.development` dan `.env.production` ke Git
- Restart dev server setelah mengubah file `.env`

### ❌ DON'T:
- Jangan commit file `.env` (sudah di `.gitignore`)
- Jangan set `VITE_ENABLE_ADMIN=true` di production
- Jangan lupa restart server setelah ubah environment variable

## Troubleshooting

### Admin UI tidak muncul padahal sudah set `VITE_ENABLE_ADMIN=true`
**Solusi:** Restart dev server
```bash
npm run dev
```

### Admin UI masih muncul padahal sudah set `VITE_ENABLE_ADMIN=false`
**Solusi:** Build ulang project
```bash
npm run build:laravel
```

### Error "import.meta.env is undefined"
**Solusi:** Pastikan menggunakan Vite (bukan Webpack). File `.env` harus di root project.

## Technical Details

### Vite Environment Variables
- Prefix `VITE_` required untuk expose variable ke browser
- Access via `import.meta.env.VITE_VARIABLE_NAME`
- Type always string, perlu explicit check: `=== 'true'`

### React Router Conditional Rendering
```jsx
// Route hanya di-render jika condition true
{ENABLE_ADMIN && <Route ... />}

// Kalau false, React Router tidak register route tersebut
// User yang akses langsung via URL akan dapat 404
```

## Integration dengan Filament Admin

**Important:** Environment toggle ini **hanya** untuk admin mockup UI di React frontend.

- ✅ Filament admin panel (`/admin`) di Laravel backend **tidak terpengaruh**
- ✅ API endpoints tetap accessible
- ✅ Database CRUD tetap berfungsi via Filament

**Arsitektur:**
- **Backend Admin:** Laravel Filament (selalu aktif)
- **Frontend Admin Mockup:** React components (toggle-able)
- **User Pages:** React (selalu aktif)

## Example Scenarios

### Scenario 1: Development Testing
```bash
# .env
VITE_ENABLE_ADMIN=true

# Akses:
http://127.0.0.1:8000/              ✅ User landing page
http://127.0.0.1:8000/kegiatan      ✅ Kegiatan page
http://127.0.0.1:8000/login-admin   ✅ Admin login mockup
http://127.0.0.1:8000/admin         ✅ Admin dashboard mockup
http://127.0.0.1:8000/admin         ✅ Filament (backend admin)
```

### Scenario 2: Production Deployment
```bash
# .env
VITE_ENABLE_ADMIN=false

# Akses:
http://127.0.0.1:8000/              ✅ User landing page
http://127.0.0.1:8000/kegiatan      ✅ Kegiatan page
http://127.0.0.1:8000/login-admin   ❌ 404 Not Found
http://127.0.0.1:8000/admin         ❌ 404 (React mockup hidden)
http://127.0.0.1:8000/admin         ✅ Filament (backend admin still works!)
```

**Note:** URL `/admin` conflict handled by Laravel routing priority.

---

**Last Updated:** November 11, 2025
