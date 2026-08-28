<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import CheckoutController from '@/actions/App/Http/Controllers/Shop/CheckoutController';
import InputError from '@/components/InputError.vue';
import SectionHeading from '@/components/store/SectionHeading.vue';
import SectionShell from '@/components/store/SectionShell.vue';
import { cn } from '@/lib/utils';

type Line = {
    variantId: number;
    productName: string;
    variantLabel: string;
    quantity: number;
    lineTotal: string;
};

type DeliveryOption = {
    value: string;
    label: string;
    cost: string;
    costMinor: number;
    freeOverThreshold: boolean;
};

type PaymentOption = {
    value: string;
    label: string;
    description: string;
};

type Props = {
    lines: Line[];
    subtotal: string;
    subtotalMinor: number;
    freeShippingThresholdMinor: number;
    deliveryMethods: DeliveryOption[];
    paymentMethods: PaymentOption[];
};

const { deliveryMethods, subtotalMinor, freeShippingThresholdMinor } =
    defineProps<Props>();

const deliveryMethod = ref(deliveryMethods[0]?.value ?? '');
const paymentMethod = ref('cash_on_delivery');

const selectedDelivery = computed(() =>
    deliveryMethods.find((method) => method.value === deliveryMethod.value),
);

/**
 * Mirrors Cart::shippingMinor so the shown total matches the one the server
 * calculates. The server remains authoritative.
 */
const shippingLabel = computed(() => {
    const method = selectedDelivery.value;

    if (!method) {
        return '—';
    }

    if (
        method.freeOverThreshold &&
        subtotalMinor >= freeShippingThresholdMinor
    ) {
        return 'Безплатна';
    }

    return method.cost;
});

const fieldClasses =
    'mt-2 w-full rounded-2xl bg-cream-50 px-4 py-3 text-base text-plum-950 ring-1 ring-plum-950/15 focus:ring-2 focus:ring-plum-900 focus:outline-none';
</script>

