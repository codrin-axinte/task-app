<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TaskList from "@/Components/Tasks/TaskList.vue";
import {Head} from "@inertiajs/vue3";
import {useForm, Link} from '@inertiajs/vue3'
import {PlusIcon, ListBulletIcon, XMarkIcon} from "@heroicons/vue/20/solid"
import useTasks from "@/Composables/useTasks";
import useShakespeare from "@/Composables/useShakespeare";
import EditSliderOver from "@/Components/Tasks/EditSliderOver.vue";
import {watch} from 'vue'
import debounce from 'lodash.debounce'
import {TransitionRoot} from '@headlessui/vue'

const props = defineProps({tasks: Object, allowedFilters: Array, currentFilter: String, searchQuery: String})


const form = useForm({
    title: '',
});


const {create, search} = useTasks();
const {createPlaceholder} = useShakespeare();

//const searchTerm = ref('');

const onSearch = debounce(() => {
    search(form.title, props.currentFilter);
}, 500)

function clearForm() {
    form.title = '';
}

watch(form, () => {
    onSearch();
});

</script>


<template>
    <Head title="Tasks"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tasks</h2>
        </template>

        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="flex-1 px-4 mx-auto mb-10 sm:px-0 sm:max-w-xl">
                    <form @submit.prevent="create(form)">
                        <div class="relative mt-1 rounded-md shadow-sm">
                            <TransitionRoot
                                :show="form.title.length > 1"
                                enter="transition-opacity duration-75"
                                enter-from="opacity-0"
                                enter-to="opacity-100"
                                leave="transition-opacity duration-150"
                                leave-from="opacity-100"
                                leave-to="opacity-0"
                            >

                                <button type="button" @click="clearForm"
                                        class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <XMarkIcon class="h-5 w-5 text-gray-400 hover:text-primary rounded-full"
                                               aria-hidden="true"/>
                                </button>

                            </TransitionRoot>

                            <input
                                type="text"
                                v-model="form.title"
                                :placeholder="createPlaceholder()"
                                :disabled="form.processing"
                                class="pr-10 pl-10 py-3 block w-full rounded-full bg-base-300 border-2 border-gray-700 mb-2 outline-none border-dashed focus:border focus:border-solid focus:border-primary focus:ring-primary transition"
                            />

                            <TransitionRoot
                                :show="form.title.length > 1"
                                enter="transition-opacity duration-75"
                                enter-from="opacity-0"
                                enter-to="opacity-100"
                                leave="transition-opacity duration-150"
                                leave-from="opacity-100"
                                leave-to="opacity-0"
                            >

                                <button type="submit"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <PlusIcon class="h-6 w-6 text-base-300 bg-primary rounded-full" aria-hidden="true"/>
                                </button>
                            </TransitionRoot>
                        </div>


                        <span v-if="form.errors.title" class="text-red-600">
                            {{ form.errors.title }}
                        </span>
                    </form>
                </div>

                <div class="flex-1 px-4 sm:px-0 sm:max-w-xl mx-auto">
                    <nav class="flex space-x-4 mb-4">
                        <Link v-for="(filter, key) in allowedFilters" :key="key" :href="route('tasks.index', {filter})"
                              :class="[currentFilter === filter ? 'text-primary'  : '', 'capitalize']">
                            {{ filter }}
                        </Link>
                    </nav>


                    <TaskList v-if="tasks.data.length > 0" :tasks="tasks.data"/>


                    <div v-else type="button"
                         class="relative block w-full rounded-lg border-2 border-dashed border-base-200 p-12 text-center hover:border-base-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">

                        <ListBulletIcon class="mx-auto h-12 w-12 text-gray-600"/>
                        <span class="mt-2 block text-lg font-medium text-gray-400">Where art thou, tasks?</span>
                    </div>


                </div>

            </div>
        </div>

        <EditSliderOver/>
    </AuthenticatedLayout>
</template>

