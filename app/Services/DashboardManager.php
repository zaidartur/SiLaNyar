<?php

namespace App\Services;

use App\Models\User;
use App\Models\Pengujian;
use App\Models\Jadwal;
use App\Models\FormPengajuan;
use App\Models\HasilUji;

class DashboardManager
{
    protected function inertiaPageExists($page): bool
    {
        $basePath = resource_path('js/pages/');
        $extensions = ['vue', 'jsx', 'tsx', 'js', 'ts'];
        $pagePath = str_replace('.', '/', $page);

        foreach ($extensions as $ext) {
            if (file_exists("{$basePath}{$pagePath}.{$ext}")) {
                return true;
            }
        }

        return false;
    }

    public function resolve(User $user): array
    {
        $data = [];

        foreach ($user->roles as $role) {
            if ($role->dashboard_view) {
                $data = array_merge_recursive($data, $this->getDashboardData($role->dashboard_view, $user));
            }
        }

        return $data;
    }

    public function resolveView(User $user): string
    {
        $priorityOrder = [
            'dashboard/SuperAdmin',
            'dashboard/KepalaDinas',
            'dashboard/KepalaLab',
            'dashboard/PengendaliTeknis',
            'dashboard/Penyelia',
            'dashboard/Admin',
            'dashboard/Teknisi',
            'dashboard/PPCU',
            'customer/dashboard/Index',
        ];

        $userViews = $user->roles->pluck('dashboard_view')->filter()->toArray();

        foreach ($priorityOrder as $priorityView) {
            if (in_array($priorityView, $userViews) && $this->inertiaPageExists($priorityView)) {
                return $priorityView;
            }
        }

        foreach ($user->roles as $role) {
            $view = $role->dashboard_view;

            if ($view && $this->inertiaPageExists($view)) {
                return $view;
            }
        }

        return 'customer/dashboard/Index';
    }

    protected function getDashboardData(string $view, User $user): array
    {
        return match ($view) {
            'dashboard/SuperAdmin' => [
                'customer' => User::role(['pelanggan', 'customer'])->get(),
                'pegawai' => User::role([
                    'superadmin', 'staf_administrator', 'pengendali_teknis',
                    'ppcu', 'penyelia', 'analis', 'kepala_lab', 'kepala_dinas',
                    'admin', 'teknisi'
                ])->get(),
            ],
            'dashboard/Admin' => [
                'statistik' => [
                    'pengajuan' => FormPengajuan::count(),
                    'jadwal' => Jadwal::count(),
                    'pengujian' => Pengujian::count(),
                    'hasil_uji' => HasilUji::count(),
                ],
            ],
            'dashboard/Teknisi' => [
                'statistik' => [
                    'jadwalPengujian' => Pengujian::where('id_user', $user->id)->count(),
                    'jadwalPengambilan' => Jadwal::where('id_user', $user->id)->count(),
                ],
                'pengajuan' => FormPengajuan::all(),
                'pengambilan' => Jadwal::with('user')->where('id_user', $user->id)->get(),
            ],
            'dashboard/PengendaliTeknis' => [
                'statistik' => [
                    'pengajuan' => FormPengajuan::count(),
                    'jadwal' => Jadwal::count(),
                    'pengujian' => Pengujian::count(),
                    'hasil_uji' => HasilUji::count(),
                ],
                'pengajuan' => FormPengajuan::with('instansi')->latest()->take(10)->get(),
            ],
            'dashboard/PPCU' => [
                'statistik' => [
                    'tugasPengambilan' => Jadwal::where('id_user', $user->id)->count(),
                    'selesai' => Jadwal::where('id_user', $user->id)->where('status', 'diterima')->count(),
                ],
                'pengambilan' => Jadwal::with('user')->where('id_user', $user->id)->get(),
            ],
            'dashboard/Penyelia' => [
                'statistik' => [
                    'totalPengujian' => Pengujian::count(),
                    'dalamProses' => Pengujian::where('status', 'diproses')->count(),
                ],
                'pengujian' => Pengujian::with(['user', 'pengajuan'])->latest()->take(10)->get(),
            ],
            'dashboard/KepalaLab' => [
                'statistik' => [
                    'perluVerifikasi' => HasilUji::where('status', 'proses_review')->count(),
                    'totalSelesai' => HasilUji::where('status', 'selesai')->count(),
                ],
                'hasil_uji' => HasilUji::with('pengujian.pengajuan')->latest()->take(10)->get(),
            ],
            'dashboard/KepalaDinas' => [
                'statistik' => [
                    'perluTTE' => HasilUji::where('status', 'proses_peresmian')->count(),
                    'totalSelesai' => HasilUji::where('status', 'selesai')->count(),
                    'totalPengajuan' => FormPengajuan::count(),
                ],
                'hasil_uji' => HasilUji::with('pengujian.pengajuan')->latest()->take(10)->get(),
            ],
            default => [
                'user' => $user
            ],
        };
    }
}
