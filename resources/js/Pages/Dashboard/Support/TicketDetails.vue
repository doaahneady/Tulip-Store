<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    ticket: Object,
});

const form = useForm({
    message: '',
    is_internal: false,
});

const submitReply = () => {
    form.post(`/dashboard/support/tickets/${props.ticket.id}/reply`, {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('message');
        },
    });
};
</script>

<template>
    <Head :title="`Ticket #${ticket.ticket_number}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ticket #{{ ticket.ticket_number }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <div class="text-sm text-gray-500">Subject</div>
                            <div class="mt-1 font-medium text-gray-900">{{ ticket.subject }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Customer</div>
                            <div class="mt-1 font-medium text-gray-900">{{ ticket.user?.name || 'Guest' }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Assigned To</div>
                            <div class="mt-1 font-medium text-gray-900">
                                {{ ticket.assignedTo ? (ticket.assignedTo.first_name + ' ' + ticket.assignedTo.last_name) : 'Unassigned' }}
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <div class="text-sm text-gray-500">Priority</div>
                            <div class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                      :class="{
                                        'bg-red-100 text-red-800': ticket.priority === 'urgent',
                                        'bg-orange-100 text-orange-800': ticket.priority === 'high',
                                        'bg-yellow-100 text-yellow-800': ticket.priority === 'medium',
                                        'bg-green-100 text-green-800': ticket.priority === 'low',
                                      }">
                                    {{ ticket.priority }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Status</div>
                            <div class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 capitalize">
                                    {{ ticket.status.replace('_',' ') }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Created</div>
                            <div class="mt-1 font-medium text-gray-900">{{ new Date(ticket.created_at).toLocaleString() }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Conversation</h3>
                    <div v-if="ticket.replies?.length === 0" class="text-sm text-gray-500">No messages yet.</div>
                    <ul v-else class="space-y-4">
                        <li v-for="reply in ticket.replies" :key="reply.id" class="border rounded p-4">
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-600">
                                    <span class="font-medium text-gray-900">
                                        {{ reply.author?.name ?? (reply.author?.first_name ? (reply.author.first_name + ' ' + reply.author.last_name) : 'Unknown') }}
                                    </span>
                                    <span class="ml-2 text-xs px-2 py-0.5 rounded"
                                          :class="reply.is_internal ? 'bg-yellow-100 text-yellow-700' : 'bg-indigo-100 text-indigo-700'">
                                        {{ reply.is_internal ? 'Internal Note' : 'Public Reply' }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500">{{ new Date(reply.created_at).toLocaleString() }}</div>
                            </div>
                            <div class="mt-2 text-sm text-gray-800 whitespace-pre-line">{{ reply.message }}</div>
                        </li>
                    </ul>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Add Reply</h3>
                    <form @submit.prevent="submitReply" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                            <textarea v-model="form.message" rows="4" class="mt-1 block w-full border-gray-300 rounded-md"></textarea>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input id="is_internal" type="checkbox" v-model="form.is_internal" class="rounded border-gray-300" />
                            <label for="is_internal" class="text-sm text-gray-700">Internal Note</label>
                        </div>
                        <div>
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Send Reply</button>
                            <Link :href="`/dashboard/support/tickets/${ticket.id}/close`" method="post"
                                  class="ml-2 inline-flex items-center bg-gray-100 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-200">
                                Close Ticket
                            </Link>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
