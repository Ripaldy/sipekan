<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KegiatanController extends Controller
{
    /**
     * Display a listing of kegiatan (public access)
     */
    public function index(Request $request)
    {
        try {
            $query = Kegiatan::query();

            // Filter by date
            if ($request->has('tanggal')) {
                $query->whereDate('tanggal', $request->tanggal);
            }

            // Filter by kategori
            if ($request->has('kategori')) {
                $query->where('kategori_kegiatan', $request->kategori);
            }

            // Search by multiple fields
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_kegiatan', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%")
                      ->orWhere('tempat', 'like', "%{$search}%")
                      ->orWhere('penanggung_jawab', 'like', "%{$search}%");
                });
            }

            // Only show future or ongoing events for public
            if ($request->has('only_future')) {
                $query->where('tanggal', '>=', now());
            }

            $kegiatans = $query->orderBy('tanggal', 'asc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Data kegiatan berhasil diambil',
                'data' => $kegiatans
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kegiatan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified kegiatan
     */
    public function show($id)
    {
        try {
            $kegiatan = Kegiatan::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Detail kegiatan berhasil diambil',
                'data' => $kegiatan
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kegiatan tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Store a newly created kegiatan (Admin only)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kegiatan' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'nullable',
            'waktu_selesai' => 'nullable',
            'lokasi' => 'required|string|max:200',
            'posyandu' => 'nullable|string|max:100',
            'kategori_kegiatan' => 'required|in:imunisasi,penimbangan,penyuluhan,posyandu',
            'pemateri' => 'nullable|string|max:100',
            'target_peserta' => 'nullable|string|max:100',
            'status' => 'nullable|in:terjadwal,sedang berlangsung,selesai,dibatalkan',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $kegiatan = Kegiatan::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Kegiatan berhasil dibuat',
                'data' => $kegiatan
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat kegiatan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified kegiatan (Admin only)
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_kegiatan' => 'string|max:200',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'date',
            'waktu_mulai' => 'nullable',
            'waktu_selesai' => 'nullable',
            'lokasi' => 'string|max:200',
            'posyandu' => 'nullable|string|max:100',
            'kategori_kegiatan' => 'in:imunisasi,penimbangan,penyuluhan,posyandu',
            'pemateri' => 'nullable|string|max:100',
            'target_peserta' => 'nullable|string|max:100',
            'status' => 'nullable|in:terjadwal,sedang berlangsung,selesai,dibatalkan',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $kegiatan = Kegiatan::findOrFail($id);
            $kegiatan->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Kegiatan berhasil diupdate',
                'data' => $kegiatan
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate kegiatan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified kegiatan (Admin only)
     */
    public function destroy($id)
    {
        try {
            $kegiatan = Kegiatan::findOrFail($id);
            $kegiatan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kegiatan berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kegiatan: ' . $e->getMessage()
            ], 500);
        }
    }
}
