export const beritaData = [
  {
    id: 1,
    judul: 'Memahami Stunting: Penyebab dan Pencegahan',
    kategori: 'Stunting',
    excerpt: 'Stunting adalah kondisi gagal tumbuh pada anak akibat kekurangan gizi kronis...',
    gambar: 'https://images.unsplash.com/photo-1516627145497-ae6968895b74?w=800',
    tanggal: '18 Oktober 2025',
    penulis: 'Kemenkes RI',
    readTime: '5 menit',
  },
  {
    id: 2,
    judul: 'Tahapan Tumbuh Kembang Anak Usia 0-5 Tahun',
    kategori: 'Tumbuh Kembang',
    excerpt: 'Memahami milestone perkembangan anak sangat penting. Simak tahapan tumbuh kembang...',
    gambar: 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=800',
    tanggal: '12 Oktober 2025',
    penulis: 'IDAI',
    readTime: '7 menit',
  },
  {
    id: 3,
    judul: 'Panduan Gizi Seimbang untuk Anak Balita',
    kategori: 'Gizi & Nutrisi',
    excerpt: 'Menu makanan bergizi adalah kunci mencegah stunting. Pelajari komposisi gizi seimbang...',
    gambar: 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=800',
    tanggal: '10 Oktober 2025',
    penulis: 'Nutritionist',
    readTime: '6 menit',
  },
  {
    id: 4,
    judul: '5 Tips Meningkatkan Imunitas Anak',
    kategori: 'Tips Kesehatan',
    excerpt: 'Sistem imun yang kuat membantu anak tumbuh sehat. Ikuti 5 tips mudah untuk meningkatkan...',
    gambar: 'https://images.unsplash.com/photo-1584515933487-779824d29309?w=800',
    tanggal: '8 Oktober 2025',
    penulis: 'Dr. Sri Aminah',
    readTime: '4 menit',
  },
  {
    id: 5,
    judul: '1000 Hari Pertama Kehidupan: Periode Emas Cegah Stunting',
    kategori: 'Stunting',
    excerpt: 'Periode 1000 hari pertama kehidupan sangat krusial. Intervensi gizi yang tepat dapat...',
    gambar: 'https://images.unsplash.com/photo-1476703993599-0035a21b17a9?w=800',
    tanggal: '5 Oktober 2025',
    penulis: 'WHO Indonesia',
    readTime: '8 menit',
  },
  {
    id: 6,
    judul: 'Stimulasi Dini untuk Perkembangan Kognitif Anak',
    kategori: 'Tumbuh Kembang',
    excerpt: 'Stimulasi sejak dini sangat penting untuk perkembangan otak anak. Ketahui aktivitas...',
    gambar: 'https://images.unsplash.com/photo-1587616211892-7a8c7ed6de10?w=800',
    tanggal: '2 Oktober 2025',
    penulis: 'Psikolog Anak',
    readTime: '6 menit',
  }
];

export const getKategoriList = () => {
  return ['Semua', ...new Set(beritaData.map(b => b.kategori))];
};

export const filterBeritaByKategori = (kategori) => {
  if (!kategori || kategori === 'Semua') return beritaData;
  return beritaData.filter(berita => berita.kategori === kategori);
};
