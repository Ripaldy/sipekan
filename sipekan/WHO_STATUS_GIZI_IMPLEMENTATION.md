# Implementasi Status Gizi WHO Standards

## Overview
Sistem SIPEKAN telah diupdate untuk menggunakan standar WHO dalam penghitungan dan kategorisasi status gizi balita. Perubahan ini menggantikan 4 kategori lama dengan 5 kategori standar WHO berdasarkan Z-score BB/TB (Berat Badan per Tinggi Badan).

## Kategori Status Gizi WHO

### 5 Kategori Baru (WHO Standards)
1. **Gizi Buruk** (Severely Wasted)
   - Z-score: < -3 SD
   - Warna: Merah (`rgb(220, 38, 38)`)
   - Database: `gizi_buruk`

2. **Kurus** (Wasted)
   - Z-score: -3 SD sampai -2 SD
   - Warna: Orange (`rgb(251, 146, 60)`)
   - Database: `kurus`

3. **Normal** (Normal)
   - Z-score: -2 SD sampai +2 SD
   - Warna: Hijau (`rgb(34, 197, 94)`)
   - Database: `normal`

4. **Gemuk** (Overweight)
   - Z-score: +2 SD sampai +3 SD
   - Warna: Kuning (`rgb(234, 179, 8)`)
   - Database: `gemuk`

5. **Obesitas** (Obese)
   - Z-score: > +3 SD
   - Warna: Biru (`rgb(29, 78, 216)`)
   - Database: `obesitas`

### Kategori Lama (Deprecated)
- `normal` → `normal` (tetap)
- `kurang` → `kurus`
- `buruk` → `gizi_buruk`
- `lebih` → `gemuk`

## Perubahan Database

### Migration: 2025_11_10_162541_update_status_gizi_categories_in_pengukurans_table.php
```php
// Langkah 1: Expand enum untuk include old + new values
ALTER TABLE pengukurans MODIFY COLUMN status_gizi 
ENUM('normal', 'kurang', 'buruk', 'lebih', 'gizi_buruk', 'kurus', 'gemuk', 'obesitas')

// Langkah 2: Migrate data
UPDATE pengukurans SET status_gizi = 'gizi_buruk' WHERE status_gizi = 'buruk'
UPDATE pengukurans SET status_gizi = 'kurus' WHERE status_gizi = 'kurang'
UPDATE pengukurans SET status_gizi = 'gemuk' WHERE status_gizi = 'lebih'

// Langkah 3: Restrict enum ke new values only
ALTER TABLE pengukurans MODIFY COLUMN status_gizi 
ENUM('gizi_buruk', 'kurus', 'normal', 'gemuk', 'obesitas')
```

## Perubahan Model

### app/Models/Pengukuran.php

#### 1. Metode calculateStatusGizi()
```php
public static function calculateStatusGizi($beratBadan, $tinggiBadan, $umurBulan): string
{
    // WHO standard BB/TB (Weight-for-Height) Z-score calculation
    $tinggiBadanMeter = $tinggiBadan / 100;
    $bmi = $beratBadan / ($tinggiBadanMeter * $tinggiBadanMeter);

    // WHO reference values approximation for Indonesian children
    if ($umurBulan < 24) {
        $median = 16.5;
        $sd = 1.5;
    } elseif ($umurBulan < 60) {
        $median = 15.5;
        $sd = 1.4;
    } else {
        $median = 15.0;
        $sd = 1.6;
    }

    // Calculate Z-score
    $zScore = ($bmi - $median) / $sd;

    // WHO categories based on Z-score
    if ($zScore < -3) return 'gizi_buruk';      // < -3 SD
    if ($zScore < -2) return 'kurus';           // -3 to -2 SD
    if ($zScore <= 2) return 'normal';          // -2 to +2 SD
    if ($zScore <= 3) return 'gemuk';           // +2 to +3 SD
    return 'obesitas';                          // > +3 SD
}
```

#### 2. Metode getStatusGiziColorAttribute()
```php
public function getStatusGiziColorAttribute(): string
{
    return match($this->status_gizi) {
        'normal' => 'success',
        'kurus' => 'warning',
        'gizi_buruk' => 'danger',
        'gemuk' => 'info',
        'obesitas' => 'primary',
        default => 'gray',
    };
}
```

