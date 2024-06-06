<?php

namespace App\Notifications;

use App\Models\Jadwal\Jadwal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;

class ScheduleCollisionNotification extends Notification
{
    use Queueable;

    protected $jadwal;

    public function __construct(Jadwal $jadwal)
    {
        $this->jadwal = $jadwal;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $detailJadwal = $this->jadwal->detailJadwal;
        $detailJadwalName = 'N/A';

        if ($detailJadwal) {
            if (!empty($detailJadwal->mata_kuliah) && !empty($detailJadwal->kelas)) {
                $detailJadwalName = $detailJadwal->mata_kuliah . ' (' . $detailJadwal->kelas . ')';
            } elseif (!empty($detailJadwal->tipe) && !empty($detailJadwal->nama_mahasiswa)) {
                $detailJadwalName = $detailJadwal->tipe . ' - ' . $detailJadwal->nama_mahasiswa;
            } elseif (!empty($detailJadwal->tipe)) {
                $detailJadwalName = $detailJadwal->tipe;
            } else {
                $detailJadwalName = 'N/A';
            }
        }

        // Convert times to Jakarta timezone
        $waktuMulai = Carbon::parse($this->jadwal->waktu_mulai)->setTimezone('Asia/Jakarta');
        $waktuSelesai = Carbon::parse($this->jadwal->waktu_selesai)->setTimezone('Asia/Jakarta');

        return (new MailMessage)
            ->subject('Deteksi Tabrakan Jadwal')
            ->greeting('Halo!')
            ->line('Sebuah jadwal yang Anda buat atau perbarui mengalami tabrakan.')
            ->line('Detail Jadwal:')
            ->line('Nama: ' . $detailJadwalName)
            ->line('Tanggal: ' . Carbon::parse($this->jadwal->tanggal_mulai)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y'))
            ->line('Waktu Mulai: ' . $waktuMulai->format('H:i'))
            ->line('Waktu Selesai: ' . $waktuSelesai->format('H:i'))
            ->line('Ruangan: ' . $this->jadwal->ruangan)
            ->action('Lihat Jadwal', url('/schedules/' . $this->jadwal->id))
            ->line('Silakan sesuaikan jadwal untuk mengatasi konflik.')
            ->salutation('Salam, Laravel');
    }
}
