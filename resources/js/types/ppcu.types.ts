export interface Instansi {
    id?: number;
    nama_instansi?: string;
    alamat?: string;
    no_telp?: string;
}

export interface Kategori {
    id?: number;
    nama_kategori?: string;
}

export interface FormPengajuan {
    id?: number;
    kode_pengajuan?: string;
    lokasi?: string;
    volume_sampel?: number;
    metode_pengambilan?: string;
    status_pengajuan?: string;
    instansi?: Instansi;
    kategori?: Kategori;
}

export interface PPCUTask {
    id: number;
    kode_pengambilan: string;
    id_form_pengajuan?: number;
    id_user?: number;
    waktu_pengambilan: string;
    status: 'diproses' | 'diterima' | 'dibatalkan' | string;
    keterangan?: string | null;
    form_pengajuan?: FormPengajuan;
    user?: {
        id?: number;
        name?: string;
    };
}

export interface PPCUStatistik {
    tugasPengambilan: number;
    selesai: number;
}
