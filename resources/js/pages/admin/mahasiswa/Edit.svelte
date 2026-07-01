<script module lang="ts">
    export const layout = {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Mahasiswa', href: '/admin/mahasiswa' },
            { title: 'Edit', href: '#' },
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

    interface Mahasiswa {
        id: number; nim: string; nama: string; email: string;
        program_studi: string; angkatan: number;
    }
    let { mahasiswa }: { mahasiswa: Mahasiswa } = $props();
</script>

<AppHead title="Edit Mahasiswa" />

<div class="flex flex-col gap-6 p-4">
    <Heading title="Edit Mahasiswa" description="Perbarui data mahasiswa" />

    <Form method="put" action={`/admin/mahasiswa/${mahasiswa.id}`} class="max-w-lg space-y-4">
        {#snippet children({ errors, processing })}
            <div class="grid gap-1">
                <Label for="nim">NIM</Label>
                <Input id="nim" name="nim" value={mahasiswa.nim} required />
                <InputError message={errors.nim} />
            </div>
            <div class="grid gap-1">
                <Label for="nama">Nama Lengkap</Label>
                <Input id="nama" name="nama" value={mahasiswa.nama} required />
                <InputError message={errors.nama} />
            </div>
            <div class="grid gap-1">
                <Label for="email">Email</Label>
                <Input id="email" type="email" name="email" value={mahasiswa.email} required />
                <InputError message={errors.email} />
            </div>
            <div class="grid gap-1">
                <Label for="program_studi">Program Studi</Label>
                <Input id="program_studi" name="program_studi" value={mahasiswa.program_studi} required />
                <InputError message={errors.program_studi} />
            </div>
            <div class="grid gap-1">
                <Label for="angkatan">Angkatan</Label>
                <Input id="angkatan" type="number" name="angkatan" value={mahasiswa.angkatan} min="2000" max="2099" required />
                <InputError message={errors.angkatan} />
            </div>
            <div class="flex gap-2">
                <Button type="submit" disabled={processing}>Perbarui</Button>
                <a href="/admin/mahasiswa"><Button type="button" variant="outline">Batal</Button></a>
            </div>
        {/snippet}
    </Form>
</div>