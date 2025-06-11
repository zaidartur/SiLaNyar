<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class LaporanKeuanganController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'periode' => 'nullable|in:semua,bulanan,tahunan,rentang_tanggal',
            'bulan' => 'required_if:periode,bulanan|nullable|integer|min:1|max:12',
            'tahun_bulanan' => 'required_if:periode,bulanan|nullable|integer|digits:4',
            'tahun_tahunan' => 'required_if:periode,tahunan|nullable|integer|digits:4',
            'tanggal_mulai' => 'required_if:periode,rentang_tanggal|nullable|date',
            'tanggal_akhir' => 'required_if:periode,rentang_tanggal|nullable|after_or_equal:tanggal_mulai',
        ]);

        $periode = $request->input('periode', 'semua');

        $query = Pembayaran::with([
            'form_pengajuan:id,kode_pengajuan,id_instansi',
            'form_pengajuan.instansi:id,nama,id_user',
            'form_pengajuan.instansi.user:id,nama',
        ])->where('status_pembayaran', 'selesai');

        // Apply filters
        if ($periode === 'bulanan' && $request->filled(['bulan', 'tahun_bulanan'])) {
            $bulan = $request->input('bulan');
            $tahun = $request->input('tahun_bulanan');
            $query->whereYear('tanggal_pembayaran', $tahun)
                ->whereMonth('tanggal_pembayaran', $bulan);
        } else if ($periode === 'tahunan' && $request->filled('tahun_tahunan')) {
            $tahun = $request->input('tahun_tahunan');
            $query->whereYear('tanggal_pembayaran', $tahun);
        } else if ($periode === 'rentang_tanggal' && $request->filled(['tanggal_mulai', 'tanggal_akhir'])) {
            $tanggalMulai = $request->input('tanggal_mulai');
            $tanggalAkhir = $request->input('tanggal_akhir');
            $query->whereBetween('tanggal_pembayaran', [$tanggalMulai, $tanggalAkhir]);
        }

        // Create a separate query for diagram data to avoid GROUP BY conflicts
        $diagramQuery = clone $query;

        $label = [];
        $data = [];

        if ($periode === 'bulanan' && $request->filled(['bulan', 'tahun_bulanan'])) {
            $hasil = $diagramQuery->select(
                DB::raw('DATE(tanggal_pembayaran) as tanggal'),
                DB::raw('SUM(total_biaya) as total'),
            )->groupBy('tanggal')->orderBy('tanggal')->get();

            foreach ($hasil as $diagram) {
                $label[] = Carbon::parse($diagram->tanggal)->format('d-m-Y');
                $data[] = (float) $diagram->total;
            }
        } elseif ($periode === 'tahunan' && $request->filled(['tahun_tahunan'])) {
            $hasil = $diagramQuery->select(
                DB::raw('YEAR(tanggal_pembayaran) as tahun'),
                DB::raw('MONTH(tanggal_pembayaran) as bulan'),
                DB::raw('SUM(total_biaya) as total'),
            )->groupBy('tahun', 'bulan')->orderBy('tahun')->orderBy('bulan')->get();

            foreach ($hasil as $diagram) {
                $label[] = Carbon::createFromDate($diagram->tahun, $diagram->bulan, 1)->format('m-Y');
                $data[] = (float) $diagram->total;
            }
        } elseif ($periode === 'rentang_tanggal' && $request->filled(['tanggal_mulai', 'tanggal_akhir'])) {
            $tanggalMulai = Carbon::parse($request->input('tanggal_mulai'));
            $tanggalAkhir = Carbon::parse($request->input('tanggal_akhir'));

            $bedaHari = $tanggalMulai->diffInDays($tanggalAkhir);

            if ($bedaHari <= 60) {
                $hasil = $diagramQuery->select(
                    DB::raw('DATE(tanggal_pembayaran) as tanggal'),
                    DB::raw('SUM(total_biaya) as total'),
                )->groupBy('tanggal')->orderBy('tanggal')->get();

                foreach ($hasil as $diagram) {
                    $label[] = Carbon::parse($diagram->tanggal)->format('d-m-Y');
                    $data[] = (float) $diagram->total;
                }
            } else {
                $hasil = $diagramQuery->select(
                    DB::raw('YEAR(tanggal_pembayaran) as tahun'),
                    DB::raw('MONTH(tanggal_pembayaran) as bulan'),
                    DB::raw('SUM(total_biaya) as total'),
                )->groupBy('tahun', 'bulan')->orderBy('tahun')->orderBy('bulan')->get();

                foreach ($hasil as $diagram) {
                    $label[] = Carbon::createFromDate($diagram->tahun, $diagram->bulan, 1)->format('m-Y');
                    $data[] = (float) $diagram->total;
                }
            }
        } else {
            $hasil = $diagramQuery->select(
                DB::raw('YEAR(tanggal_pembayaran) as tahun'),
                DB::raw('MONTH(tanggal_pembayaran) as bulan'),
                DB::raw('SUM(total_biaya) as total'),
            )->groupBy('tahun', 'bulan')->orderBy('tahun')->orderBy('bulan')->get();

            foreach ($hasil as $diagram) {
                $label[] = Carbon::createFromDate($diagram->tahun, $diagram->bulan, 1)->format('m-Y');
                $data[] = (float) $diagram->total;
            }
        }

        // pisahkan query utama diagram (biarkan seperti semula)
        $laporanKeuangan = Pembayaran::with([
            'form_pengajuan:id,kode_pengajuan,id_instansi',
            'form_pengajuan.instansi:id,nama',
            'form_pengajuan.instansi.user:id,nama',
        ])
            ->where('status_pembayaran', 'selesai');

        // tambahkan kembali filter sesuai periode
        if ($periode === 'bulanan' && $request->filled(['bulan', 'tahun_bulanan'])) {
            $bulan = $request->input('bulan');
            $tahun = $request->input('tahun_bulanan');
            $laporanKeuangan->whereYear('tanggal_pembayaran', $tahun)
                ->whereMonth('tanggal_pembayaran', $bulan);
        } elseif ($periode === 'tahunan' && $request->filled('tahun_tahunan')) {
            $tahun = $request->input('tahun_tahunan');
            $laporanKeuangan->whereYear('tanggal_pembayaran', $tahun);
        } elseif ($periode === 'rentang_tanggal' && $request->filled(['tanggal_mulai', 'tanggal_akhir'])) {
            $tanggalMulai = $request->input('tanggal_mulai');
            $tanggalAkhir = $request->input('tanggal_akhir');
            $laporanKeuangan->whereBetween('tanggal_pembayaran', [$tanggalMulai, $tanggalAkhir]);
        }

        $laporanKeuangan = $laporanKeuangan->orderByDesc('tanggal_pembayaran')->get();

        $totalPemasukanQuery = Pembayaran::where('status_pembayaran', 'selesai');

        if ($periode === 'bulanan' && $request->filled(['bulan', 'tahun_bulanan'])) {
            $bulan = $request->input('bulan');
            $tahun = $request->input('tahun_bulanan');
            $totalPemasukanQuery->whereYear('tanggal_pembayaran', $tahun)
                ->whereMonth('tanggal_pembayaran', $bulan);
        } elseif ($periode === 'tahunan' && $request->filled('tahun_tahunan')) {
            $tahun = $request->input('tahun_tahunan');
            $totalPemasukanQuery->whereYear('tanggal_pembayaran', $tahun);
        } elseif ($periode === 'rentang_tanggal' && $request->filled(['tanggal_mulai', 'tanggal_akhir'])) {
            $tanggalMulai = $request->input('tanggal_mulai');
            $tanggalAkhir = $request->input('tanggal_akhir');
            $totalPemasukanQuery->whereBetween('tanggal_pembayaran', [$tanggalMulai, $tanggalAkhir]);
        }

        $totalPemasukan = $totalPemasukanQuery->sum('total_biaya');

        // Prepare filter data with proper data types
        $filterData = $request->all();
        if (isset($filterData['bulan'])) {
            $filterData['bulan'] = (int) $filterData['bulan'];
        }
        if (isset($filterData['tahun_bulanan'])) {
            $filterData['tahun_bulanan'] = (int) $filterData['tahun_bulanan'];
        }
        if (isset($filterData['tahun_tahunan'])) {
            $filterData['tahun_tahunan'] = (int) $filterData['tahun_tahunan'];
        }

        return Inertia::render('pegawai/laporan/Index', [
            'filter' => $filterData,
            'laporan_keuangan' => $laporanKeuangan,
            'total_pemasukan' => $totalPemasukan,
            'diagram' => [
                'label' => $label,
                'data' => [
                    'label' => 'Total Pemasukan',
                    'backgroundColor' =>  'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'data' => $data,
                    'fill' => true,
                    'tension' => 0.1,
                ]
            ],
            'tahunTersedia' => Pembayaran::selectRaw('YEAR(tanggal_pembayaran) as tahun')
                ->where('status_pembayaran', 'selesai')
                ->distinct()
                ->orderBy('tahun', 'desc')
                ->pluck('tahun'),
        ]);
    }
}
