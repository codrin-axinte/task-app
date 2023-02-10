<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps(['href', 'active']);

const classes = computed(() =>
    props.active
        ? 'inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-base-content focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
        : 'inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-base-content hover:text-primary hover:border-gray-300  focus:outline-none focus:text-gray-700 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