<template>
    <Head title="Поръчка" />

    <SectionShell>
        <SectionHeading
            headline="Поръчка"
            subheadline="Доставяме само на територията на България, с Еконт и Спиди."
        />

        <Form
            v-bind="CheckoutController.store.form()"
            v-slot="{ errors, processing }"
            class="mt-14 grid gap-12 lg:grid-cols-12 lg:gap-16"
        >
            <div class="grid gap-10 lg:col-span-7">
                <fieldset>
                    <legend
                        class="font-mono text-sm tracking-wide text-plum-900/80 uppercase"
                    >
                        Данни за контакт
                    </legend>

                    <div class="mt-5 grid gap-5">
                        <div>
                            <label
                                for="customer_name"
                                class="text-base text-plum-950"
                            >
                                Име и фамилия
                            </label>
                            <input
                                id="customer_name"
                                name="customer_name"
                                type="text"
                                autocomplete="name"
                                required
                                :class="fieldClasses"
                            />
                            <InputError :message="errors.customer_name" />
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label
                                    for="customer_email"
                                    class="text-base text-plum-950"
                                >
                                    Имейл
                                </label>
                                <input
                                    id="customer_email"
                                    name="customer_email"
                                    type="email"
                                    autocomplete="email"
                                    required
                                    :class="fieldClasses"
                                />
                                <InputError :message="errors.customer_email" />
                            </div>

                            <div>
                                <label
                                    for="customer_phone"
                                    class="text-base text-plum-950"
                                >
                                    Телефон
                                </label>
                                <input
                                    id="customer_phone"
                                    name="customer_phone"
                                    type="tel"
                                    autocomplete="tel"
                                    required
                                    :class="fieldClasses"
                                />
                                <InputError :message="errors.customer_phone" />
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend
                        class="font-mono text-sm tracking-wide text-plum-900/80 uppercase"
                    >
                        Доставка
                    </legend>

                    <div class="mt-5 grid gap-3">
                        <label
                            v-for="method in deliveryMethods"
                            :key="method.value"
                            :class="
                                cn(
                                    'flex cursor-pointer items-center justify-between gap-4 rounded-2xl px-4 py-3.5 ring-1',
                                    deliveryMethod === method.value
                                        ? 'bg-cream-50 ring-plum-900'
                                        : 'bg-cream-50/50 ring-plum-950/10 hover:ring-plum-950/25',
                                )
                            "
                        >
                            <span class="flex items-center gap-3">
                                <input
                                    v-model="deliveryMethod"
                                    type="radio"
                                    name="delivery_method"
                                    :value="method.value"
                                    class="size-4 shrink-0 accent-plum-900"
                                />
                                <span class="text-base text-plum-950">
                                    {{ method.label }}
                                </span>
                            </span>
                            <span
                                class="shrink-0 text-base text-plum-900/80 tabular-nums"
                            >
                                {{ method.cost }}
                            </span>
                        </label>
                    </div>
                    <InputError :message="errors.delivery_method" />

                    <div class="mt-5 grid gap-5 sm:grid-cols-2">
                        <div>
                            <label
                                for="delivery_city"
                                class="text-base text-plum-950"
                            >
                                Населено място
                            </label>
                            <input
                                id="delivery_city"
                                name="delivery_city"
                                type="text"
                                autocomplete="address-level2"
                                required
                                :class="fieldClasses"
                            />
                            <InputError :message="errors.delivery_city" />
                        </div>

                        <div>
                            <label
                                for="delivery_postcode"
                                class="text-base text-plum-950"
                            >
                                Пощенски код
                            </label>
                            <input
                                id="delivery_postcode"
                                name="delivery_postcode"
                                type="text"
                                autocomplete="postal-code"
                                :class="fieldClasses"
                            />
                            <InputError :message="errors.delivery_postcode" />
                        </div>
                    </div>

                    <div class="mt-5">
                        <label
                            for="delivery_address"
                            class="text-base text-plum-950"
                        >
                            Адрес или офис на куриера
                        </label>
                        <input
                            id="delivery_address"
                            name="delivery_address"
                            type="text"
                            autocomplete="street-address"
                            required
                            :class="fieldClasses"
                        />
                        <InputError :message="errors.delivery_address" />
                    </div>
                </fieldset>

                <fieldset>
                    <legend
                        class="font-mono text-sm tracking-wide text-plum-900/80 uppercase"
                    >
                        Плащане
                    </legend>

                    <div class="mt-5 grid gap-3">
                        <label
                            v-for="method in paymentMethods"
                            :key="method.value"
                            :class="
                                cn(
                                    'flex cursor-pointer items-start gap-3 rounded-2xl px-4 py-3.5 ring-1',
                                    paymentMethod === method.value
                                        ? 'bg-cream-50 ring-plum-900'
                                        : 'bg-cream-50/50 ring-plum-950/10 hover:ring-plum-950/25',
                                )
                            "
                        >
                            <input
                                v-model="paymentMethod"
                                type="radio"
                                name="payment_method"
                                :value="method.value"
                                class="mt-1 size-4 shrink-0 accent-plum-900"
                            />
                            <span>
                                <span class="block text-base text-plum-950">
                                    {{ method.label }}
                                </span>
                                <span
                                    class="mt-1 block text-base text-plum-900/80"
                                >
                                    {{ method.description }}
                                </span>
                            </span>
                        </label>
                    </div>
                    <InputError :message="errors.payment_method" />
                </fieldset>

                <div>
                    <label for="notes" class="text-base text-plum-950">
                        Бележка към поръчката
                    </label>
                    <textarea
                        id="notes"
                        name="notes"
                        rows="3"
                        :class="fieldClasses"
                    />
                    <InputError :message="errors.notes" />
                </div>
            </div>

            <aside class="lg:col-span-5">
                <div
                    class="rounded-[1.75rem] bg-cream-50/70 p-8 ring-1 ring-plum-950/10"
                >
                    <h2
                        class="font-mono text-sm tracking-wide text-plum-900/80 uppercase"
                    >
                        Вашата поръчка
                    </h2>

                    <ul role="list" class="mt-6 grid gap-4">
                        <li
                            v-for="line in lines"
                            :key="line.variantId"
                            class="flex items-baseline justify-between gap-4 border-b border-plum-950/10 pb-4"
                        >
                            <span class="text-base text-plum-950">
                                {{ line.productName }}
                                <span class="text-plum-900/75">
                                    · {{ line.variantLabel }} ×
                                    <span class="tabular-nums">{{
                                        line.quantity
                                    }}</span>
                                </span>
                            </span>
                            <span
                                class="shrink-0 text-base text-plum-950 tabular-nums"
                            >
                                {{ line.lineTotal }}
                            </span>
                        </li>
                    </ul>

                    <dl class="mt-6 grid gap-3">
                        <div class="flex items-baseline justify-between gap-4">
                            <dt class="text-base text-plum-900/80">
                                Междинна сума
                            </dt>
                            <dd class="text-base text-plum-950 tabular-nums">
                                {{ subtotal }}
                            </dd>
                        </div>
                        <div class="flex items-baseline justify-between gap-4">
                            <dt class="text-base text-plum-900/80">Доставка</dt>
                            <dd class="text-base text-plum-950 tabular-nums">
                                {{ shippingLabel }}
                            </dd>
                        </div>
                    </dl>

                    <label
                        class="mt-8 flex items-start gap-3 text-base text-plum-900/85"
                    >
                        <input
                            type="checkbox"
                            name="terms"
                            value="1"
                            class="mt-1 size-4 shrink-0 accent-plum-900"
                        />
                        Съгласен съм с общите условия и политиката за
                        поверителност.
                    </label>
                    <InputError :message="errors.terms" />

                    <button
                        type="submit"
                        :disabled="processing"
                        class="mt-8 w-full rounded-full bg-plum-900 px-7 py-4 text-base text-cream-50 hover:bg-plum-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-plum-900 disabled:opacity-60"
                    >
                        Завърши поръчката
                    </button>
                </div>
            </aside>
        </Form>
    </SectionShell>
</template>
