<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    metrics: Object,
    activeDrivers: Array,
    pendingDeliveries: Array,
});
</script>

<template>
    <Head title="Driver Supervisor Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Logistics & Driver Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <!-- Metrics -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm font-medium uppercase">Active Drivers</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ metrics.active_drivers.value }}</div>
                        <div class="mt-1 text-sm text-gray-500">
                            of {{ metrics.active_drivers.total }} total
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm font-medium uppercase">Deliveries Today</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ metrics.deliveries_today.value }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm font-medium uppercase">On Time Rate</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ metrics.on_time_rate.value }}%</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm font-medium uppercase">Pending Assignments</div>
                        <div class="mt-2 text-3xl font-bold text-orange-600">{{ metrics.pending_assignments.value }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Active Drivers Map Placeholder -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Driver Status</h3>
                        <div class="space-y-4">
                            <div v-for="driver in activeDrivers" :key="driver.id" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        {{ driver.user?.name?.charAt(0) || 'D' }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ driver.user?.name }}</div>
                                        <div class="text-xs text-gray-500">{{ driver.vehicle_type || 'Car' }} • {{ driver.current_location || 'Unknown' }}</div>
                                    </div>
                                </div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ driver.status }}
                                </span>
                            </div>
                            <div v-if="activeDrivers.length === 0" class="text-center text-gray-500 text-sm py-4">
                                No active drivers currently.
                            </div>
                        </div>
                    </div>

                    <!-- Pending Deliveries -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Pending Deliveries</h3>
                        <div class="overflow-y-auto max-h-96">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr v-for="delivery in pendingDeliveries" :key="delivery.id">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">#{{ delivery.order_number }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ delivery.area || 'City Center' }}</td>
                                        <td class="px-4 py-3 text-right">
                                            <button class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Assign</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
