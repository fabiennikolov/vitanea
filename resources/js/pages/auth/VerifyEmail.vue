<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import AuthCard from '@/components/auth/AuthCard.vue';
import { Spinner } from '@/components/ui/spinner';
import { authLink, authQuietButton } from '@/lib/formClasses';
import { logout } from '@/routes';
import { send } from '@/routes/verification';

defineOptions({
    layout: {
        title: 'Потвърди имейла си',
        description: 'Изпратихме ти линк — отвори го, за да активираш профила',
    },
});

defineProps<{
    status?: string;
}>();
</script>

<template>
    <Head title="Потвърждение на имейл" />

    <!-- Nothing to type here, so the berry just waits, looking straight out. -->
    <AuthCard :gaze="0.5">
        <div
            v-if="status === 'verification-link-sent'"
            class="mb-5 rounded-2xl bg-sage-200 px-4 py-3 text-center text-base font-semibold text-plum-950"
        >
            Изпратихме нов линк на имейла, с който се регистрира.
        </div>

        <Form v-bind="send.form()" v-slot="{ processing }" class="grid gap-5">
            <button
                type="submit"
                :class="authQuietButton"
                :disabled="processing"
            >
                <Spinner v-if="processing" />
                Изпрати линка отново
            </button>

            <p
                class="text-center text-base text-plum-900/80 dark:text-plum-900/80"
            >
                Сгрешен профил?
                <Link :href="logout()" as="button" :class="authLink">
                    Излез
                </Link>
            </p>
        </Form>
    </AuthCard>
</template>
