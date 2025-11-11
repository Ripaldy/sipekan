# 🔗 Quick Reference - Models & Relationships

## 📦 Available Models

```php
App\Models\User
App\Models\Balita
App\Models\Kegiatan
App\Models\Pengukuran
App\Models\Imunisasi
App\Models\VitaminObat
App\Models\KaderPosyandu
```

---

## 🔄 Relationship Usage Examples

### 1. User Relationships

```php
use App\Models\User;

$user = User::find(1);

// Get kader profile (hasOne)
$kaderInfo = $user->kaderPosyandu;

// Get all pengukuran yang dilakukan kader ini (hasMany)
$pengukurans = $user->pengukurans;
$pengukurans = $user->pengukurans()->latest()->get();

// Get all imunisasi yang dilakukan kader ini (hasMany)
$imunisasis = $user->imunisasis;

// Get all vitamin/obat yang diberikan kader ini (hasMany)
$vitaminObats = $user->vitaminObats;

// Check role
if ($user->isAdmin()) {
    // Admin logic
}

if ($user->isKader()) {
    // Kader logic
}
```

---

### 2. Balita Relationships

```php
use App\Models\Balita;

$balita = Balita::find(1);

// Get all pengukuran balita ini (hasMany)
$pengukurans = $balita->pengukurans;
$pengukuranTerbaru = $balita->pengukurans()->latest('tanggal_ukur')->first();

// Get all imunisasi balita ini (hasMany)
$imunisasis = $balita->imunisasis;
$imunisasiSelesai = $balita->imunisasis()->count();

// Get all vitamin/obat balita ini (hasMany)
$vitaminObats = $balita->vitaminObats;

// Accessors
$umurBulan = $balita->umur_sekarang; // Integer (bulan)
$umurDisplay = $balita->umur_display; // String "2 tahun 3 bulan"
$fotoUrl = $balita->foto_url; // Full URL atau null

// Helper methods
$pengukuranTerakhir = $balita->pengukuranTerakhir();
$statusGizi = $balita->getStatusGiziTerakhir(); // "Normal", "Kurang", dll
```

---

### 3. Create Balita (Auto-Generate ID)

```php
use App\Models\Balita;

// ID akan auto-generate saat create
$balita = Balita::create([
    'nama' => 'Andi Pratama',
    'jenis_kelamin' => 'L',
    'tanggal_lahir' => '2023-05-15',
    'nama_orang_tua' => 'Budi Santoso',
    'nik_orang_tua' => '3201234567890123',
    'alamat' => 'Jl. Merdeka No. 123',
    'rt_rw' => '001/002',
    'no_telepon_ortu' => '081234567890',
]);

echo $balita->id_balita; // BSY-20251110-001

// Atau generate manual
$nextId = Balita::generateIdBalita();
echo $nextId; // BSY-20251110-002
```

---

### 4. Kegiatan Relationships

```php
use App\Models\Kegiatan;

$kegiatan = Kegiatan::find(1);

// Get all pengukuran di kegiatan ini (hasMany)
$pengukurans = $kegiatan->pengukurans;

// Accessor
$jumlahPeserta = $kegiatan->jumlah_peserta; // Count distinct balita_id

// Scopes
$upcoming = Kegiatan::upcoming()->get();
$bulanIni = Kegiatan::bulanIni()->get();
```

---

### 5. Pengukuran (Auto-Calculate)

```php
use App\Models\Pengukuran;

// Auto-calculate umur_saat_ukur dan status_gizi
$pengukuran = Pengukuran::create([
    'balita_id' => 1,
    'kegiatan_id' => 1,
    'tanggal_ukur' => now(),
    'berat_badan' => 12.5,
    'tinggi_badan' => 85.2,
    'lingkar_kepala' => 48.5,
    'kader_id' => 1,
]);

// umur_saat_ukur dan status_gizi akan auto-filled
echo $pengukuran->umur_saat_ukur; // 18 (bulan)
echo $pengukuran->status_gizi; // normal/kurang/buruk/lebih

// Relationships (belongsTo)
$balita = $pengukuran->balita;
$kegiatan = $pengukuran->kegiatan;
$kader = $pengukuran->kader; // User model

// Accessor
$color = $pengukuran->status_gizi_color; // success/warning/danger/info

// Manual calculate status gizi
$status = Pengukuran::calculateStatusGizi(12.5, 85.2, 18);
```

---

### 6. Imunisasi (Auto-Calculate)

