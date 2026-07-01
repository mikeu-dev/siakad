<script module lang="ts">
    export const layout = {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Dosen', href: '/admin/dosen' },
        ],
    };
</script>

<script lang="ts">
    import { Link, router, page } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';
    import Heading from '@/components/Heading.svelte';
    import { Button } from '@/components/ui/button';

    interface Dosen { id: number; nidn: string; nama: string; email: string; }
    interface Paginated { data: Dosen[]; prev_page_url: string | null; next_page_url: string | null; }

    let { dosen }: { dosen: Paginated } = $props();
    const flash = $derived(page.props.flash as { success?: string; error?: string });

    function hapus(id: number) {
        if (confirm('Hapus dosen ini?')) router.delete(`/admin/dosen/${id}`);
    }
</script>

<AppHead title="Manajemen Dosen" />

<div class="flex flex-col gap-6 p-4">
    <div class="flex items-center justify-between">
        <Heading title="Dosen" description="Daftar seluruh dosen" />
        <Link href="/admin/dosen/create"><Button>Tambah Dosen</Button></Link>
    </div>

    {#if flash?.success}
        <div class="rounded-md bg-green-50 p-3 text-sm text-green-700">{flash.success}</div>
    {/if}

    <div class="overflow-x-auto rounded-xl border">
        <table class="w-full text-sm">
            <thead class="border-b bg-muted/50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">NIDN</th>
                    <th class="px-4 py-3 text-left font-medium">Nama</th>
                    <th class="px-4 py-3 text-left font-medium">Email</th>
                    <th class="px-4 py-3 text-left font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                {#each dosen.data as d}
                    <tr class="border-b last:border-0 hover:bg-muted/30">
                        <td class="px-4 py-3 font-mono">{d.nidn}</td>
                        <td class="px-4 py-3">{d.nama}</td>
                        <td class="px-4 py-3">{d.email}</td>
                        <td class="flex gap-2 px-4 py-3">
                            <Link href={`/admin/dosen/${d.id}/edit`}><Button variant="outline" size="sm">Edit</Button></Link>
                            <Button variant="destructive" size="sm" onclick={() => hapus(d.id)}>Hapus</Button>
                        </td>
                    </tr>
                {/each}
                {#if dosen.data.length === 0}
                    <tr><td colspan="4" class="px-4 py-6 text-center text-muted-foreground">Belum ada data dosen.</td></tr>
                {/if}
            </tbody>
        </table>
    </div>

    <div class="flex justify-end gap-2">
        {#if dosen.prev_page_url}<Link href={dosen.prev_page_url}><Button variant="outline">Sebelumnya</Button></Link>{/if}
        {#if dosen.next_page_url}<Link href={dosen.next_page_url}><Button variant="outline">Berikutnya</Button></Link>{/if}
    </div>
</div>