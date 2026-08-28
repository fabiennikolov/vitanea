<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthCard from '@/components/auth/AuthCard.vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { authButton, authField, authLabel } from '@/lib/formClasses';
import { store } from '@/routes/password/confirm';

defineOptions({
    layout: {
        title: 'Потвърди паролата',
        description:
            'Това е защитена част — потвърди паролата си, за да продължиш',
    },
});

const passwordFocused = ref(false);
const passwordVisible = ref(false);
</script>

<template>
    <Head title="Потвърди паролата" />

    <AuthCard :covered="passwordFocused" :peeking="passwordVisible">
        <Form
            v-bind="store.form()"
            reset-on-success
            v-slot="{ errors, processing }"
        >
            <div class="grid gap-5">
                <div class="grid gap-2">
                    <Label for="password" :class="authLabel">Парола</Label>
                    <PasswordInput
                        id="password"
                        name="password"
                        required
                        autofocus
                        autocomplete="current-password"
                        placeholder="Твоята парола"
                        :class="authField"
                        @focus="passwordFocused = true"
                        @blur="passwordFocused = false"
                        @visibility="passwordVisible = $event"
                    />
                    <InputError :message="errors.password" />
                </div>

                <button
                    type="submit"
                    :class="authButton"
                    :disabled="processing"
                    data-test="confirm-password-button"
                >
                    <Spinner v-if="processing" />
                    Потвърди
                </button>
            </div>
        </Form>
    </AuthCard>
</template>