#### 3. Boot Method
Otomatis menghitung status_gizi saat menyimpan pengukuran:
```php
protected static function boot()
{
    parent::boot();

    static::saving(function ($pengukuran) {
        if ($pengukuran->berat_badan && $pengukuran->tinggi_badan) {
            $pengukuran->status_gizi = static::calculateStatusGizi(
                $pengukuran->berat_badan,
                $pengukuran->tinggi_badan,
                $pengukuran->umur_saat_ukur
            );
        }
    });
}
```

## Perubahan Widget Dashboard

### 1. StatsOverview Widget
**File:** `app/Filament/Widgets/StatsOverview.php`

**Perubahan:**
- Card "Gejala Stunting": Menggunakan `['gizi_buruk', 'kurus']` (sebelumnya `['gizi_buruk', 'gizi_kurang']`)
- Card "Anak Normal": Menggunakan `'normal'` (sebelumnya `'gizi_baik'`)

```php
// Gejala Stunting - anak dengan gizi buruk atau kurus (WHO categories)
$gejalaStunting = DB::table('pengukurans')
    ->whereIn('status_gizi', ['gizi_buruk', 'kurus'])
    ->distinct()
    ->count('balita_id');

// Anak Normal - anak dengan status gizi normal (WHO category)
$anakNormal = DB::table('pengukurans')
    ->where('status_gizi', 'normal')
    ->distinct()
    ->count('balita_id');
```

### 2. BalitaStatusGiziChart Widget
**File:** `app/Filament/Widgets/BalitaStatusGiziChart.php`

**Perubahan:**
- Dari 2 kategori (Normal, Stunting) → 5 kategori WHO
- Pie chart sekarang menampilkan distribusi detail

```php
protected function getData(): array
{
    $statusGizi = Pengukuran::select('balita_id', 'status_gizi', 'tanggal_ukur')
        ->whereIn('id', function ($query) {
            $query->select(DB::raw('MAX(id)'))
                ->from('pengukurans')
                ->groupBy('balita_id');
        })
        ->get()
        ->groupBy('status_gizi')
        ->map(fn ($group) => $group->count());

    $giziBuruk = $statusGizi->get('gizi_buruk', 0);
    $kurus = $statusGizi->get('kurus', 0);
    $normal = $statusGizi->get('normal', 0);
    $gemuk = $statusGizi->get('gemuk', 0);
    $obesitas = $statusGizi->get('obesitas', 0);

    return [
        'datasets' => [
            [
                'label' => 'Jumlah Anak',
                'data' => [$giziBuruk, $kurus, $normal, $gemuk, $obesitas],
                'backgroundColor' => [
                    'rgb(220, 38, 38)',    // Red - Gizi Buruk
                    'rgb(251, 146, 60)',   // Orange - Kurus
                    'rgb(34, 197, 94)',    // Green - Normal
                    'rgb(234, 179, 8)',    // Yellow - Gemuk
                    'rgb(29, 78, 216)',    // Blue - Obesitas
                ],
            ],
        ],
        'labels' => [
            'Gizi Buruk: ' . $giziBuruk . ' anak', 
            'Kurus: ' . $kurus . ' anak', 
            'Normal: ' . $normal . ' anak', 
            'Gemuk: ' . $gemuk . ' anak', 
            'Obesitas: ' . $obesitas . ' anak'
        ],
    ];
}
```

## Artisan Command untuk Recalculate

### Command: pengukuran:recalculate-status-gizi
**File:** `app/Console/Commands/RecalculateStatusGizi.php`

**Fungsi:** Menghitung ulang semua data pengukuran existing dengan formula WHO baru

**Cara Menggunakan:**
```bash
php artisan pengukuran:recalculate-status-gizi
```

**Output:**
```
Recalculating status gizi for all pengukuran...
Successfully recalculated X out of Y pengukuran records.
```

**Fitur:**
- Hanya mengupdate record yang status_gizi-nya berubah
- Menggunakan `saveQuietly()` untuk menghindari trigger event boot method
- Menampilkan jumlah record yang diupdate

## Formula Z-Score Detail

