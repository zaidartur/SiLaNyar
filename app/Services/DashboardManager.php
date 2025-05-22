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
        $basePath = resource_path('js/Pages/');
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
        foreach ($user->roles as $role) {
            $view = $role->dashboard_view;

            if ($view && $this->inertiaPageExists($view)) {
                return $view;
            }
        }

        return 'customer/Dashboard';
    }

    protected function getDashboardData(string $view, User $user): array
    {
        return match ($view) {
            'dashboard/SuperAdmin' => [
                'customer' => User::role('customer')->get(),
                'pegawai' => User::role(['admin', 'teknisi', 'superadmin'])->get(),
            ],
            'dashboard/Teknisi' => [
                'statistik' => [
                    'jadwalPengujian' => Pengujian::where('id_user', $user->id)->count(),
                    'jadwalPengambilan' => Jadwal::where('id_user', $user->id)->count(),
                ],
                'pengajuan' => FormPengajuan::all(),
                'pengambilan' => Jadwal::all(),
            ],
            'dashboard/Admin' => [
                'statistik' => [
                    'pengajuan' => FormPengajuan::count(),
                    'jadwal' => Jadwal::count(),
                    'pengujian' => Pengujian::count(),
                    'hasil_uji' => HasilUji::count(),
                ],
            ],
            default => [
                'user' => $user
            ],
        };
    }
}
