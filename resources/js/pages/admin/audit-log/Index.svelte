<script module lang="ts">
    export const layout = {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Audit Log', href: '/admin/audit-log' },
        ],
    };
</script>

<script lang="ts">
    import { Link } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';
    import Heading from '@/components/Heading.svelte';
    import { Button } from '@/components/ui/button';

    interface LogUser { name: string; email: string; }
    interface Log {
        id: number;
        aktivitas: string;
        tabel: string | null;
        record_id: number | null;
        ip_address: string | null;
        created_at: string;
        user: LogUser | null;
    }
    interface Paginated {
        data: Log[];
        prev_page_url: string | null;
        next_page_url: string | null;
        current_page: number;
        last_page: number;
    }

    let { logs }: { logs: Paginated } = $props();

    function badge(aktivitas: string): string {
        if (aktivitas === 'INSERT') return 'bg-green-100 text-green-700';
        if (aktivitas === 'UPDATE' || aktivitas === 'INSERT_OR_UPDATE') return 'bg-yellow-100 text-yellow-700';
        if (aktivitas === 'DELETE') return 'bg-red-100 text-red-700';
        return 'bg-blue-100 text-blue-700';
    }
</script>

<AppHead title="Audit Log" />

<div class="flex flex-col gap-6 p-4">
    <Heading title="Audit Log" description="Riwayat seluruh aktivitas sistem" />

    <div class="overflow-x-auto rounded-xl border">
        <table class="w-full text-sm">
            <thead class="border-b bg-muted/50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">Waktu</th>
                    <th class="px-4 py-3 text-left font-medium">User</th>
                    <th class="px-4 py-3 text-left font-medium">Aktivitas</th>
                    <th class="px-4 py-3 text-left font-medium">Tabel</th>
                    <th class="px-4 py-3 text-left font-medium">Record ID</th>
                    <th class="px-4 py-3 text-left font-medium">IP Address</th>
                </tr>
            </thead>
            <tbody>
                {#each logs.data as log}
                    <tr class="border-b last:border-0 hover:bg-muted/30">
                        <td class="px-4 py-3 text-xs text-muted-foreground">{new Date(log.created_at).toLocaleString('id-ID')}</td>
                        <td class="px-4 py-3">{log.user?.name ?? '(sistem)'}</td>
                        <td class="px-4 py-3">
                            <span class={`inline-flex rounded px-2 py-0.5 text-xs font-medium ${badge(log.aktivitas)}`}>
                                {log.aktivitas}
                            </span>
                        </td>
                        <td class="px-4 py-3 font-mono text-xs">{log.tabel ?? '-'}</td>
                        <td class="px-4 py-3">{log.record_id ?? '-'}</td>
                        <td class="px-4 py-3 text-xs">{log.ip_address ?? '-'}</td>
                    </tr>
                {/each}
                {#if logs.data.length === 0}
                    <tr><td colspan="6" class="px-4 py-6 text-center text-muted-foreground">Belum ada log aktivitas.</td></tr>
                {/if}
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-between">
        <span class="text-sm text-muted-foreground">Halaman {logs.current_page} dari {logs.last_page}</span>
        <div class="flex gap-2">
            {#if logs.prev_page_url}<Link href={logs.prev_page_url}><Button variant="outline">Sebelumnya</Button></Link>{/if}
            {#if logs.next_page_url}<Link href={logs.next_page_url}><Button variant="outline">Berikutnya</Button></Link>{/if}
        </div>
    </div>
</div>