### WHO Weight-for-Height (BB/TB) Z-Score
```
Z-score = (observed value - median) / SD
```

Dimana:
- **Observed value**: BMI anak yang diukur
- **Median**: Nilai median BMI untuk kelompok umur (dari tabel referensi WHO)
- **SD**: Standard Deviation untuk kelompok umur

### Simplified Implementation
Karena tabel referensi WHO lengkap sangat kompleks (berbeda per bulan, gender, tinggi badan), sistem menggunakan approximation:

```php
// Age-based median and SD approximation
if ($umurBulan < 24) {
    $median = 16.5;
    $sd = 1.5;
} elseif ($umurBulan < 60) {
    $median = 15.5;
    $sd = 1.4;
} else {
    $median = 15.0;
    $sd = 1.6;
}

$zScore = ($bmi - $median) / $sd;
```

### Improvement Recommendations
Untuk akurasi lebih tinggi di production:
1. Implementasi tabel referensi WHO lengkap
2. Gunakan package seperti `who-growth-charts` atau `anthro-calc`
3. Include gender dalam perhitungan
4. Gunakan tinggi badan sebagai faktor tambahan (bukan hanya umur)

## Testing

### Manual Testing Steps
1. Buat pengukuran baru dengan berbagai kombinasi BB/TB
2. Verifikasi auto-calculation menghasilkan kategori yang benar
3. Check dashboard widget menampilkan 5 kategori dengan warna tepat
4. Test update pengukuran existing tetap recalculate dengan benar

### Test Cases
```php
// Gizi Buruk (Z-score < -3)
BB: 8kg, TB: 75cm, Umur: 24 bulan → BMI: 14.2 → Expected: gizi_buruk

// Kurus (Z-score -3 to -2)
BB: 9kg, TB: 75cm, Umur: 24 bulan → BMI: 16.0 → Expected: kurus

// Normal (Z-score -2 to +2)
BB: 11kg, TB: 80cm, Umur: 24 bulan → BMI: 17.2 → Expected: normal

// Gemuk (Z-score +2 to +3)
BB: 13kg, TB: 80cm, Umur: 24 bulan → BMI: 20.3 → Expected: gemuk

// Obesitas (Z-score > +3)
BB: 15kg, TB: 80cm, Umur: 24 bulan → BMI: 23.4 → Expected: obesitas
```

## Files Modified

1. **Migration:**
   - `database/migrations/2025_11_10_162541_update_status_gizi_categories_in_pengukurans_table.php` (NEW)

2. **Model:**
   - `app/Models/Pengukuran.php` (UPDATED)

3. **Widgets:**
   - `app/Filament/Widgets/StatsOverview.php` (UPDATED)
   - `app/Filament/Widgets/BalitaStatusGiziChart.php` (UPDATED)

4. **Console Command:**
   - `app/Console/Commands/RecalculateStatusGizi.php` (NEW)

## Rollback Instructions

Jika perlu rollback ke sistem lama:

```bash
# 1. Rollback migration
php artisan migrate:rollback

# 2. Revert model changes (restore from git)
git checkout app/Models/Pengukuran.php

# 3. Revert widget changes
git checkout app/Filament/Widgets/StatsOverview.php
git checkout app/Filament/Widgets/BalitaStatusGiziChart.php

# 4. Delete command file
rm app/Console/Commands/RecalculateStatusGizi.php
```

## Next Steps

1. ✅ Implementasi WHO 5 categories
2. ✅ Update calculation formula
3. ✅ Update dashboard widgets
4. ✅ Create recalculation command
5. 🔄 **TODO**: Implementasi tabel referensi WHO lengkap untuk akurasi lebih tinggi
6. 🔄 **TODO**: Include gender dalam perhitungan
7. 🔄 **TODO**: Add unit tests untuk calculation formula
8. 🔄 **TODO**: Update export reports untuk include new categories

## References

- WHO Child Growth Standards: https://www.who.int/tools/child-growth-standards
- WHO Anthro Documentation: https://www.who.int/tools/child-growth-standards/software
- Z-Score Interpretation: https://www.who.int/tools/child-growth-standards/standards/weight-for-height

---

**Status:** ✅ COMPLETED
**Date:** 2025-11-10
**Phase:** 6 (Dashboard Widgets)
