<script setup>
import {ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import {TrashIcon, ArrowPathIcon, ClockIcon} from "@heroicons/vue/24/outline"
import {CheckIcon, ArrowUturnLeftIcon, XMarkIcon, HandThumbUpIcon, ListBulletIcon} from "@heroicons/vue/24/solid"
import useTasks from "@/Composables/useTasks";
import QuickEdit from "@/Components/Tasks/QuickEdit.vue";

const props = defineProps({task: Object})

const isEditing = ref(false);

const form = useForm({
    title: '',
    content: null,
})

const {toggle, restore, moveToTrash, update} = useTasks();


function cancel() {
    isEditing.value = false;
}

function edit() {
    form.title = props.task.title;
    isEditing.value = true;
}


</script>

<template>
    <div
        class="bg-base-200 overflow-hidden border border-transparent hover:border-primary shadow-md hover:shadow-xl sm:rounded-lg transition">

        <QuickEdit v-if="isEditing" :task="task" @cancel="cancel"/>

        <div v-else class="p-6 flex justify-between items-center space-x-16">

            <div class="flex flex-col w-full">
                    <span @click="edit" class="w-full h-full truncate" :class="{'line-through': !!task.completed_at}">
                        {{ task.title }}
                    </span>

                <div class="text-gray-400">
                    <p v-if="task.children_count" class="text-sm inline-flex items-center space-x-1">
                        <ListBulletIcon class="w-4 w-4"/>
                        <span>{{ task.children_count }}</span>
                    </p>

                    <p v-if="task.due_date" class="text-sm inline-flex items-center space-x-1">
                        <ClockIcon class="w-4 w-4"/>
                        <span
                            :class="{'text-emerald-400': task.due_days > 1 && task.due_days < 4, 'text-orange-400': task.due_days >=0 &&  task.due_days <= 1}">
                            {{ task.due_date }}
                        </span>
                    </p>
                </div>

            </div>

            <div class="flex space-x-4">
                <button v-if="!task.deleted_at" @click="toggle(task)" class="hover:text-primary transition">
                    <span class="sr-only">{{ task.completed_at ? 'Undo' : 'Complete' }}</span>


                    <ArrowUturnLeftIcon v-if="task.completed_at" class="w-5 h-5 "/>
                    <CheckIcon v-else class="w-5 h-5 "/>

                </button>

                <button v-else @click="restore(task)" title="Restore" class="hover:text-primary transition">
                    <span class="sr-only">
                        Restore
                    </span>
                    <ArrowPathIcon class="w-5 h-5"/>
                </button>

                <span class="text-gray-500">|</span>

                <button @click="moveToTrash(task)" title="Delete" class="text-red-500 hover:text-red-400 transition">
                    <span class="sr-only">Delete</span>
                    <TrashIcon class="w-5 h-5"/>
                </button>
            </div>

        </div>

    </div>
</template>


