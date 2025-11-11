import publicApi from '../config/api';

export const publicService = {
  /**
   * Search balita by kode_anak (public access)
   */
  async searchBalitaByCode(kode_anak) {
    try {
      const response = await publicApi.get('/public/balita/search', {
        params: { kode_anak }
      });
      return {
        success: true,
        data: response.data.data,
        message: response.data.message
      };
    } catch (error) {
      return {
        success: false,
        message: error.response?.data?.message || 'Terjadi kesalahan saat mencari data anak'
      };
    }
  },

  /**
   * Get statistics for home page
   */
  async getStatistics() {
    try {
      const response = await publicApi.get('/public/statistics');
      return response.data;
    } catch (error) {
      console.error('Error fetching statistics:', error);
      // Fallback data jika API gagal
      return {
        success: true,
        data: {
          total_balita: 500,
          balita_sehat: 490,
          persentase_sehat: 98,
          siap_melayani: true
        }
      };
    }
  },

  /**
   * Get all kegiatan (public access)
   */
  async getKegiatan(params = {}) {
    try {
      const response = await publicApi.get('/public/kegiatan', { params });
      return {
        success: true,
        data: response.data.data,
        message: response.data.message
      };
    } catch (error) {
      console.error('Error fetching kegiatan:', error);
      return {
        success: false,
        message: error.response?.data?.message || 'Terjadi kesalahan saat mengambil data kegiatan',
        data: []
      };
    }
  },

  /**
   * Get kegiatan detail by ID
   */
  async getKegiatanById(id) {
    try {
      const response = await publicApi.get(`/public/kegiatan/${id}`);
      return {
        success: true,
        data: response.data.data,
        message: response.data.message
      };
    } catch (error) {
      return {
        success: false,
        message: error.response?.data?.message || 'Kegiatan tidak ditemukan'
      };
    }
  }
};
