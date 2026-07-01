<script module lang="ts">
    export const layout = {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Keuangan', href: '/admin/keuangan' },
        ],
    };
</script>

<script lang="ts">
    import { Link, router, page } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';
    import Heading from '@/components/Heading.svelte';
    import { Button } from '@/components/ui/button';
    import { Badge } from '@/components/ui/badge';

    interface Tagihan {
        id: number;
        keterangan: string;
        nominal: number;
        status: 'belum_bayar' | 'sudah_bayar';
        mahasiswa: { nim: string; nama: string };
    }
    interface Paginated { data: Tagihan[]; prev_page_url: string | null; next_page_url: string | null; }

    let { tagihan }: { tagihan: Paginated } = $props();
    const flash = $derived(page.props.flash as { success?: string; error?: string });

    function hapus(id: number) {
        if (confirm('Hapus tagihan ini?')) router.delete(`/admin/keuangan/${id}`);
    }

    function formatRupiah(n: number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(n);
    }
</script>

<AppHead title="Keuangan" />

<div class="flex flex-col gap-6 p-4">
    <div class="flex items-center justify-between">
        <Heading title="Keuangan" description="Tagihan pembayaran mahasiswa" />
        <Link href="/admin/keuangan/create"><Button>Buat Tagihan</Button></Link>
    </div>

    {#if flash?.success}
        <div class="rounded-md bg-green-50 p-3 text-sm text-green-700">{flash.success}</div>
    {/if}

    <div class="overflow-x-auto rounded-xl border">
        <table class="w-full text-sm">
            <thead class="border-b bg-muted/50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">Mahasiswa</th>
                    <th class="px-4 py-3 text-left font-medium">Keterangan</th>
                    <th class="px-4 py-3 text-left font-medium">Nominal</th>
                    <th class="px-4 py-3 text-left font-medium">Status</th>
                    <th class="px-4 py-3 text-left font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                {#each tagihan.data as t}
                    <tr class="border-b last:border-0 hover:bg-muted/30">
                        <td class="px-4 py-3">{t.mahasiswa.nim} — {t.mahasiswa.nama}</td>
                        <td class="px-4 py-3">{t.keterangan}</td>
                        <td class="px-4 py-3">{formatRupiah(t.nominal)}</td>
                        <td class="px-4 py-3">
                            <Badge variant={t.status === 'sudah_bayar' ? 'default' : 'destructive'}>
                                {t.status === 'sudah_bayar' ? 'Lunas' : 'Belum Bayar'}
                            </Badge>
                        </td>
                        <td class="flex gap-2 px-4 py-3">
                            <Link href={`/admin/keuangan/${t.id}/edit`}><Button variant="outline" size="sm">Edit</Button></Link>
                            <Button variant="destructive" size="sm" onclick={() => hapus(t.id)}>Hapus</Button>
                        </td>
                    </tr>
                {/each}
                {#if tagihan.data.length === 0}
                    <tr><td colspan="5" class="px-4 py-6 text-center text-muted-foreground">Belum ada tagihan.</td></tr>
                {/if}
            </tbody>
        </table>
    </div>

    <div class="flex justify-end gap-2">
        {#if tagihan.prev_page_url}<Link href={tagihan.prev_page_url}><Button variant="outline">Sebelumnya</Button></Link>{/if}
        {#if tagihan.next_page_url}<Link href={tagihan.next_page_url}><Button variant="outline">Berikutnya</Button></Link>{/if}
    </div>
</div>