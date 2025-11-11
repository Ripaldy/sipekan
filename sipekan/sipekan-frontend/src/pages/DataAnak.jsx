import React, { useState } from 'react';
import { Input, Button, Card, message, Spin, Empty, Descriptions, Table } from 'antd';
import { SearchOutlined } from '@ant-design/icons';
import { publicService } from '../services/publicService';
import { Line } from 'react-chartjs-2';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
} from 'chart.js';

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
);

const DataAnak = () => {
  const [kodeAnak, setKodeAnak] = useState('');
  const [loading, setLoading] = useState(false);
  const [dataAnak, setDataAnak] = useState(null);

  const handleSearch = async () => {
    if (!kodeAnak.trim()) {
      message.warning('Masukkan kode anak terlebih dahulu');
      return;
    }

    setLoading(true);
    try {
      const result = await publicService.searchBalitaByCode(kodeAnak);
      
      if (result.success) {
        setDataAnak(result.data);
        message.success('Data anak berhasil ditemukan!');
      } else {
        setDataAnak(null);
        message.error(result.message);
      }
    } catch (error) {
      message.error('Terjadi kesalahan. Silakan coba lagi.');
    } finally {
      setLoading(false);
    }
  };

  const getChartData = () => {
    if (!dataAnak || !dataAnak.riwayat_pengukuran) return null;
    const data = dataAnak.riwayat_pengukuran.slice().reverse();

    return {
      labels: data.map(d => `${d.usia_bulan} bln`),
      datasets: [
        {
          label: 'Berat Badan (kg)',
          data: data.map(d => d.berat_badan),
          borderColor: 'rgb(34, 197, 94)',
          backgroundColor: 'rgba(34, 197, 94, 0.1)',
          tension: 0.3,
        },
        {
          label: 'Tinggi Badan (cm)',
          data: data.map(d => d.tinggi_badan),
          borderColor: 'rgb(59, 130, 246)',
          backgroundColor: 'rgba(59, 130, 246, 0.1)',
          tension: 0.3,
        }
      ]
    };
  };

  const columns = [
    { title: 'Tanggal', dataIndex: 'tanggal', key: 'tanggal' },
    { title: 'Usia (bulan)', dataIndex: 'usia_bulan', key: 'usia_bulan' },
    { title: 'BB (kg)', dataIndex: 'berat_badan', key: 'berat_badan' },
    { title: 'TB (cm)', dataIndex: 'tinggi_badan', key: 'tinggi_badan' },
    { title: 'LK (cm)', dataIndex: 'lingkar_kepala', key: 'lingkar_kepala' },
    {
      title: 'Status Gizi',
      dataIndex: 'status_gizi',
      key: 'status_gizi',
      render: (status) => (
        <span style={{
          padding: '4px 12px',
          borderRadius: '12px',
          background: status === 'Normal' ? '#d1fae5' : '#fed7aa',
          color: status === 'Normal' ? '#065f46' : '#9a3412',
          fontWeight: '600'
        }}>
          {status}
        </span>
      )
    },
  ];

  return (
    <div style={{ 
      minHeight: '100vh',
      background: 'linear-gradient(180deg, #f0fdf4 0%, #ffffff 100%)',
      paddingTop: '80px',
      paddingBottom: '60px'
    }}>
      <div style={{ maxWidth: '1200px', margin: '0 auto', padding: '0 20px' }}>
        
        <Card style={{
          background: 'linear-gradient(135deg, #22c55e 0%, #16a34a 100%)',
          border: 'none',
          borderRadius: '16px',
          marginBottom: '30px',
          boxShadow: '0 10px 30px rgba(34, 197, 94, 0.3)'
        }}>
          <div style={{ textAlign: 'center', color: 'white' }}>
            <div style={{ fontSize: '48px', marginBottom: '10px' }}>🍼</div>
            <h1 style={{ fontSize: '32px', fontWeight: 'bold', margin: '10px 0', color: 'white' }}>
              Cek Data Anak
            </h1>
            <p style={{ fontSize: '16px', margin: 0, opacity: 0.95 }}>
              Masukkan kode unik anak untuk melihat data dan grafik pertumbuhannya secara detail.
            </p>
          </div>
        </Card>

        <Card style={{ borderRadius: '16px', marginBottom: '30px', boxShadow: '0 4px 12px rgba(0,0,0,0.08)' }}>
          <div style={{ display: 'flex', gap: '12px', alignItems: 'center' }}>
            <Input
              size="large"
              placeholder="Masukkan Kode Unik (contoh: AZ123)"
              value={kodeAnak}
              onChange={(e) => setKodeAnak(e.target.value.toUpperCase())}
              onPressEnter={handleSearch}
              prefix={<SearchOutlined style={{ color: '#22c55e' }} />}
              style={{ flex: 1, borderRadius: '8px', fontSize: '16px' }}
            />
            <Button
              type="primary"
              size="large"
              icon={<SearchOutlined />}
              onClick={handleSearch}
              loading={loading}
              style={{
                background: '#22c55e',
                borderColor: '#22c55e',
                borderRadius: '8px',
                fontWeight: '600',
                height: '48px',
                paddingLeft: '32px',
                paddingRight: '32px'
              }}
            >
              Cari Data
            </Button>
          </div>

          <div style={{ 
            marginTop: '20px',
            padding: '16px',
            background: '#f0fdf4',
            borderRadius: '8px',
            display: 'flex',
            alignItems: 'center',
            gap: '12px'
          }}>
            <span style={{ fontSize: '20px' }}>💡</span>
            <span style={{ color: '#166534' }}>
              Silakan masukkan kode unik dan klik "Cari Data" untuk menampilkan informasi.
            </span>
          </div>
        </Card>

        {loading && (
          <div style={{ textAlign: 'center', padding: '60px 0' }}>
            <Spin size="large" />
            <p style={{ marginTop: '20px', fontSize: '16px', color: '#666' }}>Mencari data anak...</p>
          </div>
        )}

        {!loading && !dataAnak && (
          <Card style={{ borderRadius: '16px', textAlign: 'center', padding: '40px' }}>
            <Empty description="Data anak akan ditampilkan di sini setelah pencarian" />
          </Card>
        )}

        {!loading && dataAnak && (
          <div>
            <Card title="📋 Informasi Anak" style={{ borderRadius: '16px', marginBottom: '24px' }}>
              <Descriptions column={{ xs: 1, sm: 2 }} bordered>
                <Descriptions.Item label="Kode Anak"><strong>{dataAnak.kode_anak}</strong></Descriptions.Item>
                <Descriptions.Item label="Nama Lengkap">{dataAnak.nama_lengkap}</Descriptions.Item>
                <Descriptions.Item label="Tanggal Lahir">{dataAnak.tanggal_lahir}</Descriptions.Item>
                <Descriptions.Item label="Usia">{dataAnak.usia_bulan} bulan</Descriptions.Item>
                <Descriptions.Item label="Jenis Kelamin">
                  {dataAnak.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}
                </Descriptions.Item>
                <Descriptions.Item label="Nama Orang Tua">{dataAnak.nama_orang_tua}</Descriptions.Item>
                <Descriptions.Item label="No. Telepon">{dataAnak.no_telepon_orang_tua}</Descriptions.Item>
                <Descriptions.Item label="Alamat">{dataAnak.alamat}</Descriptions.Item>
              </Descriptions>
            </Card>

            {dataAnak.riwayat_pengukuran && dataAnak.riwayat_pengukuran.length > 0 && (
              <Card title="📈 Grafik Pertumbuhan" style={{ borderRadius: '16px', marginBottom: '24px' }}>
                <Line data={getChartData()} options={{ responsive: true }} />
              </Card>
            )}

            <Card title="📊 Riwayat Pengukuran" style={{ borderRadius: '16px' }}>
              <Table
                columns={columns}
                dataSource={dataAnak.riwayat_pengukuran}
                rowKey="tanggal"
                pagination={{ pageSize: 5 }}
                scroll={{ x: true }}
              />
            </Card>
          </div>
        )}

      </div>
    </div>
  );
};

export default DataAnak;
