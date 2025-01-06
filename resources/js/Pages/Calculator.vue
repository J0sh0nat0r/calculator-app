<script setup lang="ts">
import { computed, ref, useTemplateRef, watch, watchEffect } from 'vue';

import Calculation from '@/Components/Calculation.vue';
import CalculatorForm from '@/Components/NewCalculationForm.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ListCalculationsResponse } from '@/types/api';
import { useInfiniteQuery } from '@tanstack/vue-query';
import { useVirtualizer } from '@tanstack/vue-virtual';
import { useElementVisibility } from '@vueuse/core';

const perPage = 25;

const { data, fetchNextPage, hasNextPage, isFetchingNextPage } =
    useInfiniteQuery({
        queryKey: ['calculations'],
        queryFn: async ({ pageParam }) => {
            const response = await fetch(
                route('api.v1.calculations.index', {
                    per_page: perPage,
                    cursor: pageParam,
                }),
            );

            // FIXME: guard
            return (await response.json()) as ListCalculationsResponse;
        },
        getNextPageParam: (lastPage: ListCalculationsResponse) =>
            lastPage.meta.next_cursor,
        initialPageParam: null,
    });

const getRow = (index: number) => {
    const pages = data.value?.pages;

    if (!pages) throw new Error('getRow with no pages');

    let page = 0;

    // We treat `pages` like an unrolled linked list due to variable page length
    let pageLength = pages[page].data.length;
    while (pageLength <= index) {
        index -= pageLength;
        pageLength = pages[++page].data.length;
    }

    return pages[page].data[index];
};

const parentRef = useTemplateRef<HTMLElement>('parent-ref');
const loadTrigger = useTemplateRef<HTMLElement>('load-trigger');
const loadTriggerVisible = useElementVisibility(loadTrigger, {
    rootMargin:
        window && `${window.innerHeight * window.devicePixelRatio * 2}px`,
    scrollTarget: parentRef,
});

watchEffect(() => {
    if (
        hasNextPage.value &&
        !isFetchingNextPage.value &&
        loadTriggerVisible.value
    ) {
        fetchNextPage();
    }
});

const sizeEstimate = ref(60);

const rowVirtualizer = useVirtualizer(
    computed(() => {
        const el = parentRef.value;

        return {
            count:
                data.value?.pages.reduce(
                    (length, page) => length + page.data.length,
                    0,
                ) ?? 0,
            getScrollElement: () => el,
            estimateSize: () => sizeEstimate.value,
            overscan: 8,
            getItemKey: (i: number) => getRow(i).id,
        };
    }),
);

const virtualRows = computed(() => rowVirtualizer.value.getVirtualItems());
const totalSize = computed(() => rowVirtualizer.value.getTotalSize());

watch(totalSize, (newValue) => {
    sizeEstimate.value = Math.ceil(
        (newValue / rowVirtualizer.value.options.count) * 1.33,
    );
});

const measureElement = (el: HTMLElement) => {
    if (!el) {
        return;
    }

    rowVirtualizer.value.measureElement(el);

    return undefined;
};
</script>

<template>
    <AppLayout>
        <div class="flex justify-center bg-slate-950 pb-4 pt-3 md:pb-6">
            <CalculatorForm />
        </div>
        <div
            ref="parent-ref"
            class="grow overflow-y-auto overflow-x-hidden overscroll-contain overflow-anchor-none"
        >
            <div class="relative" :style="{ height: `${totalSize}px` }">
                <div
                    class="absolute left-0 top-0 flex w-full flex-col items-center gap-4 px-8 pb-8 pt-4 will-change-transform sm:gap-6"
                    :style="{
                        transform: `translateY(${virtualRows[0]?.start ?? 0}px)`,
                    }"
                >
                    <div
                        v-for="row in virtualRows"
                        :key="row.key as string"
                        class="w-full max-w-screen-md duration-200"
                        :data-index="row.index"
                        :ref="measureElement"
                    >
                        <Calculation
                            :calculation="getRow?.(row.index)"
                        ></Calculation>
                    </div>
                </div>
            </div>
            <div ref="load-trigger" />
        </div>
    </AppLayout>
</template>
