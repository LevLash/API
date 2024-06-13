<script setup>
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
const emit = defineEmits(['closeFeedbackModal']);
const feedbacks = defineModel()

function closeFeedbackModal() {
    emit('closeFeedbackModal');
}
</script>

<template>
    <div class="relative z-10 mx-2" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

                <div
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:items-start">

                            <div class="flex-auto mt-3 text-center sm:mt-0 sm:text-left">

                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-100 uppercase">
                                            №
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-100 uppercase">
                                            Author
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-100 uppercase">
                                            Rating
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-100 uppercase">
                                            Feedback
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr v-for="(feedback, i) in feedbacks" class="">
                                        <td class="px-6 py-4 text-sm text-gray-400 dark:text-gray-600">
                                            {{ i+1 }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-400 dark:text-gray-600">
                                            {{ feedback.robotId.administratorId.firstname }}
                                            {{ feedback.robotId.administratorId.lastname }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-400 dark:text-gray-600">
                                            {{ feedback.rating }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-400 dark:text-gray-600">
                                            {{ feedback.feedback }}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <SecondaryButton
                            @click="closeFeedbackModal"
                            class="inline-flex items-center mr-2 mb-4 underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            OK
                        </SecondaryButton>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
