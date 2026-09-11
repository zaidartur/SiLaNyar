export interface StatMetric {
    label: string;
    value: number | string;
    description?: string;
    icon: string;
    color?: string;
    trend?: {
        value: string;
        positive?: boolean;
    };
}

export interface WorkflowStage {
    key: string;
    title: string;
    description: string;
    count?: number;
    active?: boolean;
    completed?: boolean;
}

export interface RecentPermohonan {
    id: number;
    kode: string;
    instansi: string;
    tanggal: string;
    status: string;
    totalBiaya?: number;
}
