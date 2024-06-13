<script setup>
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
    robotId: {
        type: Number,
        default: 0,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    }
});

const emit = defineEmits(['closeModal', 'getRobots']);


function closeModal() {
    emit('closeModal');
}

function createOn() {
    if (props.robotId !== 0) {
        axios.post('/robot/on/' + props.robotId, {'task': document.querySelector("#new_task").value})
            .then((response) => {
                emit('closeModal')
                emit('getRobots')
            })
    }
}
</script>

<template>
    <div class="relative z-10 mx-2" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

                <div
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:items-start">

                            <div class="flex">
                                <div class="">
                                    <img class="flex-auto w-12" src="/images/task.png">
                                </div>
                                <div class="flex-auto mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                    <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Please
                                        enter task for this robot:</h3>
                                    <div class="mt-4">
                                        <!--                                    <InputLabel for="new_task" value="Write new task"/>-->

                                        <TextInput
                                            id="new_task"
                                            type="text"
                                            class="mt-1 block w-full"
                                            required
                                            model-value=""
                                        />
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <SecondaryButton
                            @click="closeModal"
                            class="inline-flex items-center mr-2 mb-4 underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            Cancel
                        </SecondaryButton>

                        <PrimaryButton @click="createOn"
                                       class="mr-2 bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            Send
                        </PrimaryButton>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
