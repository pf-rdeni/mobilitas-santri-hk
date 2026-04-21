<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\SantriModel;
use App\Models\JadwalMobilitasModel;
use App\Models\RegistrasiTiketModel;

class DummyTiketSeeder extends Seeder
{
    public function run()
    {
        $santriModel = new SantriModel();
        $jadwalModel = new JadwalMobilitasModel();
        $tiketModel = new RegistrasiTiketModel();

        // Cari jadwal aktif pertama, kalau kosong cari yang pertama ada
        $jadwal = $jadwalModel->where('status', 'aktif')->first();
        if (!$jadwal) {
            $jadwal = $jadwalModel->first();
        }

        if (!$jadwal) {
            echo "Gagal: Tidak ada Jadwal Mobilitas di database.\n";
            return;
        }

        $semuaSantri = $santriModel->findAll();
        if (empty($semuaSantri)) {
            echo "Gagal: Tidak ada data Santri di database.\n";
            return;
        }

        $maskapaiList = ['Garuda Indonesia', 'Batik Air', 'Citilink', 'Lion Air', 'Super Air Jet', 'Pelita Air'];
        $bandaraAsal = ['Bandara Soekarno-Hatta (CGK)', 'Bandara Halim Perdanakusuma (HLP)'];
        $bandaraTujuan = 'Bandara Internasional Zainuddin Abdul Madjid (LOP)';

        $count = 0;
        foreach ($semuaSantri as $santri) {
            // Cek jika santri ini sudah terdaftar tiketnya di jadwal ini
            $exists = $tiketModel->where('id_santri', $santri->id)
                                 ->where('id_jadwal', $jadwal->id)
                                 ->first();

            if (!$exists) {
                // Random waktu dari jam 06:00 sampai 20:00 dengan kelipatan 10 menit
                $jam = str_pad(rand(6, 20), 2, '0', STR_PAD_LEFT);
                $menit = str_pad(rand(0, 5) * 10, 2, '0', STR_PAD_LEFT);
                
                // Generator kode booking random 6 huruf/angka
                $kodeBooking = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));

                // Distribusi probabilitas ikut_bus = 'ya' (80%), status_transfer = 'sudah' (70%)
                $ikutBus = (rand(1, 10) > 2) ? 'ya' : 'tidak';
                $statusTransfer = (rand(1, 10) > 3) ? 'sudah' : 'belum';

                $data = [
                    'id_santri'         => $santri->id,
                    'id_jadwal'         => $jadwal->id,
                    'maskapai'          => $maskapaiList[array_rand($maskapaiList)],
                    'kode_booking'      => $kodeBooking,
                    'terminal_bandara'  => (string) rand(1, 3), // Terminal 1, 2, atau 3
                    'waktu_penerbangan' => "$jam:$menit",
                    'ikut_bus'          => $ikutBus,
                    'status_transfer'   => $statusTransfer,
                    'bandara_asal'      => $bandaraAsal[array_rand($bandaraAsal)],
                    'bandara_tujuan'    => $bandaraTujuan,
                    'bukti_transfer'    => null,
                    'bukti_tiket'       => null
                ];

                $tiketModel->insert($data);
                $count++;
            }
        }

        echo "Selesai! Berhasil mengenerate $count data registrasi tiket dummy (simulasi penerbangan & bus) untuk jadwal ID {$jadwal->id}.\n";
    }
}
