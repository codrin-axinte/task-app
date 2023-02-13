import {router} from "@inertiajs/vue3";
import useSwal from "@/Composables/useSwal";
import useShakespeare from "@/Composables/useShakespeare";
import axios from "axios";

export default function useTasks() {

    const {toast} = useSwal();
    const {
        createdMessage,
        deletedMessage,
        completedMessage,
        updatedMessage
    } = useShakespeare();


    function editForm(task, options = {}) {
        return axios.get(route('tasks.edit', task));
    }

    function create(form, options = {}) {

        return form.post(route('tasks.index'), {
            preserveScroll: true,
            onSuccess() {
                form.reset();
                toast({
                    message: createdMessage(),
                    type: 'success'
                });
            },
            ...options,
        });
    }

    function update(task, form, options = {}) {
        return form.put(route('tasks.update', task), {
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
            },
            ...options
        })

    }

    function moveToTrash(task, options = {}) {
        return router.delete(route('tasks.destroy', task), {
            ...options,
            preserveScroll: true,
            onSuccess() {
                toast({
                    message: deletedMessage(),
                    type: 'success'
                });
            }
        });
    }

    function toggle(task, options = {}) {
        return router.put(route('tasks.toggle', task), options, {
            preserveScroll: true,
            onSuccess: () => {
                if (!task.completed_at) {
                    toast({
                        message: completedMessage(),
                        type: 'success'
                    });
                }
            }
        });
    }

    function restore(task, options = {}) {
        return router.put(route('tasks.restore', task), options);
    }

    function search(searchQuery, currentFilter) {
        router.get(route('tasks.index'), {searchQuery: searchQuery, filter: currentFilter}, {preserveState: true});
    }

    return {
        editForm,
        create,
        update,
        toggle,
        restore,
        moveToTrash,
        search
    }
}
