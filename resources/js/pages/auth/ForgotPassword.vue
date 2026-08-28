<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AuthCard from '@/components/auth/AuthCard.vue';
import InputError from '@/components/InputError.vue';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { authButton, authField, authLabel, authLink } from '@/lib/formClasses';
import { login } from '@/routes';
import { email as emailRoute } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Забравена парола',
        description: 'Ще ти изпратим линк за нова парола',
    },
});

defineProps<{
    status?: string;
}>();

const address = ref('');

const gaze = computed(() => Math.min(address.value.length / 26, 1));
</script>

<template>
    <Head title="Забравена парола" />

    <AuthCard :gaze="gaze">
        <div
            v-if="status"
            class="mb-5 rounded-2xl bg-sage-200 px-4 py-3 text-center text-base font-semibold text-plum-950"
        >
            {{ status }}
        </div>

        <Form
            v-bind="emailRoute.form()"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-2">
                <Label for="email" :class="authLabel">Имейл</Label>
                <input
                    id="email"
                    v-model="address"
                    type="email"
                    name="email"
                    autocomplete="off"
                    autofocus
                    placeholder="ime@primer.bg"
                    :class="authField"
                />
                <InputError :message="errors.email" />
            </div>

            <button
                type="submit"
                :class="authButton"
                :disabled="processing"
                data-test="email-password-reset-link-button"
            >
                <Spinner v-if="processing" />
                Изпрати линк
            </button>

            <p
                class="text-center text-base text-plum-900/80 dark:text-plum-900/80"
            >
                Сети ли се?
                <Link :href="login()" :class="authLink"
                    >Върни се към входа</Link
                >
            </p>
        </Form>
    </AuthCard>
</template>
