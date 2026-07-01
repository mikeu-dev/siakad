<script module lang="ts">
    export const layout = {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Mahasiswa', href: '/admin/mahasiswa' },
        ],
    };
</script>

<script lang="ts">
    import { Link, router, page } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';
    import { Button } from '@/components/ui/button';
    import Heading from '@/components/Heading.svelte';

    interface Mahasiswa {
        id: number;
        nim: string;
        nama: string;
        email: string;
        program_studi: string;
        angkatan: number;
    }

    interface PaginatedMahasiswa {
        data: Mahasiswa[];
        current_page: number;
        last_page: number;
        prev_page_url: string | null;
        next_page_url: string | null;
    }

    let { mahasiswa }: { mahasiswa: PaginatedMahasiswa } = $props();

    const flash = $derived(page.props.flash as { success?: string; error?: string });

    function hapus(id: number) {
        if (confirm('Hapus mahasiswa ini?')) {
            router.delete(`/admin/mahasiswa/${id}`);
        }
    }
</script>

<AppHead title="Manajemen Mahasiswa" />

<div class="flex flex-col gap-6 p-4">
    <div class="flex items-center justify-between">
        <Heading title="Mahasiswa" description="Daftar seluruh mahasiswa terdaftar" />
        <Link href="/admin/mahasiswa/create">
            <Button>Tambah Mahasiswa</Button>
        </Link>
    </div>

    {#if flash?.success}
        <div class="rounded-md bg-green-50 p-3 text-sm text-green-700">{flash.success}</div>
    {/if}
    {#if flash?.error}
        <div class="rounded-md bg-red-50 p-3 text-sm text-red-700">{flash.error}</div>
    {/if}

    <div class="overflow-x-auto rounded-xl border">
        <table class="w-full text-sm">
            <thead class="border-b bg-muted/50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">NIM</th>
                    <th class="px-4 py-3 text-left font-medium">Nama</th>
                    <th class="px-4 py-3 text-left font-medium">Email</th>
                    <th class="px-4 py-3 text-left font-medium">Program Studi</th>
                    <th class="px-4 py-3 text-left font-medium">Angkatan</th>
                    <th class="px-4 py-3 text-left font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                {#each mahasiswa.data as mhs}
                    <tr class="border-b last:border-0 hover:bg-muted/30">
                        <td class="px-4 py-3 font-mono">{mhs.nim}</td>
                        <td class="px-4 py-3">{mhs.nama}</td>
                        <td class="px-4 py-3">{mhs.email}</td>
                        <td class="px-4 py-3">{mhs.program_studi}</td>
                        <td class="px-4 py-3">{mhs.angkatan}</td>
                        <td class="flex gap-2 px-4 py-3">
                            <Link href={`/admin/mahasiswa/${mhs.id}/edit`}>
                                <Button variant="outline" size="sm">Edit</Button>
                            </Link>
                            <Button variant="destructive" size="sm" onclick={() => hapus(mhs.id)}>Hapus</Button>
                        </td>
                    </tr>
                {/each}
                {#if mahasiswa.data.length === 0}
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-muted-foreground">Belum ada data mahasiswa.</td>
                    </tr>
                {/if}
            </tbody>
        </table>
    </div>

    <div class="flex justify-end gap-2">
        {#if mahasiswa.prev_page_url}
            <Link href={mahasiswa.prev_page_url}><Button variant="outline">Sebelumnya</Button></Link>
        {/if}
        {#if mahasiswa.next_page_url}
            <Link href={mahasiswa.next_page_url}><Button variant="outline">Berikutnya</Button></Link>
        {/if}
    </div>
</div>