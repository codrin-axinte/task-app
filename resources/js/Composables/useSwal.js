import swal from "sweetalert2";

export default function useSwal() {


    function toast({title, message = '', type = null, options = {}}) {
        swal.fire({
            title: title,
            text: message,
            toast: true,
            icon: type,
            position: "bottom-end",
            timerProgressBar: true,
            showConfirmButton: false,
            timer: 3000,
            customClass: {
                popup: '!bg-base-100 !text-base-content',
                confirmButton: '!bg-primary',
            },
            ...options,
        })
    }

    return {
        toast,
    }
}
