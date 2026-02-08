<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    metrics: Object,
    pendingLeaves: Array,
});
</script>

<template>
    <Head title="HR Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Human Resources Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <!-- KPIs -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm font-medium uppercase">Total Employees</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ metrics.total_employees.value }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm font-medium uppercase">Present Today</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ metrics.present_today.value }}</div>
                        <div class="mt-1 text-sm text-gray-500">
                            {{ metrics.present_today.percentage }}% attendance
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm font-medium uppercase">On Leave</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ metrics.on_leave.value }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm font-medium uppercase">Absent Today</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ metrics.absent_today.value }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Pending Leave Requests -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Pending Leave Requests</h3>
                        <div v-if="pendingLeaves.length === 0" class="text-gray-500 text-sm">
                            No pending requests.
                        </div>
                        <ul v-else class="divide-y divide-gray-100">
                            <li v-for="request in pendingLeaves" :key="request.id" class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ request.employee_name }}</p>
                                    <p class="text-xs text-gray-500">{{ request.type }} • {{ request.days }} days</p>
                                </div>
                                <div class="flex space-x-2">
                                    <button class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded hover:bg-green-200">Approve</button>
                                    <button class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded hover:bg-red-200">Reject</button>
                                </div>
                            </li>
                        </ul>
                        <div class="mt-4 text-center">
                            <Link href="#" class="text-sm text-indigo-600 hover:text-indigo-900">View all requests</Link>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">HR Management</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <button class="p-4 border rounded-lg hover:bg-gray-50 text-left">
                                <span class="block font-medium text-gray-900">Add Employee</span>
                                <span class="text-sm text-gray-500">Onboard new staff</span>
                            </button>
                            <button class="p-4 border rounded-lg hover:bg-gray-50 text-left">
                                <span class="block font-medium text-gray-900">Payroll Run</span>
                                <span class="text-sm text-gray-500">Process monthly salaries</span>
                            </button>
                            <button class="p-4 border rounded-lg hover:bg-gray-50 text-left">
                                <span class="block font-medium text-gray-900">Announcements</span>
                                <span class="text-sm text-gray-500">Post internal news</span>
                            </button>
                            <button class="p-4 border rounded-lg hover:bg-gray-50 text-left">
                                <span class="block font-medium text-gray-900">Performance</span>
                                <span class="text-sm text-gray-500">Review cycles</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
