<script module lang="ts">
    export const layout = {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'KRS', href: '/mahasiswa/krs' },
        ],
    };
</script>

<script lang="ts">
    import { Form, router, page } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';
    import Heading from '@/components/Heading.svelte';
    import InputError from '@/components/InputError.svelte';
    import { Button } from '@/components/ui/button';
    import { Label } from '@/components/ui/label';

    interface MkItem { id: number; kode: string; nama: string; sks: number; }
    interface KrsItem { id: number; mata_kuliah: MkItem; }

    let { krs, totalSks, maxSks, available }: {
        krs: KrsItem[];
        totalSks: number;
        maxSks: number;
        available: MkItem[];
    } = $props();

    const flash = $derived(page.props.flash as { success?: string; error?: string });

    function batalkan(id: number) {
        if (confirm('Batalkan pengambilan mata kuliah ini?')) {
            router.delete(`/mahasiswa/krs/${id}`);
        }
    }
</script>

<AppHead title="Kartu Rencana Studi" />

<div class="flex flex-col gap-6 p-4">
    <Heading title="Kartu Rencana Studi (KRS)" description="Kelola mata kuliah yang kamu ambil semester ini" />

    {#if flash?.success}
        <div class="rounded-md bg-green-50 p-3 text-sm text-green-700">{flash.success}</div>
    {/if}
    {#if flash?.error}
        <div class="rounded-md bg-red-50 p-3 text-sm text-red-700">{flash.error}</div>
    {/if}

    <div class="flex items-center gap-3 rounded-xl border bg-muted/30 px-4 py-3">
        <span class="text-sm font-medium">Total SKS:</span>
        <span class="text-lg font-bold {totalSks > maxSks ? 'text-red-600' : 'text-green-600'}">{totalSks}</span>
        <span class="text-sm text-muted-foreground">/ {maxSks} SKS maksimum</span>
    </div>

    <div class="rounded-xl border p-4">
        <h2 class="mb-4 text-sm font-semibold">Tambah Mata Kuliah</h2>
        <Form method="post" action="/mahasiswa/krs" class="flex flex-wrap items-end gap-4">
            {#snippet children({ errors, processing })}
                <div class="grid min-w-64 gap-1">
                    <Label for="mata_kuliah_id">Mata Kuliah</Label>
                    <select id="mata_kuliah_id" name="mata_kuliah_id"
                        class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm"
                        required>
                        <option value="">Pilih mata kuliah...</option>
                        {#each available as mk}
                            <option value={mk.id}>{mk.kode} — {mk.nama} ({mk.sks} SKS)</option>
                        {/each}
                    </select>
                    <InputError message={errors.mata_kuliah_id} />
                </div>
                <Button type="submit" disabled={processing}>Ambil</Button>
            {/snippet}
        </Form>
    </div>

    <div class="overflow-x-auto rounded-xl border">
        <table class="w-full text-sm">
            <thead class="border-b bg-muted/50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">Kode</th>
                    <th class="px-4 py-3 text-left font-medium">Nama Mata Kuliah</th>
                    <th class="px-4 py-3 text-left font-medium">SKS</th>
                    <th class="px-4 py-3 text-left font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                {#each krs as k}
                    <tr class="border-b last:border-0 hover:bg-muted/30">
                        <td class="px-4 py-3 font-mono">{k.mata_kuliah.kode}</td>
                        <td class="px-4 py-3">{k.mata_kuliah.nama}</td>
                        <td class="px-4 py-3">{k.mata_kuliah.sks}</td>
                        <td class="px-4 py-3">
                            <Button variant="destructive" size="sm" onclick={() => batalkan(k.id)}>Batalkan</Button>
                        </td>
                    </tr>
                {/each}
                {#if krs.length === 0}
                    <tr><td colspan="4" class="px-4 py-6 text-center text-muted-foreground">Belum ada mata kuliah yang diambil.</td></tr>
                {/if}
            </tbody>
        </table>
    </div>
</div>