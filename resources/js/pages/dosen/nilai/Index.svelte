<script module lang="ts">
    export const layout = {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Input Nilai', href: '/dosen/nilai' },
        ],
    };
</script>

<script lang="ts">
    import { Form, router, page } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';
    import Heading from '@/components/Heading.svelte';
    import InputError from '@/components/InputError.svelte';
    import { Button } from '@/components/ui/button';
    import { Input } from '@/components/ui/input';
    import { Label } from '@/components/ui/label';

    interface MhsOption { id: number; nim: string; nama: string; }
    interface MkOption { id: number; kode: string; nama: string; }
    interface NilaiItem {
        id: number;
        nilai: number;
        finalisasi: boolean;
        mahasiswa: { nim: string; nama: string };
        mata_kuliah: { kode: string; nama: string };
    }
    interface Paginated { data: NilaiItem[]; prev_page_url: string | null; next_page_url: string | null; }

    let { nilai, mahasiswaList, mataKuliahList }: {
        nilai: Paginated;
        mahasiswaList: MhsOption[];
        mataKuliahList: MkOption[];
        dosenId: number;
    } = $props();

    const flash = $derived(page.props.flash as { success?: string; error?: string });

    function finalisasi(id: number) {
        if (confirm('Finalisasi nilai ini? Nilai tidak dapat diubah setelah difinalisasi.')) {
            router.patch(`/dosen/nilai/${id}/finalisasi`);
        }
    }
</script>

<AppHead title="Input Nilai" />

<div class="flex flex-col gap-6 p-4">
    <Heading title="Input Nilai" description="Masukkan atau perbarui nilai mahasiswa" />

    {#if flash?.success}
        <div class="rounded-md bg-green-50 p-3 text-sm text-green-700">{flash.success}</div>
    {/if}
    {#if flash?.error}
        <div class="rounded-md bg-red-50 p-3 text-sm text-red-700">{flash.error}</div>
    {/if}

    <div class="rounded-xl border p-4">
        <h2 class="mb-4 text-sm font-semibold">Form Input Nilai</h2>
        <Form method="post" action="/dosen/nilai" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            {#snippet children({ errors, processing })}
                <div class="grid gap-1">
                    <Label for="mahasiswa_id">Mahasiswa</Label>
                    <select id="mahasiswa_id" name="mahasiswa_id"
                        class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm"
                        required>
                        <option value="">Pilih mahasiswa...</option>
                        {#each mahasiswaList as m}
                            <option value={m.id}>{m.nim} — {m.nama}</option>
                        {/each}
                    </select>
                    <InputError message={errors.mahasiswa_id} />
                </div>
                <div class="grid gap-1">
                    <Label for="mata_kuliah_id">Mata Kuliah</Label>
                    <select id="mata_kuliah_id" name="mata_kuliah_id"
                        class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm"
                        required>
                        <option value="">Pilih mata kuliah...</option>
                        {#each mataKuliahList as mk}
                            <option value={mk.id}>{mk.kode} — {mk.nama}</option>
                        {/each}
                    </select>
                    <InputError message={errors.mata_kuliah_id} />
                </div>
                <div class="grid gap-1">
                    <Label for="nilai">Nilai (0–100)</Label>
                    <Input id="nilai" type="number" name="nilai" placeholder="85" min="0" max="100" step="0.01" required />
                    <InputError message={errors.nilai} />
                </div>
                <div class="sm:col-span-3">
                    <Button type="submit" disabled={processing}>Simpan Nilai</Button>
                </div>
            {/snippet}
        </Form>
    </div>

    <div class="overflow-x-auto rounded-xl border">
        <table class="w-full text-sm">
            <thead class="border-b bg-muted/50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">Mahasiswa</th>
                    <th class="px-4 py-3 text-left font-medium">Mata Kuliah</th>
                    <th class="px-4 py-3 text-left font-medium">Nilai</th>
                    <th class="px-4 py-3 text-left font-medium">Status</th>
                    <th class="px-4 py-3 text-left font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                {#each nilai.data as n}
                    <tr class="border-b last:border-0 hover:bg-muted/30">
                        <td class="px-4 py-3">{n.mahasiswa.nim} — {n.mahasiswa.nama}</td>
                        <td class="px-4 py-3">{n.mata_kuliah.kode} — {n.mata_kuliah.nama}</td>
                        <td class="px-4 py-3 font-semibold">{n.nilai}</td>
                        <td class="px-4 py-3">
                            {#if n.finalisasi}
                                <span class="inline-flex rounded bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Finalisasi</span>
                            {:else}
                                <span class="inline-flex rounded bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-700">Draft</span>
                            {/if}
                        </td>
                        <td class="px-4 py-3">
                            {#if !n.finalisasi}
                                <Button size="sm" onclick={() => finalisasi(n.id)}>Finalisasi</Button>
                            {:else}
                                <span class="text-xs text-muted-foreground">—</span>
                            {/if}
                        </td>
                    </tr>
                {/each}
                {#if nilai.data.length === 0}
                    <tr><td colspan="5" class="px-4 py-6 text-center text-muted-foreground">Belum ada data nilai.</td></tr>
                {/if}
            </tbody>
        </table>
    </div>

    <div class="flex justify-end gap-2">
        {#if nilai.prev_page_url}<a href={nilai.prev_page_url}><Button variant="outline">Sebelumnya</Button></a>{/if}
        {#if nilai.next_page_url}<a href={nilai.next_page_url}><Button variant="outline">Berikutnya</Button></a>{/if}
    </div>
</div>