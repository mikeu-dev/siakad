<script lang="ts">
    import { Link, page } from '@inertiajs/svelte';
    import BookOpen from 'lucide-svelte/icons/book-open';
    import BookUser from 'lucide-svelte/icons/book-user';
    import ClipboardList from 'lucide-svelte/icons/clipboard-list';
    import DollarSign from 'lucide-svelte/icons/dollar-sign';
    import GraduationCap from 'lucide-svelte/icons/graduation-cap';
    import LayoutGrid from 'lucide-svelte/icons/layout-grid';
    import ScrollText from 'lucide-svelte/icons/scroll-text';
    import ShieldCheck from 'lucide-svelte/icons/shield-check';
    import Users from 'lucide-svelte/icons/users';
    import type { Snippet } from 'svelte';
    import AppLogo from '@/components/AppLogo.svelte';
    import NavFooter from '@/components/NavFooter.svelte';
    import NavMain from '@/components/NavMain.svelte';
    import NavUser from '@/components/NavUser.svelte';
    import {
        Sidebar,
        SidebarContent,
        SidebarFooter,
        SidebarHeader,
        SidebarMenu,
        SidebarMenuButton,
        SidebarMenuItem,
    } from '@/components/ui/sidebar';
    import { toUrl } from '@/lib/utils';
    import { dashboard } from '@/routes';
    import type { NavItem } from '@/types';

    let { children }: { children?: Snippet } = $props();

    const role = $derived(page.props.auth.role);

    const adminNavItems: NavItem[] = [
        { title: 'Dashboard', href: '/dashboard', icon: LayoutGrid },
        { title: 'Mahasiswa', href: '/admin/mahasiswa', icon: GraduationCap },
        { title: 'Dosen', href: '/admin/dosen', icon: BookUser },
        { title: 'Mata Kuliah', href: '/admin/mata-kuliah', icon: BookOpen },
        { title: 'Keuangan', href: '/admin/keuangan', icon: DollarSign },
        { title: 'Users', href: '/admin/users', icon: Users },
        { title: 'Audit Log', href: '/admin/audit-log', icon: ShieldCheck },
    ];

    const dosenNavItems: NavItem[] = [
        { title: 'Dashboard', href: '/dashboard', icon: LayoutGrid },
        { title: 'Input Nilai', href: '/dosen/nilai', icon: ClipboardList },
    ];

    const mahasiswaNavItems: NavItem[] = [
        { title: 'Dashboard', href: '/dashboard', icon: LayoutGrid },
        { title: 'KRS', href: '/mahasiswa/krs', icon: BookOpen },
        { title: 'Nilai', href: '/mahasiswa/nilai', icon: ClipboardList },
        { title: 'Keuangan', href: '/mahasiswa/keuangan', icon: DollarSign },
    ];

    const footerNavItems: NavItem[] = [
        {
            title: 'Dokumentasi Laravel',
            href: 'https://laravel.com/docs',
            icon: ScrollText,
        },
    ];

    const mainNavItems = $derived(
        role === 'administrator'
            ? adminNavItems
            : role === 'dosen'
              ? dosenNavItems
              : role === 'mahasiswa'
                ? mahasiswaNavItems
                : [{ title: 'Dashboard', href: dashboard(), icon: LayoutGrid }],
    );
</script>

<Sidebar collapsible="icon" variant="inset">
    <SidebarHeader>
        <SidebarMenu>
            <SidebarMenuItem>
                <SidebarMenuButton size="lg" asChild>
                    {#snippet children(props)}
                        <Link
                            {...props}
                            href={toUrl(dashboard())}
                            class={props.class}
                        >
                            <AppLogo />
                        </Link>
                    {/snippet}
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarHeader>

    <SidebarContent>
        <NavMain items={mainNavItems} />
    </SidebarContent>

    <SidebarFooter>
        <NavFooter items={footerNavItems} />
        <NavUser />
    </SidebarFooter>
</Sidebar>
{@render children?.()}
