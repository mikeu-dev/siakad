<script module lang="ts">
    export const layout = {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Users', href: '/admin/users' },
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

    interface Role { id: number; nama_role: string; }
    interface UserItem { id: number; name: string; email: string; role_id: number | null; }
    let { user, roles }: { user: UserItem; roles: Role[] } = $props();
</script>

<AppHead title="Edit User" />

<div class="flex flex-col gap-6 p-4">
    <Heading title="Edit User" description="Perbarui data pengguna" />

    <Form method="put" action={`/admin/users/${user.id}`} class="max-w-lg space-y-4">
        {#snippet children({ errors, processing })}
            <div class="grid gap-1">
                <Label for="name">Nama</Label>
                <Input id="name" name="name" value={user.name} required />
                <InputError message={errors.name} />
            </div>
            <div class="grid gap-1">
                <Label for="email">Email</Label>
                <Input id="email" type="email" name="email" value={user.email} required />
                <InputError message={errors.email} />
            </div>
            <div class="grid gap-1">
                <Label for="role_id">Role</Label>
                <select id="role_id" name="role_id"
                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm"
                    required>
                    {#each roles as r}
                        <option value={r.id} selected={r.id === user.role_id}>{r.nama_role}</option>
                    {/each}
                </select>
                <InputError message={errors.role_id} />
            </div>
            <div class="flex gap-2">
                <Button type="submit" disabled={processing}>Perbarui</Button>
                <a href="/admin/users"><Button type="button" variant="outline">Batal</Button></a>
            </div>
        {/snippet}
    </Form>
</div>