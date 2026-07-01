<script module lang="ts">
    export const layout = {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Mata Kuliah', href: '/admin/mata-kuliah' },
        ],
    };
</script>

<script lang="ts">
    import { Link, router, page } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';
    import Heading from '@/components/Heading.svelte';
    import { Button } from '@/components/ui/button';

    interface MataKuliah { id: number; kode: string; nama: string; sks: number; semester: number; }
    interface Paginated { data: MataKuliah[]; prev_page_url: string | null; next_page_url: string | null; }

    let { mataKuliah }: { mataKuliah: Paginated } = $props();
    const flash = $derived(page.props.flash as { success?: string; error?: string });

    function hapus(id: number) {
        if (confirm('Hapus mata kuliah ini?')) router.delete(`/admin/mata-kuliah/${id}`);
    }
</script>

<AppHead title="Mata Kuliah" />

<div class="flex flex-col gap-6 p-4">
    <div class="flex items-center justify-between">
        <Heading title="Mata Kuliah" description="Daftar mata kuliah yang tersedia" />
        <Link href="/admin/mata-kuliah/create"><Button>Tambah Mata Kuliah</Button></Link>
    </div>

    {#if flash?.success}
        <div class="rounded-md bg-green-50 p-3 text-sm text-green-700">{flash.success}</div>
    {/if}

    <div class="overflow-x-auto rounded-xl border">
        <table class="w-full text-sm">
            <thead class="border-b bg-muted/50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">Kode</th>
                    <th class="px-4 py-3 text-left font-medium">Nama</th>
                    <th class="px-4 py-3 text-left font-medium">SKS</th>
                    <th class="px-4 py-3 text-left font-medium">Semester</th>
                    <th class="px-4 py-3 text-left font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                {#each mataKuliah.data as mk}
                    <tr class="border-b last:border-0 hover:bg-muted/30">
                        <td class="px-4 py-3 font-mono">{mk.kode}</td>
                        <td class="px-4 py-3">{mk.nama}</td>
                        <td class="px-4 py-3">{mk.sks}</td>
                        <td class="px-4 py-3">{mk.semester}</td>
                        <td class="flex gap-2 px-4 py-3">
                            <Link href={`/admin/mata-kuliah/${mk.id}/edit`}><Button variant="outline" size="sm">Edit</Button></Link>
                            <Button variant="destructive" size="sm" onclick={() => hapus(mk.id)}>Hapus</Button>
                        </td>
                    </tr>
                {/each}
                {#if mataKuliah.data.length === 0}
                    <tr><td colspan="5" class="px-4 py-6 text-center text-muted-foreground">Belum ada mata kuliah.</td></tr>
                {/if}
            </tbody>
        </table>
    </div>

    <div class="flex justify-end gap-2">
        {#if mataKuliah.prev_page_url}<Link href={mataKuliah.prev_page_url}><Button variant="outline">Sebelumnya</Button></Link>{/if}
        {#if mataKuliah.next_page_url}<Link href={mataKuliah.next_page_url}><Button variant="outline">Berikutnya</Button></Link>{/if}
    </div>
</div>