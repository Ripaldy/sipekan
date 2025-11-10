<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Balita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class BalitaController extends Controller
{
    /**
     * Get all balita dengan pagination
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $search = $request->input('search');

        $query = Balita::with(['pengukurans' => function ($q) {
            $q->latest('tanggal_ukur')->limit(1);
        }]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('id_balita', 'like', "%{$search}%")
                    ->orWhere('nama_orang_tua', 'like', "%{$search}%");
            });
        }

        $balitas = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $balitas->map(function ($balita) {
                return $this->formatBalitaData($balita);
            }),
            'meta' => [
                'current_page' => $balitas->currentPage(),
                'last_page' => $balitas->lastPage(),
                'per_page' => $balitas->perPage(),
                'total' => $balitas->total(),
            ],
        ]);
    }

    /**
     * Get detail balita + riwayat lengkap
     */
    public function show($id)
    {
        $balita = Balita::with([
            'pengukurans' => fn($q) => $q->orderBy('tanggal_ukur', 'desc'),
            'imunisasis' => fn($q) => $q->orderBy('tanggal_pemberian', 'desc'),
            'vitaminObats' => fn($q) => $q->orderBy('tanggal_pemberian', 'desc')
        ])->find($id);

        if (!$balita) {
            return response()->json([
                'success' => false,
                'message' => 'Balita tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => array_merge($this->formatBalitaData($balita), [
                'riwayat_pengukuran' => $balita->pengukurans,
                'riwayat_imunisasi' => $balita->imunisasis,
                'riwayat_vitamin_obat' => $balita->vitaminObats,
            ]),
        ]);
    }

    /**
     * Create balita baru (auto-generate id_balita)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'nama_orang_tua' => 'required|string|max:100',
            'alamat' => 'required|string',
            'desa_kelurahan' => 'required|string|max:100',
            'posyandu' => 'required|string|max:100',
            'no_telepon_ortu' => 'required|string|max:20',
            'foto_balita' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Auto-generate id_balita: BSY-YYYYMMDD-XXX
        $validated['id_balita'] = $this->generateIdBalita();

        // Handle upload foto
        if ($request->hasFile('foto_balita')) {
            $validated['foto_balita'] = $this->uploadFoto($request->file('foto_balita'));
        }

        $balita = Balita::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Balita berhasil ditambahkan',
            'data' => $this->formatBalitaData($balita),
        ], 201);
    }

    /**
     * Update balita
     */
    public function update(Request $request, $id)
    {
        $balita = Balita::find($id);

        if (!$balita) {
            return response()->json([
                'success' => false,
                'message' => 'Balita tidak ditemukan',
            ], 404);
        }

        $validated = $request->validate([
            'nama' => 'sometimes|string|max:100',
            'jenis_kelamin' => 'sometimes|in:L,P',
            'tanggal_lahir' => 'sometimes|date',
            'nama_orang_tua' => 'sometimes|string|max:100',
            'alamat' => 'sometimes|string',
            'desa_kelurahan' => 'sometimes|string|max:100',
            'posyandu' => 'sometimes|string|max:100',
            'no_telepon_ortu' => 'sometimes|string|max:20',
            'foto_balita' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle upload foto baru
        if ($request->hasFile('foto_balita')) {
            // Hapus foto lama
            if ($balita->foto_balita) {
                Storage::disk('public')->delete($balita->foto_balita);
            }
            $validated['foto_balita'] = $this->uploadFoto($request->file('foto_balita'));
        }

        $balita->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Balita berhasil diupdate',
            'data' => $this->formatBalitaData($balita),
        ]);
    }

    /**
     * Soft delete balita
     */
    public function destroy($id)
    {
        $balita = Balita::find($id);

        if (!$balita) {
            return response()->json([
                'success' => false,
                'message' => 'Balita tidak ditemukan',
            ], 404);
        }

        $balita->delete();

        return response()->json([
            'success' => true,
            'message' => 'Balita berhasil dihapus',
        ]);
    }

    /**
     * Search balita by code
     */
    public function searchByCode(Request $request)
    {
        $code = $request->input('code');

        if (!$code) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter code wajib diisi',
            ], 400);
        }

        $balita = Balita::where('id_balita', $code)->first();

        if (!$balita) {
            return response()->json([
                'success' => false,
                'message' => 'Balita tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatBalitaData($balita),
        ]);
    }

    /**
     * Generate ID Balita (Format: BSY-YYYYMMDD-XXX)
     */
    private function generateIdBalita(): string
    {
        $date = now()->format('Ymd');
        $prefix = "BSY-{$date}-";

        // Cari nomor urut terakhir hari ini
        $lastBalita = Balita::where('id_balita', 'like', $prefix . '%')
            ->orderBy('id_balita', 'desc')
            ->first();

        if ($lastBalita) {
            $lastNumber = (int) substr($lastBalita->id_balita, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $prefix . $newNumber;
    }

    /**
     * Upload dan resize foto balita
     */
    private function uploadFoto($file): string
    {
        $filename = 'balita_' . uniqid() . '.' . $file->extension();
        $path = 'balita/' . $filename;

        // Resize foto menggunakan Intervention Image
        $image = Image::read($file);
        $image->scale(width: 500);
        
        Storage::disk('public')->put($path, (string) $image->encode());

        return $path;
    }

    /**
     * Format data balita untuk response
     */
    private function formatBalitaData($balita): array
    {
        $pengukuranTerakhir = $balita->pengukurans->first();

        return [
            'id' => $balita->id,
            'id_balita' => $balita->id_balita,
            'nama' => $balita->nama,
            'jenis_kelamin' => $balita->jenis_kelamin,
            'tanggal_lahir' => $balita->tanggal_lahir->format('Y-m-d'),
            'umur_bulan' => $balita->umur_sekarang,
            'umur_display' => $balita->umur_display,
            'nama_orang_tua' => $balita->nama_orang_tua,
            'alamat' => $balita->alamat,
            'desa_kelurahan' => $balita->desa_kelurahan,
            'posyandu' => $balita->posyandu,
            'no_telepon_ortu' => $balita->no_telepon_ortu,
            'foto_url' => $balita->foto_url,
            'status_gizi_terakhir' => $balita->getStatusGiziTerakhir(),
            'pengukuran_terakhir' => $pengukuranTerakhir ? [
                'tanggal' => $pengukuranTerakhir->tanggal_ukur->format('Y-m-d'),
                'berat_badan' => $pengukuranTerakhir->berat_badan,
                'tinggi_badan' => $pengukuranTerakhir->tinggi_badan,
                'status_gizi' => $pengukuranTerakhir->status_gizi,
            ] : null,
        ];
    }
}