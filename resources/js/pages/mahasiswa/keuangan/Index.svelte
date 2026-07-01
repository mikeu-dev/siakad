<script module lang="ts">
    export const layout = {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Keuangan', href: '/mahasiswa/keuangan' },
        ],
    };
</script>

<script lang="ts">
    import AppHead from '@/components/AppHead.svelte';
    import Heading from '@/components/Heading.svelte';
    import { Badge } from '@/components/ui/badge';

    interface Tagihan {
        id: number;
        keterangan: string;
        nominal: number;
        status: 'belum_bayar' | 'sudah_bayar';
        created_at: string;
    }

    let { tagihan }: { tagihan: Tagihan[] } = $props();

    function formatRupiah(n: number): string {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(n);
    }

    const totalBelumBayar = $derived(
        tagihan
            .filter((t) => t.status === 'belum_bayar')
            .reduce((sum, t) => sum + t.nominal, 0)
    );
</script>

<AppHead title="Keuangan" />

<div class="flex flex-col gap-6 p-4">
    <Heading title="Keuangan" description="Riwayat tagihan dan pembayaran" />

    {#if totalBelumBayar > 0}
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            Kamu memiliki tagihan belum lunas sebesar <strong>{formatRupiah(totalBelumBayar)}</strong>. Segera lakukan pembayaran.
        </div>
    {:else}
        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            Semua tagihan telah lunas.
        </div>
    {/if}

    <div class="overflow-x-auto rounded-xl border">
        <table class="w-full text-sm">
            <thead class="border-b bg-muted/50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">Tanggal</th>
                    <th class="px-4 py-3 text-left font-medium">Keterangan</th>
                    <th class="px-4 py-3 text-left font-medium">Nominal</th>
                    <th class="px-4 py-3 text-left font-medium">Status</th>
                </tr>
            </thead>
            <tbody>
                {#each tagihan as t}
                    <tr class="border-b last:border-0 hover:bg-muted/30">
                        <td class="px-4 py-3 text-xs text-muted-foreground">{new Date(t.created_at).toLocaleDateString('id-ID')}</td>
                        <td class="px-4 py-3">{t.keterangan}</td>
                        <td class="px-4 py-3 font-medium">{formatRupiah(t.nominal)}</td>
                        <td class="px-4 py-3">
                            <Badge variant={t.status === 'sudah_bayar' ? 'default' : 'destructive'}>
                                {t.status === 'sudah_bayar' ? 'Lunas' : 'Belum Bayar'}
                            </Badge>
                        </td>
                    </tr>
                {/each}
                {#if tagihan.length === 0}
                    <tr><td colspan="4" class="px-4 py-6 text-center text-muted-foreground">Belum ada tagihan.</td></tr>
                {/if}
            </tbody>
        </table>
    </div>
</div>