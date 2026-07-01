<script module lang="ts">
    export const layout = {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Mahasiswa', href: '/admin/mahasiswa' },
            { title: 'Tambah', href: '/admin/mahasiswa/create' },
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
</script>

<AppHead title="Tambah Mahasiswa" />

<div class="flex flex-col gap-6 p-4">
    <Heading title="Tambah Mahasiswa" description="Buat akun mahasiswa baru" />

    <Form method="post" action="/admin/mahasiswa" class="max-w-lg space-y-4">
        {#snippet children({ errors, processing })}
            <div class="grid gap-1">
                <Label for="nim">NIM</Label>
                <Input id="nim" name="nim" placeholder="Nomor Induk Mahasiswa" required />
                <InputError message={errors.nim} />
            </div>
            <div class="grid gap-1">
                <Label for="nama">Nama Lengkap</Label>
                <Input id="nama" name="nama" placeholder="Nama lengkap" required />
                <InputError message={errors.nama} />
            </div>
            <div class="grid gap-1">
                <Label for="email">Email</Label>
                <Input id="email" type="email" name="email" placeholder="email@contoh.com" required />
                <InputError message={errors.email} />
            </div>
            <div class="grid gap-1">
                <Label for="program_studi">Program Studi</Label>
                <Input id="program_studi" name="program_studi" placeholder="Teknik Informatika" required />
                <InputError message={errors.program_studi} />
            </div>
            <div class="grid gap-1">
                <Label for="angkatan">Angkatan</Label>
                <Input id="angkatan" type="number" name="angkatan" placeholder="2024" min="2000" max="2099" required />
                <InputError message={errors.angkatan} />
            </div>
            <div class="grid gap-1">
                <Label for="password">Password</Label>
                <Input id="password" type="password" name="password" placeholder="Min. 8 karakter" required />
                <InputError message={errors.password} />
            </div>
            <div class="flex gap-2">
                <Button type="submit" disabled={processing}>Simpan</Button>
                <a href="/admin/mahasiswa"><Button type="button" variant="outline">Batal</Button></a>
            </div>
        {/snippet}
    </Form>
</div>