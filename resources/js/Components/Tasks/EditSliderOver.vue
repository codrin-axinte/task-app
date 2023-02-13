<script setup>
import {ref, onMounted, onUnmounted, inject} from 'vue'
import {Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot} from '@headlessui/vue'
import {XMarkIcon} from '@heroicons/vue/24/outline'
import {useForm} from "@inertiajs/vue3";
import useTasks from "@/Composables/useTasks";
import InputError from "@/Components/InputError.vue";
import useSwal from "@/Composables/useSwal";
import useShakespeare from "@/Composables/useShakespeare";
import SuggestField from "@/Components/SuggestField.vue";


const emitter = inject('bus');

const {editForm, update} = useTasks();
const {toast} = useSwal();
const {updatedMessage} = useShakespeare();

const open = ref(false);
const taskOptions = ref([]);

const currentTask = ref();

const form = useForm({
    title: null,
    content: null,
    due_date: null,
    parent_id: null,
    priority: null,
})

function save() {
    let options = {
        preserveScroll: true,
        onError(e) {
            toast({
                message: 'Something went wrong',
                type: 'error'
            });
        },
        onSuccess() {
            toast({
                message: updatedMessage(),
                type: 'success'
            });
        }
    };


    update(currentTask.value, form.transform((data) => ({
        ...data,
        parent_id: data.parent_id ? data.parent_id.value : null
    })), options);
}

function edit(task) {
    currentTask.value = task;
    editForm(task).then(({data}) => {
        taskOptions.value = data.tasks;

        const formData = data.form;
        form.title = formData.title;
        form.content = formData.content;
        form.due_date = formData.due_date;
        form.parent_id = formData.parent_id;
        form.priority = formData.priority;
        open.value = true;
    });

}

function close() {
    form.cancel();
    open.value = false;
    form.reset();
}

onMounted(() => {
    emitter.on('task:edit', edit);
});

onUnmounted(() => {
    emitter.off('task:edit', edit);
});


</script>


<template>
    <TransitionRoot as="template" :show="open">
        <Dialog as="div" class="relative z-10" @close="close">
            <div class="fixed inset-0"/>

            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
                        <TransitionChild as="template"
                                         enter="transform transition ease-in-out duration-500 sm:duration-700"
                                         enter-from="translate-x-full" enter-to="translate-x-0"
                                         leave="transform transition ease-in-out duration-500 sm:duration-700"
                                         leave-from="translate-x-0" leave-to="translate-x-full">
                            <DialogPanel class="pointer-events-auto w-screen max-w-md">
                                <form
                                    @submit.prevent="save"
                                    class="flex h-full flex-col divide-y divide-gray-600 bg-base-200 shadow-xl border-l border-gray-800">
                                    <div class="h-0 flex-1 overflow-y-auto">
                                        <div class="bg-base-100 py-6 px-4 sm:px-6">
                                            <div class="flex items-center justify-between">
                                                <DialogTitle class="text-lg font-medium text-white">
                                                    Revise thy task, oh ye of little faith.
                                                </DialogTitle>
                                                <div class="ml-3 flex h-7 items-center">
                                                    <button type="button"
                                                            class="rounded-md  text-white hover:bg-primary focus:outline-none focus:ring-2 focus:ring-white transition"
                                                            @click="close">
                                                        <span class="sr-only">Close panel</span>
                                                        <XMarkIcon class="h-6 w-6" aria-hidden="true"/>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="mt-1">
                                                <p class="text-sm text-primary">
                                                    Begin by filling the information below, to amend thy task with
                                                    haste.
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex flex-1 flex-col justify-between">
                                            <div class="divide-y divide-gray-200 px-4 sm:px-6">
                                                <div
                                                    class="space-y-6 pt-6 pb-5 text-base-content">
                                                    <div>
                                                        <label for="title"
                                                               class="block text-sm font-medium">Title</label>
                                                        <div class="mt-1">
                                                            <input type="text"
                                                                   name="title"
                                                                   id="title"
                                                                   v-model="form.title"
                                                                   class="block w-full rounded-lg bg-base-300 border-base-100 shadow-sm  focus:border-primary outline-none focus:ring-primary sm:text-sm transition"/>
                                                        </div>


                                                        <InputError v-if="form.errors.title"
                                                                    :message="form.errors.title" class="mt-1"/>

                                                    </div>


                                                    <div>
                                                        <label for="due_date"
                                                               class="block text-sm font-medium">Due date</label>
                                                        <div class="mt-1">
                                                            <input type="datetime-local"
                                                                   name="due_date"
                                                                   id="due_date"
                                                                   v-model="form.due_date"
                                                                   class="block w-full rounded-lg bg-base-300 border-base-100 shadow-sm  focus:border-primary outline-none focus:ring-primary sm:text-sm transition"/>
                                                        </div>


                                                        <InputError v-if="form.errors.due_date"
                                                                    :message="form.errors.due_date" class="mt-1"/>
                                                    </div>

                                                    <div>
                                                        <label for="parent_id"
                                                               class="block text-sm font-medium">Parent task</label>
                                                        <div class="mt-1">
                                                            <SuggestField
                                                                :options="taskOptions"
                                                                v-model="form.parent_id"
                                                            />
                                                        </div>

                                                        <InputError v-if="form.errors.parent_id"
                                                                    :message="form.errors.parent_id" class="mt-1"/>
                                                    </div>


                                                    <div>
                                                        <label for="content"
                                                               class="block text-sm font-medium">Content</label>
                                                        <div class="mt-1">
                                                            <textarea id="content"
                                                                      name="content"
                                                                      rows="10"
                                                                      v-model="form.content"
                                                                      class="block w-full rounded-lg bg-base-300 border-base-100 shadow-sm focus:border-primary outline-none focus:ring-primary sm:text-sm transition"/>
                                                        </div>

                                                        <InputError
                                                            v-if="form.errors.content"
                                                            :message="form.errors.content"
                                                            class="mt-1"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="flex flex-shrink-0 justify-end px-4 py-4">
                                        <button type="button"
                                                class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition"
                                                @click="close">
                                            Revoke
                                        </button>
                                        <button type="submit"
                                                class="ml-4 inline-flex justify-center rounded-md border border-transparent bg-primary py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition">
                                            Preserve
                                        </button>
                                    </div>
                                </form>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

