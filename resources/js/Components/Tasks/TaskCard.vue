<script setup>
import {ref} from "vue";
import {router, useForm} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import {TrashIcon, ArrowPathIcon} from "@heroicons/vue/24/outline"
import {CheckIcon, ArrowUturnLeftIcon, XMarkIcon, HandThumbUpIcon} from "@heroicons/vue/24/solid"

const props = defineProps({task: Object})

const isEditing = ref(false);
const editInput = ref();

const form = useForm({
    title: '',
    content: null,
})

function save() {
    form.put(route('tasks.update', props.task), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            isEditing.value = false;
        }
    })

}

function cancel() {
    isEditing.value = false;
}

function edit() {
    form.title = props.task.title;
    isEditing.value = true;
    //editInput.value.value.focus();
}

function deleteTask() {
    router.delete(route('tasks.destroy', props.task));
}

function toggleTask() {
    router.put(route('tasks.toggle', props.task));
}

function restore() {
    router.put(route('tasks.restore', props.task));
}

</script>

<template>
    <div
        class="bg-base-200 overflow-hidden border border-transparent hover:border-primary shadow-md hover:shadow-xl sm:rounded-lg transition">

        <form v-if="isEditing" @submit.prevent="save" class="flex space-x-4 p-6">
            <input
                v-model="form.title"
                name="content"
                placeholder="What needs to be done?"
                class="bg-transparent border-none outline-none ring:focus:outline-none focus:outline-none w-full"/>

            <InputError
                v-if="form.errors.title"
                :message="form.errors.title"
            />

            <div class="flex space-x-4">
                <button type="submit" @click="save" title="Save" class="text-emerald-500 hover:text-emerald-400 transition">
                    <span class="sr-only">Save</span>
                    <HandThumbUpIcon class="w-5 h-5" />
                </button>
                <button type="reset" @click="cancel" title="Cancel" class="text-red-500 hover:text-red-400 transition">
                    <span class="sr-only">Cancel</span>
                    <XMarkIcon class="w-5 h-5" />
                </button>
            </div>
        </form>


        <div v-else class="p-6 flex justify-between space-x-16">

            <span @click="edit" class="w-full h-full truncate" :class="{'line-through': !!task.completed_at}">
                {{ task.title }}
            </span>

            <div class="flex space-x-4">
                <button v-if="!task.deleted_at" @click="toggleTask" class="hover:text-primary transition">
                    <span class="sr-only">{{ task.completed_at ? 'Undo' : 'Complete' }}</span>


                    <ArrowUturnLeftIcon v-if="task.completed_at" class="w-5 h-5 "/>
                    <CheckIcon v-else class="w-5 h-5 "/>

                </button>

                <button v-else @click="restore" title="Restore" class="hover:text-primary transition">
                    <span class="sr-only">
                        Restore
                    </span>
                    <ArrowPathIcon class="w-5 h-5"/>
                </button>

                <span class="text-gray-500">|</span>

                <button @click="deleteTask" title="Delete" class="text-red-500 hover:text-red-400 transition">
                    <span class="sr-only">Delete</span>
                    <TrashIcon class="w-5 h-5"/>
                </button>
            </div>

        </div>

    </div>
</template>


