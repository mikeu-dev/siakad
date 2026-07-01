<script module lang="ts">
    export const layout = {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Nilai', href: '/mahasiswa/nilai' },
        ],
    };
</script>

<script lang="ts">
    import AppHead from '@/components/AppHead.svelte';
    import Heading from '@/components/Heading.svelte';

    interface NilaiItem {
        id: number;
        kode_mk: string;
        nama_mk: string;
        sks: number;
        nilai: number;
        finalisasi: boolean;
        nama_dosen: string;
    }

    let { nilai }: { nilai: NilaiItem[] } = $props();

    function huruf(n: number): string {
        if (n >= 85) return 'A';
        if (n >= 75) return 'B+';
        if (n >= 70) return 'B';
        if (n >= 60) return 'C+';
        if (n >= 55) return 'C';
        if (n >= 45) return 'D';
        return 'E';
    }

    const totalSks = $derived(nilai.reduce((sum, n) => sum + n.sks, 0));
    const totalBobot = $derived(
        nilai.reduce((sum, n) => {
            const poin =
                n.nilai >= 85 ? 4 :
                n.nilai >= 75 ? 3.5 :
                n.nilai >= 70 ? 3 :
                n.nilai >= 60 ? 2.5 :
                n.nilai >= 55 ? 2 :
                n.nilai >= 45 ? 1 : 0;
            return sum + poin * n.sks;
        }, 0)
    );
    const ipk = $derived(totalSks > 0 ? (totalBobot / totalSks).toFixed(2) : '0.00');
</script>

<AppHead title="Nilai" />

<div class="flex flex-col gap-6 p-4">
    <Heading title="Nilai" description="Rekap nilai mata kuliah kamu" />

    <div class="flex gap-4">
        <div class="rounded-xl border bg-muted/30 px-5 py-4">
            <p class="text-xs text-muted-foreground">Total SKS</p>
            <p class="text-2xl font-bold">{totalSks}</p>
        </div>
        <div class="rounded-xl border bg-muted/30 px-5 py-4">
            <p class="text-xs text-muted-foreground">IPK</p>
            <p class="text-2xl font-bold">{ipk}</p>
        </div>
    </div>

    <div class="overflow-x-auto rounded-xl border">
        <table class="w-full text-sm">
            <thead class="border-b bg-muted/50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">Kode</th>
                    <th class="px-4 py-3 text-left font-medium">Mata Kuliah</th>
                    <th class="px-4 py-3 text-left font-medium">SKS</th>
                    <th class="px-4 py-3 text-left font-medium">Nilai</th>
                    <th class="px-4 py-3 text-left font-medium">Huruf</th>
                    <th class="px-4 py-3 text-left font-medium">Dosen</th>
                    <th class="px-4 py-3 text-left font-medium">Status</th>
                </tr>
            </thead>
            <tbody>
                {#each nilai as n}
                    <tr class="border-b last:border-0 hover:bg-muted/30">
                        <td class="px-4 py-3 font-mono">{n.kode_mk}</td>
                        <td class="px-4 py-3">{n.nama_mk}</td>
                        <td class="px-4 py-3">{n.sks}</td>
                        <td class="px-4 py-3 font-semibold">{n.nilai}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded bg-blue-100 px-2 py-0.5 text-xs font-bold text-blue-700">{huruf(n.nilai)}</span>
                        </td>
                        <td class="px-4 py-3">{n.nama_dosen}</td>
                        <td class="px-4 py-3">
                            {#if n.finalisasi}
                                <span class="inline-flex rounded bg-green-100 px-2 py-0.5 text-xs text-green-700">Final</span>
                            {:else}
                                <span class="inline-flex rounded bg-yellow-100 px-2 py-0.5 text-xs text-yellow-700">Draft</span>
                            {/if}
                        </td>
                    </tr>
                {/each}
                {#if nilai.length === 0}
                    <tr><td colspan="7" class="px-4 py-6 text-center text-muted-foreground">Belum ada data nilai.</td></tr>
                {/if}
            </tbody>
        </table>
    </div>
</div>