<script module lang="ts">
    export const layout = {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Keuangan', href: '/admin/keuangan' },
            { title: 'Buat Tagihan', href: '/admin/keuangan/create' },
        ],
    };
</script>

<script lang="ts">
    import { Form } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';
    import Heading from '@/components/Heading.svelte';
    import InputError from '@/components/InputError.svelte';
    import { Button } from '@/components/ui/button';
    import { Input } from '@/components/ui/input';
    import { Label } from '@/components/ui/label';

    interface MahasiswaOption { id: number; nim: string; nama: string; }
    let { mahasiswa }: { mahasiswa: MahasiswaOption[] } = $props();
</script>

<AppHead title="Buat Tagihan" />

<div class="flex flex-col gap-6 p-4">
    <Heading title="Buat Tagihan" description="Tambahkan tagihan pembayaran untuk mahasiswa" />

    <Form method="post" action="/admin/keuangan" class="max-w-lg space-y-4">
        {#snippet children({ errors, processing })}
            <div class="grid gap-1">
                <Label for="mahasiswa_id">Mahasiswa</Label>
                <select id="mahasiswa_id" name="mahasiswa_id"
                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm"
                    required>
                    <option value="">Pilih mahasiswa...</option>
                    {#each mahasiswa as m}
                        <option value={m.id}>{m.nim} — {m.nama}</option>
                    {/each}
                </select>
                <InputError message={errors.mahasiswa_id} />
            </div>
            <div class="grid gap-1">
                <Label for="keterangan">Keterangan</Label>
                <Input id="keterangan" name="keterangan" placeholder="SPP Semester Ganjil" required />
                <InputError message={errors.keterangan} />
            </div>
            <div class="grid gap-1">
                <Label for="nominal">Nominal (Rp)</Label>
                <Input id="nominal" type="number" name="nominal" placeholder="3000000" min="0" required />
                <InputError message={errors.nominal} />
            </div>
            <div class="grid gap-1">
                <Label for="status">Status</Label>
                <select id="status" name="status"
                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm"
                    required>
                    <option value="belum_bayar">Belum Bayar</option>
                    <option value="sudah_bayar">Sudah Bayar</option>
                </select>
                <InputError message={errors.status} />
            </div>
            <div class="flex gap-2">
                <Button type="submit" disabled={processing}>Simpan</Button>
                <a href="/admin/keuangan"><Button type="button" variant="outline">Batal</Button></a>
            </div>
        {/snippet}
    </Form>
</div>