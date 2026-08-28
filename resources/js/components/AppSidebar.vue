<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { LayoutGrid, Package, ShoppingBag, Store } from '@lucide/vue';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard, home } from '@/routes';
import { index as cartIndex } from '@/routes/cart';
import { index as productsIndex } from '@/routes/products';
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    {
        title: 'Профил',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Продукти',
        href: productsIndex(),
        icon: Package,
    },
    {
        title: 'Количка',
        href: cartIndex(),
        icon: ShoppingBag,
    },
];

// The starter kit pointed these at its own repository and the Laravel docs.
const footerNavItems: NavItem[] = [
    {
        title: 'Към магазина',
        href: home(),
        icon: Store,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