```php
use App\Models\Imunisasi;

// Auto-calculate umur_saat_imunisasi
$imunisasi = Imunisasi::create([
    'balita_id' => 1,
    'jenis_vaksin' => 'BCG',
    'tanggal_pemberian' => now(),
    'batch_number' => 'BCG-2025-001',
    'tempat_pemberian' => 'Puskesmas ABC',
    'kader_id' => 1,
]);

// umur_saat_imunisasi akan auto-filled
echo $imunisasi->umur_saat_imunisasi; // 2 (bulan)

// Relationships (belongsTo)
$balita = $imunisasi->balita;
$kader = $imunisasi->kader; // User model

// Get jadwal imunisasi standar
$jadwal = Imunisasi::getJadwalImunisasi();
/*
[
    'HB-0' => 0,
    'BCG' => 1,
    'Polio 1' => 1,
    ...
]
*/

// Check imunisasi belum lengkap untuk balita
$balita = Balita::find(1);
$sudahDilakukan = $balita->imunisasis->pluck('jenis_vaksin')->toArray();
$jadwalLengkap = Imunisasi::getJadwalImunisasi();
$belumDilakukan = array_diff(array_keys($jadwalLengkap), $sudahDilakukan);
```

---

### 7. VitaminObat

```php
use App\Models\VitaminObat;

$vitaminObat = VitaminObat::create([
    'balita_id' => 1,
    'jenis' => 'vitamin_a',
    'tanggal_pemberian' => now(),
    'dosis' => '100.000 IU',
    'kader_id' => 1,
]);

// Relationships (belongsTo)
$balita = $vitaminObat->balita;
$kader = $vitaminObat->kader; // User model
```

---

### 8. KaderPosyandu

```php
use App\Models\KaderPosyandu;

$kader = KaderPosyandu::create([
    'user_id' => 1,
    'nama_lengkap' => 'Siti Nurhaliza',
    'no_telepon' => '081234567890',
    'alamat' => 'Jl. Posyandu No. 5',
    'tanggal_bergabung' => now(),
    'status' => 'aktif',
]);

// Relationship (belongsTo)
$user = $kader->user;

// Atau dari User
$user = User::find(1);
$kaderInfo = $user->kaderPosyandu;
```

---

## 🎯 Common Queries

### Get Balita dengan Pengukuran Terakhir

```php
$balitas = Balita::with(['pengukurans' => function($query) {
    $query->latest('tanggal_ukur')->limit(1);
}])->get();
```

### Get Kegiatan dengan Jumlah Peserta

```php
$kegiatans = Kegiatan::withCount([
    'pengukurans as jumlah_peserta' => function($query) {
        $query->select(DB::raw('count(distinct balita_id)'));
    }
])->get();
```

### Get Balita dengan Status Gizi

```php
$balitaKurangGizi = Balita::whereHas('pengukurans', function($query) {
    $query->latest('tanggal_ukur')
          ->where('status_gizi', 'kurang');
})->get();
```

### Get Imunisasi yang Dilakukan Kader

```php
$kader = User::find(1);
$imunisasisBulanIni = $kader->imunisasis()
    ->whereMonth('tanggal_pemberian', now()->month)
    ->with('balita')
    ->get();
```

### Statistik Balita per Status Gizi

```php
$pengukuranTerakhir = Pengukuran::select('balita_id', 'status_gizi')
    ->whereIn('id', function($query) {
        $query->select(DB::raw('MAX(id)'))
              ->from('pengukurans')
              ->groupBy('balita_id');
    })
    ->get();

$statistik = $pengukuranTerakhir->groupBy('status_gizi')
    ->map(fn($group) => $group->count());
```

---

## ⚡ Performance Tips

### Eager Loading

```php
// BAD: N+1 Query Problem
$balitas = Balita::all();
foreach($balitas as $balita) {
    echo $balita->pengukuranTerakhir()->nama_kader;
}

// GOOD: Eager Loading
$balitas = Balita::with([
    'pengukurans' => fn($q) => $q->latest('tanggal_ukur')->limit(1),
    'pengukurans.kader'
])->get();
```

### Lazy Eager Loading

```php
$balitas = Balita::all();

// Load relationships later
$balitas->load('pengukurans', 'imunisasis');
```

### Counting

```php
// BAD
$count = $balita->pengukurans->count();

// GOOD
$count = $balita->pengukurans()->count();
```

---

## 🧪 Testing in Tinker

```bash
php artisan tinker
```

```php
// Test Balita creation with auto ID
$balita = App\Models\Balita::create([
    'nama' => 'Test Baby',
    'jenis_kelamin' => 'L',
    'tanggal_lahir' => '2023-01-15',
    'nama_orang_tua' => 'Test Parent',
    'nik_orang_tua' => '1234567890123456',
    'alamat' => 'Test Address',
    'rt_rw' => '001/001',
    'no_telepon_ortu' => '08123456789'
]);
echo $balita->id_balita; // BSY-20251110-001

// Test umur calculation
echo $balita->umur_sekarang; // Umur dalam bulan
echo $balita->umur_display; // "X tahun Y bulan"

// Test relationships
$user = App\Models\User::first();
$user->kaderPosyandu;
$user->pengukurans;
```

---

**Happy Coding!** 🚀
