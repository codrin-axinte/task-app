<script setup>
import TaskCard from "@/Components/Tasks/TaskCard.vue";

const props = defineProps({tasks: Array})


</script>


<template>
    <TransitionGroup name="list" tag="div" class="flex flex-col space-y-4">
        <div v-for="task in tasks" :key="task.id" class="flex flex-col space-y-4">
            <TaskCard :task="task"/>

            <TaskList
                v-if="task && task.children?.length > 0"
                :tasks="task.children"
                class="pl-6"
            />
        </div>
    </TransitionGroup>
</template>

