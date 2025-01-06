<script setup lang="ts">
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {
    CalculatorIcon,
    EqualsIcon,
    ExclamationCircleIcon,
} from '@heroicons/vue/16/solid';
import { InfiniteData, useQueryClient } from '@tanstack/vue-query';
import { useForm } from 'laravel-precognition-vue';
import { useTemplateRef } from 'vue';

defineProps<{
    loading?: boolean;
}>();

const queryClient = useQueryClient();

const input = useTemplateRef<HTMLInputElement>('input');

const form = useForm('post', route('api.v1.calculations.store'), {
    expr: '',
});

const submit = () =>
    form.submit({
        onSuccess({ data: { data } }) {
            form.reset();

            queryClient.setQueryData(
                ['calculations'],
                (old: InfiniteData<unknown>) => ({
                    pages: [{ data: [data] }, ...old.pages],
                    pageParams: [null, ...old.pageParams],
                }),
            );
        },
    });
</script>

<template>
    <form class="flex w-fit flex-col" @submit.prevent="submit">
        <div class="mb-1 min-h-6 text-sm">
            <span v-if="form.processing" class="align-middle text-gray-50">
                <CalculatorIcon
                    class="inline size-4 animate-pulse text-emerald-300"
                />
                Thinking about it...
            </span>
            <span
                v-else-if="form.invalid('expr')"
                class="align-middle text-red-500"
            >
                <ExclamationCircleIcon class="inline size-4" />
                {{ form.errors.expr }}
            </span>
            <label v-else for="expr" class="align-middle text-gray-50">
                <CalculatorIcon class="inline size-4" />
                Calculate something
            </label>
        </div>

        <div class="flex">
            <input
                id="expr"
                ref="input"
                v-model="form.expr"
                class="form-input rounded-md border-gray-400 bg-gray-700 text-white data-invalid:border-red-500 focus:border-teal-600 focus:ring-teal-600 data-invalid:focus:border-red-500 data-invalid:focus:ring-red-500 disabled:text-gray-500"
                :data-invalid="form.invalid('expr')"
                :disabled="form.processing"
                placeholder="Enter an expression"
                autofocus
                @change="form.validate('expr')"
            />
            <PrimaryButton
                type="submit"
                class="ml-2 shrink-0"
                aria-label="Calculate"
                :disabled="form.processing"
            >
                <EqualsIcon class="size-4" />
            </PrimaryButton>
        </div>
    </form>
</template>
