<script setup>
import {ref, inject, onMounted} from "vue";
import {useForm} from "@inertiajs/vue3";
import {TrashIcon, ArrowPathIcon, PencilIcon, ClockIcon} from "@heroicons/vue/24/outline"
import {CheckIcon, ArrowUturnLeftIcon, ListBulletIcon} from "@heroicons/vue/24/solid"
import useTasks from "@/Composables/useTasks";
import QuickEdit from "@/Components/Tasks/QuickEdit.vue";

const props = defineProps({task: Object})


const isEditing = ref(false);
const emitter = inject('bus');


const form = useForm({
    title: '',
})

const {toggle, restore, moveToTrash, update} = useTasks();


function cancel() {
    isEditing.value = false;
}

function quickEdit() {
    form.title = props.task.title;
    isEditing.value = true;
}


function edit() {
    emitter.emit('task:edit', props.task.id);
}


</script>

<template>

    <div
        class="bg-base-200 overflow-hidden border border-transparent hover:border-primary shadow-md hover:shadow-xl sm:rounded-lg transition">

        <QuickEdit v-if="isEditing" :task="task" @cancel="cancel"/>

        <div v-else class="p-6 flex justify-between items-center space-x-4">

            <div class="flex flex-col w-full">
                    <span @click="quickEdit" class="w-full h-full truncate"
                          :class="{'line-through': !!task.completed_at}">
                        {{ task.title }}
                    </span>

                <div class="flex space-x-2 text-gray-400">
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

            <div class="flex space-x-3">
                <button v-if="!task.deleted_at" @click="toggle(task)" title="Toggle"
                        class="hover:text-primary transition">
                    <span class="sr-only">{{ task.completed_at ? 'Undo' : 'Complete' }}</span>


                    <ArrowUturnLeftIcon v-if="task.completed_at" class="w-5 h-5 "/>
                    <CheckIcon v-else class="w-5 h-5 "/>

                </button>

                <button v-else @click="restore(task)" title="Restore" class="hover:text-primary transition">
                    <span class="sr-only">Restore</span>
                    <ArrowPathIcon class="w-5 h-5"/>
                </button>


                <button @click="edit" title="Edit" class="hover:text-primary transition">
                    <span class="sr-only">Edit</span>
                    <PencilIcon class="w-5 h-5"/>
                </button>

                <span class="text-gray-500">|</span>

                <button @click="moveToTrash(task)" title="Delete"
                        class="text-red-500 hover:text-red-400 transition">
                    <span class="sr-only">Delete</span>
                    <TrashIcon class="w-5 h-5"/>
                </button>
            </div>

        </div>

    </div>
</template>


