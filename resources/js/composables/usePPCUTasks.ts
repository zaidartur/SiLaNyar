import { ref, computed } from 'vue';
import type { PPCUTask } from '@/types/ppcu.types';

export function usePPCUTasks(initialTasks: PPCUTask[] = []) {
    const tasks = ref<PPCUTask[]>(initialTasks);
    const filterStatus = ref<'all' | 'diproses' | 'diterima'>('all');
    const searchQuery = ref('');
    const selectedTask = ref<PPCUTask | null>(null);
    const isStatusDialogOpen = ref(false);

    const filteredTasks = computed(() => {
        return tasks.value.filter((task) => {
            const matchStatus =
                filterStatus.value === 'all' ? true : task.status === filterStatus.value;

            if (!matchStatus) return false;

            if (!searchQuery.value.trim()) return true;

            const query = searchQuery.value.toLowerCase();
            const matchKode = task.kode_pengambilan.toLowerCase().includes(query);
            const matchLokasi = task.form_pengajuan?.lokasi?.toLowerCase().includes(query) ?? false;
            const matchInstansi =
                task.form_pengajuan?.instansi?.nama_instansi?.toLowerCase().includes(query) ?? false;

            return matchKode || matchLokasi || matchInstansi;
        });
    });

    const activeTasksCount = computed(() => {
        return tasks.value.filter((t) => t.status === 'diproses').length;
    });

    const completedTasksCount = computed(() => {
        return tasks.value.filter((t) => t.status === 'diterima').length;
    });

    function openGpsNavigation(lokasi?: string) {
        if (!lokasi) return;
        const encoded = encodeURIComponent(lokasi);
        const mapUrl = `https://www.google.com/maps/search/?api=1&query=${encoded}`;
        if (typeof window !== 'undefined') {
            window.open(mapUrl, '_blank', 'noopener,noreferrer');
        }
    }

    function openStatusModal(task: PPCUTask) {
        selectedTask.value = task;
        isStatusDialogOpen.value = true;
    }

    function closeStatusModal() {
        isStatusDialogOpen.value = false;
        selectedTask.value = null;
    }

    function formatDate(dateStr?: string): string {
        if (!dateStr) return '-';
        try {
            const date = new Date(dateStr);
            return new Intl.DateTimeFormat('id-ID', {
                weekday: 'short',
                day: 'numeric',
                month: 'short',
                year: 'numeric',
            }).format(date);
        } catch {
            return dateStr;
        }
    }

    function getStatusBadge(status: string) {
        switch (status) {
            case 'diterima':
                return {
                    label: 'Selesai di Lab',
                    color: 'success',
                    bgClass: 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800',
                };
            case 'diproses':
                return {
                    label: 'Perlu Diambil',
                    color: 'warning',
                    bgClass: 'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400 border border-amber-200 dark:border-amber-800',
                };
            case 'dibatalkan':
                return {
                    label: 'Dibatalkan',
                    color: 'error',
                    bgClass: 'bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400 border border-rose-200 dark:border-rose-800',
                };
            default:
                return {
                    label: status,
                    color: 'info',
                    bgClass: 'bg-slate-50 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700',
                };
        }
    }

    return {
        tasks,
        filterStatus,
        searchQuery,
        filteredTasks,
        activeTasksCount,
        completedTasksCount,
        selectedTask,
        isStatusDialogOpen,
        openGpsNavigation,
        openStatusModal,
        closeStatusModal,
        formatDate,
        getStatusBadge,
    };
}
