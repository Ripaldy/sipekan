export const kegiatanData = [
  {
    id: 1,
    judul: 'Imunisasi Anak Batch 1',
    kategori: 'Imunisasi',
    deskripsi: 'Memberikan imunisasi anti stunting dengan cakupan vaksin lengkap untuk mencegah penyakit menular dan memastikan pertumbuhan optimal anak-anak di wilayah ini.',
    tanggal: 'Jumat, 25 Juli 2025 pukul 15.00 WIB',
    lokasi: 'Posyandu Anggrek',
    peserta: '50 anak',
    status: 'upcoming',
    icon: 'í²‰',
    color: '#22c55e'
  },
  {
    id: 2,
    judul: 'Imunisasi Anak Batch 2 (Agustus)',
    kategori: 'Imunisasi',
    deskripsi: 'Imunisasi Batch 2 di Balai Desa dengan fokus pada vaksin polio, campak, dan DPT untuk memastikan semua anak terlindungi dari penyakit berbahaya.',
    tanggal: 'Kamis, 14 Agustus 2025 pukul 08.00 WIB',
    lokasi: 'Balai Desa',
    peserta: '60 anak',
    status: 'upcoming',
    icon: 'í²‰',
    color: '#22c55e'
  },
  {
    id: 3,
    judul: 'Edukasi Kesehatan Ibu dan Anak',
    kategori: 'Edukasi',
    deskripsi: 'Edukasi lengkap tentang pentingnya gizi seimbang untuk ibu hamil dan menyusui, termasuk panduan pemilihan makanan bergizi, cara menyiapkan menu sehat keluarga, dan tips kesehatan.',
    tanggal: 'Senin, 1 September 2025 pukul 10.00 WIB',
    lokasi: 'Posyandu Melati',
    peserta: '80 peserta (Ibu hamil, ibu menyusui, dan keluarga)',
    status: 'upcoming',
    icon: 'í³š',
    color: '#3b82f6'
  },
  {
    id: 4,
    judul: 'Pemeriksaan Kesehatan Rutin',
    kategori: 'Pemeriksaan',
    deskripsi: 'Pemeriksaan kesehatan gratis untuk anak balita di Puskesmas dengan fokus pada deteksi dini stunting dan masalah kesehatan lainnya.',
    tanggal: 'Minggu, 6 Oktober 2025 pukul 09.00 WIB',
    lokasi: 'Puskesmas Kecamatan',
    peserta: '100 anak',
    status: 'upcoming',
    icon: 'í¹º',
    color: '#8b5cf6'
  }
];

export const filterKegiatan = (searchQuery) => {
  if (!searchQuery) return kegiatanData;
  
  const query = searchQuery.toLowerCase();
  return kegiatanData.filter(kegiatan => 
    kegiatan.judul.toLowerCase().includes(query) ||
    kegiatan.lokasi.toLowerCase().includes(query) ||
    kegiatan.kategori.toLowerCase().includes(query)
  );
};

