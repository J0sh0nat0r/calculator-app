<script setup lang="ts">
import { ListCalculationsResponse } from '@/types/api';
import { TrashIcon } from '@heroicons/vue/16/solid';
import { InfiniteData, useMutation, useQueryClient } from '@tanstack/vue-query';
import axios from 'axios';

defineProps<{
    calculationId: string;
}>();

const queryClient = useQueryClient();

const { isPending, mutate } = useMutation({
    mutationFn: (id: string) =>
        axios.delete(
            route('api.v1.calculations.destroy', {
                calculation: id,
            }),
        ),
    async onMutate(id: string) {
        await queryClient.cancelQueries({ queryKey: ['calculations'] });

        const previousCalculations = queryClient.getQueryData(['calculations']);

        queryClient.setQueryData(
            ['calculations'],
            (old: InfiniteData<ListCalculationsResponse>) => ({
                pages: old.pages.map((p) => ({
                    data: p.data.filter((c) => c.id !== id),
                    meta: p.meta,
                    links: p.links,
                })),
                pageParams: [...old.pageParams],
            }),
        );

        return { previousCalculations };
    },
    onError(_data, _id, context) {
        queryClient.setQueryData(
            ['calculations'],
            context?.previousCalculations,
        );
    },
});
</script>

<template>
    <button
        class="absolute right-1 top-1 transition duration-150 ease-in-out hover:text-zinc-300 active:text-zinc-400 disabled:text-gray-400"
        :disabled="isPending"
        aria-label="Delete calculation"
        @click="() => mutate(calculationId)"
    >
        <TrashIcon class="size-4" />
    </button>
</template>
