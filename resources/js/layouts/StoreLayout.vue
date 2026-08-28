<script setup lang="ts">
import PullDownMenu from '@/components/store/PullDownMenu.vue';
import StoreFooter from '@/components/store/StoreFooter.vue';
import StoreHeader from '@/components/store/StoreHeader.vue';
import { Toaster } from '@/components/ui/sonner';
</script>

<template>
    <div class="isolate">
        <!--
          The shell is what PullDownMenu marks inert while the panel is down,
          so the menu itself is mounted as its sibling rather than inside it.
        -->
        <div data-store-shell class="flex min-h-dvh flex-col bg-cream-100">
            <StoreHeader />

            <main class="flex-1">
                <slot />
            </main>

            <StoreFooter />
        </div>

        <PullDownMenu />

        <!--
          Without this the storefront silently swallowed every
          Inertia::flash('toast') — the Toaster was only mounted in the
          authenticated layouts.
        -->
        <Toaster position="bottom-right" :duration="3500" close-button />
    </div>
</template>
