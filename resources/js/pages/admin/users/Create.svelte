<script module lang="ts">
    export const layout = {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Users', href: '/admin/users' },
            { title: 'Tambah', href: '/admin/users/create' },
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

    interface Role { id: number; nama_role: string; }
    let { roles }: { roles: Role[] } = $props();
</script>

<AppHead title="Tambah User" />

<div class="flex flex-col gap-6 p-4">
    <Heading title="Tambah User" description="Buat akun pengguna baru" />

    <Form method="post" action="/admin/users" class="max-w-lg space-y-4">
        {#snippet children({ errors, processing })}
            <div class="grid gap-1">
                <Label for="name">Nama</Label>
                <Input id="name" name="name" placeholder="Nama lengkap" required />
                <InputError message={errors.name} />
            </div>
            <div class="grid gap-1">
                <Label for="email">Email</Label>
                <Input id="email" type="email" name="email" placeholder="email@contoh.com" required />
                <InputError message={errors.email} />
            </div>
            <div class="grid gap-1">
                <Label for="role_id">Role</Label>
                <select id="role_id" name="role_id"
                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm"
                    required>
                    <option value="">Pilih role...</option>
                    {#each roles as r}
                        <option value={r.id}>{r.nama_role}</option>
                    {/each}
                </select>
                <InputError message={errors.role_id} />
            </div>
            <div class="grid gap-1">
                <Label for="password">Password</Label>
                <Input id="password" type="password" name="password" placeholder="Min. 8 karakter" required />
                <InputError message={errors.password} />
            </div>
            <div class="grid gap-1">
                <Label for="password_confirmation">Konfirmasi Password</Label>
                <Input id="password_confirmation" type="password" name="password_confirmation" placeholder="Ulangi password" required />
                <InputError message={errors.password_confirmation} />
            </div>
            <div class="flex gap-2">
                <Button type="submit" disabled={processing}>Simpan</Button>
                <a href="/admin/users"><Button type="button" variant="outline">Batal</Button></a>
            </div>
        {/snippet}
    </Form>
</div>