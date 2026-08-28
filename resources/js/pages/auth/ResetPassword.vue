<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthCard from '@/components/auth/AuthCard.vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { authButton, authField, authLabel } from '@/lib/formClasses';
import { update } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Нова парола',
        description: 'Измисли си нова парола за профила си',
    },
});

const props = defineProps<{
    token: string;
    email: string;
    passwordRules: string;
}>();

const inputEmail = ref(props.email);
const passwordFocused = ref(false);
const passwordVisible = ref(false);
</script>

<template>
    <Head title="Нова парола" />

    <!-- Both fields here are secrets, so the berry starts out already covered. -->
    <AuthCard :covered="passwordFocused" :peeking="passwordVisible">
        <Form
            v-bind="update.form()"
            :transform="(data) => ({ ...data, token, email })"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
        >
            <div class="grid gap-5">
                <div class="grid gap-2">
                    <Label for="email" :class="authLabel">Имейл</Label>
                    <input
                        id="email"
                        v-model="inputEmail"
                        type="email"
                        name="email"
                        autocomplete="email"
                        readonly
                        :class="[authField, 'opacity-70']"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password" :class="authLabel">Нова парола</Label>
                    <PasswordInput
                        id="password"
                        name="password"
                        autocomplete="new-password"
                        autofocus
                        placeholder="Новата ти парола"
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
                    :disabled="processing"
                    data-test="reset-password-button"
                >
                    <Spinner v-if="processing" />
                    Запази новата парола
                </button>
            </div>
        </Form>
    </AuthCard>
</template>
