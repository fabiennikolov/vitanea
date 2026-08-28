<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AuthCard from '@/components/auth/AuthCard.vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { authButton, authField, authLabel, authLink } from '@/lib/formClasses';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Влез в профила си',
        description: 'Въведи имейл и парола, за да продължиш',
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const email = ref('');
const passwordFocused = ref(false);
const passwordVisible = ref(false);

/** The berry tracks the caret along the field rather than the raw length. */
const gaze = computed(() => Math.min(email.value.length / 26, 1));
</script>

<template>
    <Head title="Вход" />

    <AuthCard
        :gaze="gaze"
        :covered="passwordFocused"
        :peeking="passwordVisible"
    >
        <div
            v-if="status"
            class="mb-4 rounded-2xl bg-sage-200 px-4 py-3 text-center text-base font-semibold text-plum-950"
        >
            {{ status }}
        </div>

        <Form
            v-bind="store.form()"
            :reset-on-success="['password']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-5">
                <div class="grid gap-2">
                    <Label for="email" :class="authLabel">Имейл</Label>
                    <input
                        id="email"
                        v-model="email"
                        type="email"
                        name="email"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="email"
                        placeholder="ime@primer.bg"
                        :class="authField"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between gap-3">
                        <Label for="password" :class="authLabel">Парола</Label>
                        <Link
                            v-if="canResetPassword"
                            :href="request()"
                            :class="[authLink, 'text-sm']"
                            :tabindex="5"
                        >
                            Забравена парола?
                        </Link>
                    </div>

                    <!--
                      Focus and visibility drive the illustration: the berry
                      covers its eyes while the password is being typed and
                      peeks only once the field itself has been revealed.
                    -->
                    <PasswordInput
                        id="password"
                        name="password"
                        required
                        :tabindex="2"
                        autocomplete="current-password"
                        placeholder="Твоята парола"
                        :class="authField"
                        @focus="passwordFocused = true"
                        @blur="passwordFocused = false"
                        @visibility="passwordVisible = $event"
                    />
                    <InputError :message="errors.password" />
                </div>

                <Label
                    for="remember"
                    class="flex items-center gap-3 text-base text-plum-900/85 dark:text-plum-900/85"
                >
                    <Checkbox
                        id="remember"
                        name="remember"
                        :tabindex="3"
                        class="border-plum-950/30 data-[state=checked]:border-plum-900 data-[state=checked]:bg-plum-900 data-[state=checked]:text-cream-50"
                    />
                    <span>Запомни ме</span>
                </Label>

                <button
                    type="submit"
                    :class="authButton"
                    :tabindex="4"
                    :disabled="processing"
                    data-test="login-button"
                >
                    <Spinner v-if="processing" />
                    Влез
                </button>
            </div>

            <p
                class="text-center text-base text-plum-900/80 dark:text-plum-900/80"
            >
                Нямаш профил?
                <Link :href="register()" :class="authLink" :tabindex="5">
                    Регистрирай се
                </Link>
            </p>
        </Form>
    </AuthCard>
</template>
