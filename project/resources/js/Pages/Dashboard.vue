<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import {onBeforeMount, onMounted, ref} from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import NewTaskModal from "@/Components/NewTaskModal.vue";
import FeedbackModal from "@/Components/FeedbackModal.vue";
import NewFeedbackModal from "@/Components/NewFeedbackModal.vue";

const props = defineProps({
    robots: {
        type: Object,
        default: {}
    },
    feedbacks: {
        type: Object,
        default: {}
    },
    showNew: {
        type: Boolean,
        default: true
    },
    showModal: {
        type: Boolean,
        default: false
    },
    showFeedbackModal: {
        type: Boolean,
        default: false
    },
    showNewFeedbackModal: {
        type: Boolean,
        default: false
    },
    currentId: {
        type: Number,
        default: 0
    },
});

let robots = ref(props.robots)
// let feedbacks = ref(props.feedbacks)
let showNew = ref(props.showNew)
let showModal = ref(props.showModal)
let showFeedbackModal = ref(props.showFeedbackModal)
let showNewFeedbackModal = ref(props.showNewFeedbackModal)
let currentId = ref(props.currentId)
const feedbacks = ref({})

getRobots()

function getRobots() {
    axios.get('/robot/list')
        .then((response) => {
            robots.value = response.data
        })
}

function createRobot() {
    showNew = false
    axios.post('/robot')
        .then((response) => {
            getRobots()
            showNew = true
        })
}


function turnOff(id) {
    showNew = false
    axios.post('/robot/off/' + id)
        .then((response) => {
            getRobots()
            showNew = true
        })
}

function viewFeedbackModal(id) {
    axios.get('/robot/feedback/' + id)
        .then((response) => {
            feedbacks.value = response.data
            showFeedbackModal.value = true
        })
}


function closeFeedbackModal() {
    showFeedbackModal.value = false
}

function viewNewFeedbackModal(id) {
    currentId.value = id
    showNewFeedbackModal.value = true
}

function closeNewFeedbackModal() {
    showNewFeedbackModal.value = false
}

function viewModal(id) {
    currentId.value = id
    showModal.value = true
}

function closeModal() {
    showModal.value = false
}

</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="flow-root">
                        <PrimaryButton v-show="showNew" styles="float-right mr-2 mt-2" @click="createRobot">
                            Create Robot
                        </PrimaryButton>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-100 uppercase">ID</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-100 uppercase">Current Task
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-100 uppercase">Status</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-100 uppercase">Last
                                Check-In
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-100 uppercase">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="(robot, i) in robots" class="">
                            <td class="px-6 py-4 text-sm text-gray-400 dark:text-gray-600">
                                {{ robot.id }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400 dark:text-gray-600">
                                {{ robot.currentTask }}
                            </td>
                            <td class="text-center px-6 py-4 text-sm text-gray-400 dark:text-gray-600">
                                {{ robot.status }}
                            </td>
                            <td class="text-center px-6 py-4 text-sm text-gray-400 dark:text-gray-600">
                                {{ (new Date(robot.lastCheckIn)).toUTCString() }}
                            </td>
                            <td class="flex justify-center px-6 py-4 text-sm text-gray-400 dark:text-gray-600">
                                <SecondaryButton @click="viewModal(robot.id)" v-show="(robot.status == 'OFFLINE')">TURN
                                    ON
                                </SecondaryButton>
                                <SecondaryButton @click="turnOff(robot.id)" v-show="(robot.status == 'ONLINE')">TURN
                                    OFF
                                </SecondaryButton>
                                <SecondaryButton @click="viewFeedbackModal(robot.id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/>
                                    </svg>
                                </SecondaryButton>
                                <SecondaryButton @click="viewNewFeedbackModal(robot.id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>

                                </SecondaryButton>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <NewTaskModal v-show="showModal" @closeModal="closeModal" @getRobots="getRobots" :robotId="currentId"/>
        <FeedbackModal v-show="showFeedbackModal" @closeFeedbackModal="closeFeedbackModal"
                       v-model="feedbacks" :robotId="currentId"/>
        <NewFeedbackModal v-show="showNewFeedbackModal" @closeNewFeedbackModal="closeNewFeedbackModal"
                          :robotId="currentId"/>
    </AuthenticatedLayout>
</template>
