<script setup lang="ts">
/**
 * Container for a section's decorative shapes. Purely presentational, so it is
 * hidden from assistive technology and never intercepts pointer events.
 */
</script>

<template>
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 -z-10">
        <slot />
    </div>
</template>
