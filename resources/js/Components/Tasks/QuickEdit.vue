<script setup>
import {useForm} from "@inertiajs/vue3";
import useTasks from "@/Composables/useTasks";
import {ref, onMounted, inject} from 'vue'
import InputError from "@/Components/InputError.vue";
import {XMarkIcon, HandThumbUpIcon} from "@heroicons/vue/24/solid"
import useSwal from "@/Composables/useSwal";
import useShakespeare from "@/Composables/useShakespeare";

const {toast} = useSwal();
const {updatedMessage} = useShakespeare();

const props = defineProps({
    task: Object
})

const emit = defineEmits(['cancel'])

const editInput = ref(null);
const {update} = useTasks();

const form = useForm({
    title: props.task.title || '',
    content: null,
})


function save() {
    let options = {
        preserveScroll: true,
        onSuccess() {
            form.reset();
            emit('cancel');
            toast({
                message: updatedMessage(),
                type: 'success'
            });
        }
    };

    update(props.task, form, options);
}

onMounted(() => {
    if (editInput) {
        editInput.value.focus();
    }

})

</script>

<template>
    <form @submit.prevent="save" class="flex space-x-4 p-6">
        <input
            @blur="emit('cancel')"
            v-model="form.title"
            name="content"
            ref="editInput"
            placeholder="What needs to be done?"
            class="bg-transparent border-none outline-none ring:focus:outline-none focus:outline-none w-full"/>

        <InputError
            v-if="form.errors.title"
            :message="form.errors.title"
        />

        <div class="flex space-x-4">
            <button type="submit" @click="save" title="Save"
                    class="text-emerald-500 hover:text-emerald-400 transition">
                <span class="sr-only">Save</span>
                <HandThumbUpIcon class="w-5 h-5"/>
            </button>
            <button type="reset" @click="emit('cancel')" title="Cancel"
                    class="text-red-500 hover:text-red-400 transition">
                <span class="sr-only">Cancel</span>
                <XMarkIcon class="w-5 h-5"/>
            </button>
        </div>
    </form>
</template>

