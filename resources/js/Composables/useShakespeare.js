export default function useShakespeare() {


    function createPlaceholder() {
        return [
            "What steps shall be taken to ensure success?",
            "What's to be done?",
            "What must be done, to thrive in this time?",
            "What shall be done?"
        ].random();
    }

    function createdMessage() {
        return [
            'A new labor hath been added to our toils.',
            'A new endeavor hath been added to our duties!',
            'A task hath been added, with haste it shall be accomplished.'
        ].random();
    }

    function deletedMessage() {
        return [
            'The task, it hath been deleted, its duties now are null and void.',
            'The task, it hath been erased from memory, vanished without a trace.',
            'The task hath been erased, its duties now revoked.'
        ].random()
    }

    function completedMessage() {
        return [
            "The task is done, its objectives met, success doth crown our efforts.",
            "The task is now fulfilled, its objectives met with grand success.",
            "The task is now finished, its obligations fulfilled.",
            "The task is completed, its purpose fulfilled with diligence."
        ].random();
    }

    return {
        createdMessage,
        createPlaceholder,
        deletedMessage,
        completedMessage
    }
}

