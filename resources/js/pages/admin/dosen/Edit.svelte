<script module lang="ts">
    export const layout = {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Dosen', href: '/admin/dosen' },
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

    interface Dosen { id: number; nidn: string; nama: string; email: string; }
    let { dosen }: { dosen: Dosen } = $props();
</script>

<AppHead title="Edit Dosen" />

<div class="flex flex-col gap-6 p-4">
    <Heading title="Edit Dosen" description="Perbarui data dosen" />

    <Form method="put" action={`/admin/dosen/${dosen.id}`} class="max-w-lg space-y-4">
        {#snippet children({ errors, processing })}
            <div class="grid gap-1">
                <Label for="nidn">NIDN</Label>
                <Input id="nidn" name="nidn" value={dosen.nidn} required />
                <InputError message={errors.nidn} />
            </div>
            <div class="grid gap-1">
                <Label for="nama">Nama Lengkap</Label>
                <Input id="nama" name="nama" value={dosen.nama} required />
                <InputError message={errors.nama} />
            </div>
            <div class="grid gap-1">
                <Label for="email">Email</Label>
                <Input id="email" type="email" name="email" value={dosen.email} required />
                <InputError message={errors.email} />
            </div>
            <div class="flex gap-2">
                <Button type="submit" disabled={processing}>Perbarui</Button>
                <a href="/admin/dosen"><Button type="button" variant="outline">Batal</Button></a>
            </div>
        {/snippet}
    </Form>
</div>