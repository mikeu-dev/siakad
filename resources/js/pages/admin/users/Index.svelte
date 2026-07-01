<script module lang="ts">
    export const layout = {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Users', href: '/admin/users' },
        ],
    };
</script>

<script lang="ts">
    import { Link, router, page } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';
    import Heading from '@/components/Heading.svelte';
    import { Button } from '@/components/ui/button';
    import { Badge } from '@/components/ui/badge';

    interface Role { id: number; nama_role: string; }
    interface UserItem { id: number; name: string; email: string; role: Role | null; }
    interface Paginated { data: UserItem[]; prev_page_url: string | null; next_page_url: string | null; }

    let { users }: { users: Paginated; roles: Role[] } = $props();
    const flash = $derived(page.props.flash as { success?: string; error?: string });

    function hapus(id: number) {
        if (confirm('Hapus user ini?')) router.delete(`/admin/users/${id}`);
    }
</script>

<AppHead title="Manajemen User" />

<div class="flex flex-col gap-6 p-4">
    <div class="flex items-center justify-between">
        <Heading title="Users" description="Kelola akun pengguna sistem" />
        <Link href="/admin/users/create"><Button>Tambah User</Button></Link>
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
                    <th class="px-4 py-3 text-left font-medium">Nama</th>
                    <th class="px-4 py-3 text-left font-medium">Email</th>
                    <th class="px-4 py-3 text-left font-medium">Role</th>
                    <th class="px-4 py-3 text-left font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                {#each users.data as u}
                    <tr class="border-b last:border-0 hover:bg-muted/30">
                        <td class="px-4 py-3">{u.name}</td>
                        <td class="px-4 py-3">{u.email}</td>
                        <td class="px-4 py-3">
                            <Badge variant="outline">{u.role?.nama_role ?? '-'}</Badge>
                        </td>
                        <td class="flex gap-2 px-4 py-3">
                            <Link href={`/admin/users/${u.id}/edit`}><Button variant="outline" size="sm">Edit</Button></Link>
                            <Button variant="destructive" size="sm" onclick={() => hapus(u.id)}>Hapus</Button>
                        </td>
                    </tr>
                {/each}
                {#if users.data.length === 0}
                    <tr><td colspan="4" class="px-4 py-6 text-center text-muted-foreground">Belum ada user.</td></tr>
                {/if}
            </tbody>
        </table>
    </div>

    <div class="flex justify-end gap-2">
        {#if users.prev_page_url}<Link href={users.prev_page_url}><Button variant="outline">Sebelumnya</Button></Link>{/if}
        {#if users.next_page_url}<Link href={users.next_page_url}><Button variant="outline">Berikutnya</Button></Link>{/if}
    </div>
</div>