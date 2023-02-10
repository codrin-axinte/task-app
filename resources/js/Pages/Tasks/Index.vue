<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TaskList from "@/Components/Tasks/TaskList.vue";
import {Head} from "@inertiajs/vue3";
import {useForm, router, Link} from '@inertiajs/vue3'
import {PlusIcon, ListBulletIcon} from "@heroicons/vue/20/solid"


defineProps({tasks: Array, allowedFilters: Array, currentFilter: String})

const form = useForm({
    title: '',
    content: null
});

function createTask() {
    form.post(route('tasks.index'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        }
    });

}

const placeholders = [
    "What steps shall be taken to ensure success?",
    "What's to be done?",
    "What must be done, to thrive in this time?",
    "What shall be done?"
];

</script>


<template>
    <Head title="Tasks"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tasks</h2>
        </template>

        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="flex-1 mx-auto  mb-10 max-w-xl">
                    <form @submit.prevent="createTask">
                        <div class="relative mt-1 rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <PlusIcon class="h-5 w-5 text-base-300 bg-primary rounded-lg" aria-hidden="true"/>
                            </div>

                            <input
                                type="text"
                                v-model="form.title"
                                :placeholder="placeholders.random()"
                                :disabled="form.processing"
                                class="pl-10 block w-full rounded-full bg-base-300 border-base-100 mb-2 outline-none focus:border-primary focus:ring-primary transition"
                            />
                        </div>


                        <span v-if="form.errors.title" class="text-red-600">
                            {{ form.errors.title }}
                        </span>
                    </form>
                </div>

                <div class="flex-1 max-w-xl mx-auto">
                    <nav class="flex space-x-4 mb-4">
                        <Link v-for="(filter, key) in allowedFilters" :key="key" :href="route('tasks.index', {filter})"
                              :class="[currentFilter === filter ? 'text-primary'  : '', 'capitalize']">
                            {{ filter }}
                        </Link>
                    </nav>

                    <TaskList v-if="tasks.length > 0" :tasks="tasks"/>


                    <div v-else type="button"
                         class="relative block w-full rounded-lg border-2 border-dashed border-base-200 p-12 text-center hover:border-base-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">

                        <ListBulletIcon class="mx-auto h-12 w-12 text-gray-600"/>
                        <span class="mt-2 block text-lg font-medium text-gray-400">Where art thou, tasks?</span>
                    </div>


                </div>

            </div>
        </div>

    </AuthenticatedLayout>
</template>

