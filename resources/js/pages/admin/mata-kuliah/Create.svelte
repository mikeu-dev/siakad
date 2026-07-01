<script module lang="ts">
    export const layout = {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Mata Kuliah', href: '/admin/mata-kuliah' },
            { title: 'Tambah', href: '/admin/mata-kuliah/create' },
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

<AppHead title="Tambah Mata Kuliah" />

<div class="flex flex-col gap-6 p-4">
    <Heading title="Tambah Mata Kuliah" description="Tambah mata kuliah baru ke kurikulum" />

    <Form method="post" action="/admin/mata-kuliah" class="max-w-lg space-y-4">
        {#snippet children({ errors, processing })}
            <div class="grid gap-1">
                <Label for="kode">Kode</Label>
                <Input id="kode" name="kode" placeholder="MK001" required />
                <InputError message={errors.kode} />
            </div>
            <div class="grid gap-1">
                <Label for="nama">Nama Mata Kuliah</Label>
                <Input id="nama" name="nama" placeholder="Pemrograman Web" required />
                <InputError message={errors.nama} />
            </div>
            <div class="grid gap-1">
                <Label for="sks">SKS</Label>
                <Input id="sks" type="number" name="sks" placeholder="3" min="1" max="6" required />
                <InputError message={errors.sks} />
            </div>
            <div class="grid gap-1">
                <Label for="semester">Semester</Label>
                <Input id="semester" type="number" name="semester" placeholder="3" min="1" max="14" required />
                <InputError message={errors.semester} />
            </div>
            <div class="flex gap-2">
                <Button type="submit" disabled={processing}>Simpan</Button>
                <a href="/admin/mata-kuliah"><Button type="button" variant="outline">Batal</Button></a>
            </div>
        {/snippet}
    </Form>
</div>