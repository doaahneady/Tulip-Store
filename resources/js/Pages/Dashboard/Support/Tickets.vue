<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    tickets: Object,
    filters: Object,
});

const status = ref(props.filters?.status ?? '');
const priority = ref(props.filters?.priority ?? '');
const search = ref(props.filters?.search ?? '');

const applyFilters = () => {
    const params = {};
    if (status.value) params.status = status.value;
    if (priority.value) params.priority = priority.value;
    if (search.value) params.search = search.value;
    router.get('/dashboard/support/tickets', params, { preserveState: true, replace: true });
};
</script>

<template>
    <Head title="Support Tickets" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Support Tickets</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select v-model="status" class="mt-1 block w-full border-gray-300 rounded-md">
                                <option value="">All</option>
                                <option value="open">Open</option>
                                <option value="in_progress">In Progress</option>
                                <option value="waiting_customer">Waiting Customer</option>
                                <option value="resolved">Resolved</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                            <select v-model="priority" class="mt-1 block w-full border-gray-300 rounded-md">
                                <option value="">All</option>
                                <option value="urgent">Urgent</option>
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <input v-model="search" type="text" placeholder="Ticket #, subject, customer"
                                   class="mt-1 block w-full border-gray-300 rounded-md" />
                        </div>
                    </div>
                    <div class="mt-4">
                        <button @click="applyFilters" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                            Apply Filters
                        </button>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="ticket in tickets.data" :key="ticket.id">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">#{{ ticket.ticket_number }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ ticket.subject }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ ticket.user?.name || 'Guest' }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                              :class="{
                                                'bg-red-100 text-red-800': ticket.priority === 'urgent',
                                                'bg-orange-100 text-orange-800': ticket.priority === 'high',
                                                'bg-yellow-100 text-yellow-800': ticket.priority === 'medium',
                                                'bg-green-100 text-green-800': ticket.priority === 'low',
                                              }">
                                            {{ ticket.priority }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 capitalize">
                                            {{ ticket.status.replace('_',' ') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                        <Link :href="`/dashboard/support/tickets/${ticket.id}`" class="text-indigo-600 hover:text-indigo-900">View</Link>
                                    </td>
                                </tr>
                                <tr v-if="tickets.data.length === 0">
                                    <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500">No tickets found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
