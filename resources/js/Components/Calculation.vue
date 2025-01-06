<script setup lang="ts">
import DeleteCalculationButton from '@/Components/DeleteCalculationButton.vue';
import Expr from '@/Components/Math/Expr';
import { Calculation } from '@/types/api';
import { breakpointsTailwind, useBreakpoints } from '@vueuse/core';
import { computed } from 'vue';

const props = defineProps<{
    calculation: Calculation;
}>();

const trimmedResult = computed(() =>
    props.calculation.result.replace(/([0-9])\.?0+$/, '$1'),
);

const breakpoints = useBreakpoints(breakpointsTailwind);

const displayStyle = breakpoints.greaterOrEqual('sm');
</script>

<template>
    <div class="relative rounded bg-pink-600 p-3 text-white">
        <DeleteCalculationButton :calculation-id="calculation.id" />
        <div
            class="flex flex-col gap-1 scrollbar-track-transparent scrollbar-thumb-pink-100"
        >
            <div
                class="max-w-full self-start overflow-x-auto overflow-y-hidden scrollbar-thin"
            >
                <math display="block" :displaystyle="displayStyle" class="m-2">
                    <Expr :expr="calculation.expr.ast"></Expr>
                </math>
            </div>
            <hr />
            <div
                class="max-w-full self-end overflow-x-auto overflow-y-hidden scrollbar-thin"
            >
                <math display="block" :displaystyle="displayStyle" class="m-2">
                    <mrow>
                        <mo>=</mo>
                        <mn v-text="trimmedResult" />
                    </mrow>
                </math>
            </div>
        </div>
    </div>
</template>
