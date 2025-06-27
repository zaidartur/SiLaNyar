<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\HasilUji;
use App\Models\HasilUjiHistori;
use App\Models\ParameterUji;
use App\Models\Pengujian;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class HasilUjiController extends Controller
{
    private const STATUS_FLOW = [
        'draf' => ['proses_review', 'revisi'],
        'revisi' => ['proses_review', 'draf'],
        'proses_review' => ['proses_peresmian', 'revisi'],
        'proses_peresmian' => ['selesai', 'revisi'],
        'selesai' => [],
    ];

    // menampilkan daftar hasil uji
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasRole('teknisi')) {
            $hasil_uji = HasilUji::whereHas('pengujian', function ($query) use ($user) {
                $query->where('id_user', $user->id);
            })
                ->with([
                    'pengujian.form_pengajuan.instansi.user',
                    'pengujian.user'
                ])->get();
        } else {
            $hasil_uji = HasilUji::with([
                'pengujian.form_pengajuan.instansi.user',
                'pengujian.user'
            ])->get();
        }

        $unscheduled_pengujian = \App\Models\Pengujian::whereDoesntHave('hasil_uji')
            ->with('form_pengajuan.instansi')
            ->where('id_user', $user->id)
            ->get();

        return Inertia::render('pegawai/hasil_uji/Index', [
            'hasil_uji' => $hasil_uji,
            'unscheduled_pengujian' => $unscheduled_pengujian,
            'userRole' => Auth::user()->roles->pluck('name')->first(),
        ]);
    }

    // form tambah hasil uji
    public function create(Request $request)
    {
        $user = Auth::user();
        $pengujianList = Pengujian::with('form_pengajuan.instansi.user')
            ->whereDoesntHave('hasil_uji')
            ->where('id_user', $user->id)
            ->where('status', 'selesai')
            ->select('id', 'kode_pengujian', 'id_form_pengajuan', 'status')
            ->get();

        $pengujian = null;
        $semuaParameter = [];

        if ($request->id_pengujian) {
            $pengujian = Pengujian::with([
                'form_pengajuan.kategori.parameter',
                'form_pengajuan.kategori.subkategori.parameter',
                'form_pengajuan.instansi.user',
                'user'
            ])
                ->find($request->id_pengujian);

            $kategori = $pengujian->form_pengajuan->kategori;

            $parameterKategori = collect($kategori->parameter)->map(function ($param) {
                return [
                    'id' => $param->id,
                    'nama' => $param->nama_parameter,
                    'satuan' => $param->satuan,
                    'baku_mutu' => $param->pivot->baku_mutu ?? null,
                ];
            });

            $parameterSubKategori = collect($kategori->subkategori)->flatMap(function ($sub) {
                return $sub->parameter->map(function ($param) {
                    return [
                        'id' => $param->id,
                        'nama' => $param->nama_parameter,
                        'satuan' => $param->satuan,
                        'baku_mutu' => $param->pivot->baku_mutu ?? null,
                    ];
                });
            });

            $semuaParameter = $parameterKategori->merge($parameterSubKategori)->unique('id')->values();
        }

        return Inertia::render('pegawai/hasil_uji/Tambah', [
            'pengujianList' => $pengujianList,
            'pilihPengujian' => $pengujian,
            'parameter' => $semuaParameter,
        ]);
    }

    // proses tambah hasil uji
    public function store(Request $request)
    {
        /** @var \App\Models\User */
        $user = Auth::user();
        $validated = $request->validate([
            'id_pengujian' => 'required|exists:pengujian,id',
            'hasil' => 'required|array',
            'hasil.*.id_parameter' => 'required|exists:parameter_uji,id',
            'hasil.*.nilai' => 'nullable|string',
            'hasil.*.keterangan' => 'nullable|string|max:255',
        ], [
            'id_pengujian.required' => 'Pengujian Wajib Diisi.',
            'id_pengujian.exists' => 'Data Pengujian Tidak Ditemukan.',
            'hasil.required' => 'Data Hasil Wajib Diisi.',
            'hasil.array' => 'Format Hasil Uji Tidak Valid.',
            'hasil.*.id_parameter.required' => 'Parameter Wajib Diisi.',
            'hasil.*.id_parameter.exists' => 'Parameter Tidak Ditemukan.',
            'hasil.*.keterangan.max' => 'Keterangan Maksimal 255 Karakter.',
        ]);

        DB::beginTransaction();

        try {
            $hasil_uji = HasilUji::create([
                'id_pengujian' => $validated['id_pengujian'],
                'status' => 'draf',
                'diupdate_oleh' => $user->nama,
            ]);

            foreach ($validated['hasil'] as $item) {
                DB::table('parameter_pengujian')->updateOrInsert(
                    [
                        'id_pengujian' => $validated['id_pengujian'],
                        'id_parameter' => $item['id_parameter'],
                    ],
                    [
                        'nilai' => $item['nilai'] ?? null,
                        'keterangan' => $item['keterangan'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }

            $pdf = Pdf::loadView('hasil_uji.show', [
                'hasil_uji' => $hasil_uji->fresh()->load([
                    'pengujian.parameter_uji',
                    'pengujian.form_pengajuan.kategori.parameter',
                    'pengujian.form_pengajuan.kategori.subkategori.parameter',
                    'pengujian.form_pengajuan.instansi.user',
                    'pengujian.user'
                ]),
                'tanggal' => now()->format('d-m-Y'),
                'is_customer' => false
            ]);

            $path = 'pdf/hasil_uji_' . $hasil_uji->id . '.pdf';
            Storage::put($path, $pdf->output());

            $hasil_uji->update(['file_pdf' => $path]);

            DB::commit();

            return Redirect::route('pegawai.hasil_uji.index')->with('message', 'Hasil Uji Berhasil Dibuat!');
        } catch (\Exception $err) {
            DB::rollBack();
            return Redirect::back()->withErrors(['error' => 'Gagal Menyimpan Hasil Uji' . $err->getMessage()]);
        }
    }

    // form edit hasil uji
    public function edit($id)
    {
        $hasil_uji = HasilUji::with([
            'pengujian.parameter_uji',
            'pengujian.form_pengajuan.kategori.parameter',
            'pengujian.form_pengajuan.kategori.subkategori.parameter',
            'pengujian.form_pengajuan.instansi.user',
            'pengujian.user',
        ])->findOrFail($id);

        if (!in_array($hasil_uji->status, ['draf', 'revisi'])) {
            return Redirect::route('pegawai.hasil_uji.index')->withErrors(['status' => 'Hanya Status Hasil Uji Draf dan Revisi Yang Dapat Diedit']);
        }

        $pengujian = $hasil_uji->pengujian;
        $kategori = $pengujian->form_pengajuan->kategori;

        $parameterKategori = collect($kategori->parameter)->map(function ($param) {
            return [
                'id' => $param->id,
                'nama' => $param->nama_parameter,
                'satuan' => $param->satuan,
                'baku_mutu' => $param->pivot->baku_mutu ?? null,
            ];
        });

        $parameterSubKategori = collect($kategori->subkategori)->flatMap(function ($sub) {
            return $sub->parameter->map(function ($param) {
                return [
                    'id' => $param->id,
                    'nama' => $param->nama_parameter,
                    'satuan' => $param->satuan,
                    'baku_mutu' => $param->pivot->baku_mutu ?? null,
                ];
            });
        });

        $semuaParameter = $parameterKategori->merge($parameterSubKategori)->unique('id')->values();

        $nilaiTersimpan = DB::table('parameter_pengujian')->where('id_pengujian', $pengujian->id)->get()->keyBy('id_parameter');

        $parameterDenganNilai = $semuaParameter->map(function ($param) use ($nilaiTersimpan) {
            $nilai = $nilaiTersimpan[$param['id']] ?? null;
            return array_merge($param, [
                'nilai' => $nilai->nilai ?? null,
                'keterangan' => $nilai->keterangan ?? null,
            ]);
        });

        return Inertia::render('pegawai/hasil_uji/Edit', [
            'hasil_uji' => $hasil_uji,
            'pengujian' => $pengujian,
            'parameter' => $parameterDenganNilai
        ]);
    }

    public function update(HasilUji $hasil_uji, Request $request)
    {
        /** @var \App\Models\User */
        $user = Auth::user();

        if (!in_array($hasil_uji->status, ['draf', 'revisi'])) {
            return Redirect::route('pegawai.hasil_uji.index')->withErrors(['status' => 'Gagal Mengedit Hasil Uji']);
        }

        $validated = $request->validate([
            'hasil' => 'required|array',
            'hasil.*.id_parameter' => 'required|exists:parameter_uji,id',
            'hasil.*.nilai' => 'nullable|string',
            'hasil.*.keterangan' => 'nullable|string|max:255',
        ], [
            'hasil.required' => 'Data Hasil Wajib Diisi.',
            'hasil.array' => 'Format Hasil Uji Tidak Valid.',
            'hasil.*.id_parameter.required' => 'Parameter Wajib Diisi.',
            'hasil.*.id_parameter.exists' => 'Parameter Tidak Ditemukan.',
            'hasil.*.keterangan.max' => 'Keterangan Maksimal 255 Karakter.',
        ]);

        DB::beginTransaction();

        try {
            $parameterKategori = collect($hasil_uji->pengujian->form_pengajuan->kategori->parameter)->map(function ($param) {
                return [
                    'id' => $param->id,
                    'nama_parameter' => $param->nama_parameter,
                    'satuan' => $param->satuan,
                    'baku_mutu' => $param->pivot->baku_mutu ?? null,
                ];
            });

            $parameterSubKategori = collect($hasil_uji->pengujian->form_pengajuan->kategori->subkategori)->flatMap(function ($sub) {
                return $sub->parameter->map(function ($param) {
                    return [
                        'id' => $param->id,
                        'nama_parameter' => $param->nama_parameter,
                        'satuan' => $param->satuan,
                        'baku_mutu' => $param->pivot->baku_mutu ?? null,
                    ];
                });
            });

            $semuaParameter = $parameterKategori->merge($parameterSubKategori)->keyBy('id');

            $dataSebelum = DB::table('parameter_pengujian')
                ->where('id_pengujian', $hasil_uji->id_pengujian)
                ->get()
                ->map(function ($item) use ($semuaParameter) {
                    $parameter = $semuaParameter[$item->id_parameter] ?? null;

                    return [
                        'id_parameter' => $item->id_parameter,
                        'nama_parameter' => $parameter['nama_parameter'] ?? 'Tidak Ditemukan',
                        'satuan' => $parameter['satuan'] ?? null,
                        'baku_mutu' => $parameter['baku_mutu'] ?? null,
                        'nilai' => $item->nilai,
                        'keterangan' => $item->keterangan,
                    ];
                });

            HasilUjiHistori::create([
                'id_hasil_uji' => $hasil_uji->id,
                'data_parameterdanpengujian' => $dataSebelum,
                'status' => $hasil_uji->status,
                'diupdate_oleh' => $user->nama,
                'created_at' => now(),
            ]);

            $hasil_uji->update(['diupdate_oleh' => $user->nama]);

            foreach ($validated['hasil'] as $item) {
                DB::table('parameter_pengujian')->updateOrInsert(
                    [
                        'id_pengujian' => $hasil_uji->id_pengujian,
                        'id_parameter' => $item['id_parameter'],
                    ],
                    [
                        'nilai' => $item['nilai'] ?? null,
                        'keterangan' => $item['keterangan'] ?? null,
                        'updated_at' => now(),
                    ],
                );
            }

            $pdf = Pdf::loadView('hasil_uji.show', [
                'hasil_uji' => $hasil_uji->fresh()->load([
                    'pengujian.form_pengajuan.kategori.parameter',
                    'pengujian.form_pengajuan.kategori.subkategori.parameter',
                    'pengujian.form_pengajuan.instansi.user',
                    'pengujian.user'
                ]),
                'tanggal' => now()->format('d-m-Y'),
                'is_customer' => false
            ]);

            $path = 'pdf/hasil_uji_' . $hasil_uji->id . '.pdf';

            Storage::put($path, $pdf->output());

            $hasil_uji->update(['file_pdf' => $path]);

            DB::commit();

            return Redirect::route('pegawai.hasil_uji.detail', $hasil_uji->id)->with('message', 'Hasil Uji Berhasil Diupdate!');
        } catch (\Exception $err) {
            Log::error("Update error: " . $err->getMessage());
            DB::rollBack();
            return Redirect::back()->withErrors(['error' => 'Gagal Memperbarui Hasil Uji ' . $hasil_uji->kode_hasil_uji . ' Dengan Error ' . $err->getMessage()]);
        }
    }

    // proses verifikasi hasil uji
    public function verifikasi($id, Request $request)
    {
        /** @var \App\Models\User */
        $user = Auth::user();

        $hasil_uji = HasilUji::findOrFail($id);

        $request->validate([
            'status' => 'required|in:draf,revisi,proses_review,proses_peresmian,selesai',
        ]);

        $statusSekarang = $hasil_uji->status;
        $statusTersedia = self::STATUS_FLOW[$statusSekarang] ?? [];

        if (!in_array($request->status, $statusTersedia)) {
            return Redirect::back()->withErrors([
                'status' => 'Status Yang Anda Masukkan Tidak Valid'
            ]);
        }

        $pengajuan = $hasil_uji->pengujian->form_pengajuan->id;

        $hasilUjiLain = HasilUji::whereHas('pengujian', function ($query) use ($pengajuan, $hasil_uji) {
            $query->where('id_form_pengajuan', $pengajuan)
                ->where('id', '!=', $hasil_uji->id_pengujian);
        })
            ->whereIn('status', ['selesai', 'proses_peresmian', 'proses_review'])
            ->exists();

        if ($hasilUjiLain) {
            return Redirect::back()->withErrors([
                'status' => 'Tidak dapat memverifikasi hasil uji karena salah satu hasil uji dari pengajuan ini sudah disahkan (status final).',
            ]);
        }

        DB::beginTransaction();

        try {
            $parameterKategori = collect($hasil_uji->pengujian->form_pengajuan->kategori->parameter)->map(function ($param) {
                return [
                    'id' => $param->id,
                    'nama_parameter' => $param->nama_parameter,
                    'satuan' => $param->satuan,
                    'baku_mutu' => $param->pivot->baku_mutu ?? null,
                ];
            });

            $parameterSubKategori = collect($hasil_uji->pengujian->form_pengajuan->kategori->subkategori)->flatMap(function ($sub) {
                return $sub->parameter->map(function ($param) {
                    return [
                        'id' => $param->id,
                        'nama_parameter' => $param->nama_parameter,
                        'satuan' => $param->satuan,
                        'baku_mutu' => $param->pivot->baku_mutu ?? null,
                    ];
                });
            });

            $semuaParameter = $parameterKategori->merge($parameterSubKategori)->keyBy('id');

            $dataSebelum = DB::table('parameter_pengujian')
                ->where('id_pengujian', $hasil_uji->id_pengujian)
                ->get(['id_parameter', 'nilai', 'keterangan'])
                ->map(function ($item) use ($semuaParameter) {
                    $parameter = $semuaParameter[$item->id_parameter] ?? null;

                    return [
                        'id_parameter' => $item->id_parameter,
                        'nama_parameter' => $parameter['nama_parameter'] ?? 'Tidak Ditemukan',
                        'satuan' => $parameter['satuan'] ?? null,
                        'baku_mutu' => $parameter['baku_mutu'] ?? null,
                        'nilai' => $item->nilai,
                        'keterangan' => $item->keterangan,
                    ];
                });

            HasilUjiHistori::create([
                'id_hasil_uji' => $hasil_uji->id,
                'data_parameterdanpengujian' => $dataSebelum,
                'status' => $hasil_uji->status,
                'diupdate_oleh' => $user->nama,
                'created_at' => now(),
            ]);

            $dataUpdate = ['status' => $request->status, 'diupdate_oleh' => $user->nama];

            if ($request->status === 'proses_review') {
                $dataUpdate['proses_review_at'] = now();
            }

            $hasil_uji->update($dataUpdate);

            DB::commit();

            return Redirect::route('pegawai.hasil_uji.index')->with('message', 'Hasil Uji Berhasil Diupdate');
        } catch (\Exception $err) {
            DB::rollBack();
            return Redirect::back()->withErrors(['error' => 'Gagal Melakukan Update Status Hasil Uji ' . $err->getMessage()]);
        }
    }

    // menampilkan detail hasil uji dengan kemampuan edit
    public function show($id)
    {
        $hasil_uji = HasilUji::with([
            'pengujian.form_pengajuan.kategori.parameter',
            'pengujian.form_pengajuan.kategori.subkategori.parameter',
            'pengujian.form_pengajuan.instansi.user',
            'pengujian.user'
        ])->findOrFail($id);

        $parameterKategori = collect($hasil_uji->pengujian->form_pengajuan->kategori->parameter)->map(function ($param) {
            return [
                'id_parameter' => $param->id,
                'nama_parameter' => $param->nama_parameter,
                'satuan' => $param->satuan,
                'baku_mutu' => $param->pivot->baku_mutu ?? null,
            ];
        });

        $parameterSubKategori = collect($hasil_uji->pengujian->form_pengajuan->kategori->subkategori)->flatMap(function ($sub) {
            return $sub->parameter->map(function ($param) {
                return [
                    'id_parameter' => $param->id,
                    'nama_parameter' => $param->nama_parameter,
                    'satuan' => $param->satuan,
                    'baku_mutu' => $param->pivot->baku_mutu ?? null,
                ];
            });
        });

        $semuaParameter = $parameterKategori->merge($parameterSubKategori)->keyBy('id_parameter');

        $parameterPengujian = DB::table('parameter_pengujian')
            ->where('id_pengujian', $hasil_uji->id_pengujian)
            ->get()
            ->map(function ($item) use ($semuaParameter) {
                $parameter = $semuaParameter[$item->id_parameter] ?? null;

                return [
                    'id_parameter' => $item->id_parameter,
                    'nama_parameter' => $parameter['nama_parameter'] ?? 'Tidak Ditemukan',
                    'satuan' => $parameter['satuan'] ?? null,
                    'nilai' => $item->nilai ?? null,
                    'baku_mutu' => $parameter['baku_mutu'] ?? null,
                    'keterangan' => $item->keterangan ?? null
                ];
            });

        // Get available parameters for editing
        $availableParameters = $semuaParameter->values()->map(function ($param) use ($parameterPengujian) {
            $pengujianData = $parameterPengujian->firstWhere('id_parameter', $param['id_parameter']);
            return array_merge($param, [
                'nilai' => $pengujianData['nilai'] ?? null,
                'keterangan' => $pengujianData['keterangan'] ?? null,
            ]);
        });

        return Inertia::render('pegawai/hasil_uji/Detail', [
            'hasil_uji' => $hasil_uji,
            'parameter_pengujian' => $parameterPengujian,
            'parameter' => $availableParameters,
        ]);
    }

    // hapus hasil uji
    public function destroy($id)
    {
        $hasil_uji = HasilUji::findOrFail($id);

        $hasil_uji->delete();

        if ($hasil_uji) {
            return Redirect::route('pegawai.hasil_uji.index')->with('message', 'Hasil Uji Berhasil Dihapus!');
        }
    }
}
