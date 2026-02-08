<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    kpi: Object,
    priority: Object,
    status: Object,
    satisfaction: Object,
    today: Object,
    assignedToMe: Number,
    urgentTickets: Array,
    performance: Array,
});

const getPriorityColor = (priority) => {
    switch (priority) {
        case 'urgent': return 'text-red-600 bg-red-100';
        case 'high': return 'text-orange-600 bg-orange-100';
        case 'medium': return 'text-yellow-600 bg-yellow-100';
        default: return 'text-green-600 bg-green-100';
    }
};
</script>

<template>
    <Head title="Support Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Customer Support Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm font-medium uppercase">Open Tickets</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ kpi.open_tickets?.value ?? 0 }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm font-medium uppercase">Assigned to Me</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ assignedToMe || 0 }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm font-medium uppercase">Avg Response Time</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ kpi.avg_response_time?.formatted ?? '0 min' }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm font-medium uppercase">Satisfaction</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ satisfaction.satisfaction_percentage ?? 0 }}%</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Priority Breakdown</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Urgent</span>
                                <span class="text-sm font-semibold text-red-600">{{ priority.urgent ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">High</span>
                                <span class="text-sm font-semibold text-orange-600">{{ priority.high ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Medium</span>
                                <span class="text-sm font-semibold text-yellow-600">{{ priority.medium ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Low</span>
                                <span class="text-sm font-semibold text-green-600">{{ priority.low ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Status Distribution</h3>
                        <div class="space-y-2">
                            <div v-for="(count, key) in status" :key="key">
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span class="capitalize">{{ key.replace('_', ' ') }}</span>
                                    <span class="font-semibold">{{ count }}</span>
                                </div>
                                <div class="w-full bg-gray-100 h-2 rounded">
                                    <div class="h-2 rounded bg-indigo-600" :style="{ width: `${Math.min(100, (count / (kpi.open_tickets?.value || 1)) * 100)}%` }"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Today's Activity</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Created</span>
                                <span class="text-sm font-semibold">{{ today.created }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Resolved</span>
                                <span class="text-sm font-semibold text-green-600">{{ today.resolved }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Escalated</span>
                                <span class="text-sm font-semibold text-orange-600">{{ today.escalated }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Urgent Attention Required</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="ticket in urgentTickets" :key="ticket.id">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">#{{ ticket.ticket_number }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ ticket.subject }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ ticket.user?.name || 'Guest' }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                {{ ticket.status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                            <Link :href="`/dashboard/support/tickets/${ticket.id}`" class="text-indigo-600 hover:text-indigo-900">View</Link>
                                        </td>
                                    </tr>
                                    <tr v-if="urgentTickets.length === 0">
                                        <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">No urgent tickets. Great job!</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">My Performance</h3>
                        <div v-if="performance.length > 0">
                            <div class="mb-4">
                                <div class="text-sm text-gray-500">Assigned to Me</div>
                                <div class="text-2xl font-bold">{{ assignedToMe }}</div>
                            </div>
                             <div class="mb-4">
                                <div class="text-sm text-gray-500">Resolved</div>
                                <div class="text-2xl font-bold text-green-600">{{ performance[0].resolved_count }}</div>
                            </div>
                            <div class="mb-4">
                                <div class="text-sm text-gray-500">Avg Response Time</div>
                                <div class="text-2xl font-bold">{{ performance[0].avg_response_time }}</div>
                            </div>
                        </div>
                        <div class="mt-6">
                            <Link href="/dashboard/support/tickets" class="block text-center w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                                Go to Ticket Queue
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
