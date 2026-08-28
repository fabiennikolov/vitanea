<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AuthCard from '@/components/auth/AuthCard.vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { authButton, authField, authLabel, authLink } from '@/lib/formClasses';
import { login } from '@/routes';
import { store } from '@/routes/register';

defineProps<{
    passwordRules: string;
}>();

defineOptions({
    layout: {
        title: 'Създай профил',
        description: 'Попълни данните си и продължаваме',
    },
});

const name = ref('');
const email = ref('');
const passwordFocused = ref(false);
const passwordVisible = ref(false);

/** The berry follows whichever of the two open fields is being filled in. */
const gaze = computed(() =>
    Math.min((name.value.length + email.value.length) / 30, 1),
);
</script>

<template>
    <Head title="Регистрация" />

    <AuthCard
        :gaze="gaze"
        :covered="passwordFocused"
        :peeking="passwordVisible"
    >
        <Form
            v-bind="store.form()"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-5">
                <div class="grid gap-2">
                    <Label for="name" :class="authLabel">Име</Label>
                    <input
                        id="name"
                        v-model="name"
                        type="text"
                        name="name"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="name"
                        placeholder="Име и фамилия"
                        :class="authField"
                    />
                    <InputError :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email" :class="authLabel">Имейл</Label>
                    <input
                        id="email"
                        v-model="email"
                        type="email"
                        name="email"
                        required
                        :tabindex="2"
                        autocomplete="email"
                        placeholder="ime@primer.bg"
                        :class="authField"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password" :class="authLabel">Парола</Label>
                    <PasswordInput
                        id="password"
                        name="password"
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        placeholder="Измисли си парола"
                        :passwordrules="passwordRules"
                        :class="authField"
                        @focus="passwordFocused = true"
                        @blur="passwordFocused = false"
                        @visibility="passwordVisible = $event"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation" :class="authLabel">
                        Потвърди паролата
                    </Label>
                    <PasswordInput
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        placeholder="Същата парола още веднъж"
                        :passwordrules="passwordRules"
                        :class="authField"
                        @focus="passwordFocused = true"
                        @blur="passwordFocused = false"
                        @visibility="passwordVisible = $event"
                    />
                    <InputError :message="errors.password_confirmation" />
                </div>

                <button
                    type="submit"
                    :class="authButton"
                    :tabindex="5"
                    :disabled="processing"
                    data-test="register-user-button"
                >
                    <Spinner v-if="processing" />
                    Създай профил
                </button>
            </div>

            <p
                class="text-center text-base text-plum-900/80 dark:text-plum-900/80"
            >
                Вече имаш профил?
                <Link :href="login()" :class="authLink" :tabindex="6">
                    Влез
                </Link>
            </p>
        </Form>
    </AuthCard>
</template>
