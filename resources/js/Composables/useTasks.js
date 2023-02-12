import {router} from "@inertiajs/vue3";
import useSwal from "@/Composables/useSwal";
import useShakespeare from "@/Composables/useShakespeare";


export default function useTasks() {

    const {toast} = useSwal();
    const {
        createdMessage,
        deletedMessage,
        completedMessage,
    } = useShakespeare();

    function create(form, options = {}) {

        return form.post(route('tasks.index'), {
            preserveScroll: true,
            onSuccess() {
                form.reset();
                const {toast} = useSwal()

                toast({
                    message: createdMessage(),
                    type: 'success'
                });
            },
            ...options,
        });
    }

    function update(task, form, options = {}) {
        return form.put(route('tasks.update', task), options)

    }

    function moveToTrash(task, options = {}) {
        return router.delete(route('tasks.destroy', task), {
            ...options,
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
            onSuccess: () => {
               if(!task.completed_at) {
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


    return {
        create,
        update,
        toggle,
        restore,
        moveToTrash
    }
}
