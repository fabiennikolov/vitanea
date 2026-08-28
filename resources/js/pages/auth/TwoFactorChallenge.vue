<script setup lang="ts">
import { Form, Head, setLayoutProps } from '@inertiajs/vue3';
import { computed, ref, watchEffect } from 'vue';
import AuthCard from '@/components/auth/AuthCard.vue';
import InputError from '@/components/InputError.vue';
import {
    InputOTP,
    InputOTPGroup,
    InputOTPSlot,
} from '@/components/ui/input-otp';
import { authButton, authField } from '@/lib/formClasses';
import { store } from '@/routes/two-factor/login';
import type { TwoFactorConfigContent } from '@/types';

const showRecoveryInput = ref<boolean>(false);
const code = ref<string>('');

/** The berry watches the code fill up, digit by digit. */
const gaze = computed(() => Math.min(code.value.length / 6, 1));

const authConfigContent = computed<TwoFactorConfigContent>(() => {
    if (showRecoveryInput.value) {
        return {
            title: 'Код за възстановяване',
            description: 'Въведи един от аварийните си кодове',
            buttonText: 'влез с код от приложението',
        };
    }

    return {
        title: 'Код за вход',
        description: 'Въведи кода от приложението за удостоверяване',
        buttonText: 'влез с код за възстановяване',
    };
});

watchEffect(() => {
    setLayoutProps({
        title: authConfigContent.value.title,
        description: authConfigContent.value.description,
    });
});

const toggleRecoveryMode = (clearErrors: () => void): void => {
    showRecoveryInput.value = !showRecoveryInput.value;
    clearErrors();
    code.value = '';
};
</script>

<template>
    <Head title="Двуфакторна защита" />

    <AuthCard :gaze="gaze">
        <Form
            v-if="!showRecoveryInput"
            v-bind="store.form()"
            class="grid gap-5"
            reset-on-error
            @error="code = ''"
            #default="{ errors, processing, clearErrors }"
        >
            <input type="hidden" name="code" :value="code" />

            <div class="grid justify-items-center gap-3 text-center">
                <InputOTP
                    id="otp"
                    v-model="code"
                    :maxlength="6"
                    :disabled="processing"
                    autofocus
                >
                    <InputOTPGroup>
                        <InputOTPSlot
                            v-for="index in 6"
                            :key="index"
                            :index="index - 1"
                            class="size-12 border-plum-950/15 bg-cream-50 text-lg font-bold text-plum-950 dark:bg-cream-50 dark:text-plum-950"
                        />
                    </InputOTPGroup>
                </InputOTP>
                <InputError :message="errors.code" />
            </div>

            <button type="submit" :class="authButton" :disabled="processing">
                Продължи
            </button>

            <p
                class="text-center text-base text-plum-900/80 dark:text-plum-900/80"
            >
                Или
                <button
                    type="button"
                    class="rounded-sm font-bold text-berry-600 underline decoration-berry-600/40 underline-offset-4 transition-colors hover:decoration-berry-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-plum-900"
                    @click="() => toggleRecoveryMode(clearErrors)"
                >
                    {{ authConfigContent.buttonText }}
                </button>
            </p>
        </Form>

        <Form
            v-else
            v-bind="store.form()"
            class="grid gap-5"
            reset-on-error
            #default="{ errors, processing, clearErrors }"
        >
            <div class="grid gap-2">
                <input
                    name="recovery_code"
                    type="text"
                    placeholder="Аварийният ти код"
                    :autofocus="showRecoveryInput"
                    required
                    :class="authField"
                />
                <InputError :message="errors.recovery_code" />
            </div>

            <button type="submit" :class="authButton" :disabled="processing">
                Продължи
            </button>

            <p
                class="text-center text-base text-plum-900/80 dark:text-plum-900/80"
            >
                Или
                <button
                    type="button"
                    class="rounded-sm font-bold text-berry-600 underline decoration-berry-600/40 underline-offset-4 transition-colors hover:decoration-berry-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-plum-900"
                    @click="() => toggleRecoveryMode(clearErrors)"
                >
                    {{ authConfigContent.buttonText }}
                </button>
            </p>
        </Form>
    </AuthCard>
</template>
