<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    metrics: Object,
    revenueChart: Object,
    filters: Object,
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value);
};
</script>

<template>
    <Head title="Admin Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Admin Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <!-- KPI Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Total Users -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm font-medium uppercase">Total Users</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ metrics.total_users.value }}</div>
                        <div class="mt-1 text-sm">
                            <span :class="metrics.total_users.growth > 0 ? 'text-green-600' : 'text-red-600'">
                                {{ metrics.total_users.growth }}%
                            </span>
                            <span class="text-gray-500 ml-1">vs last month</span>
                        </div>
                    </div>

                    <!-- Total Orders -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm font-medium uppercase">Total Orders</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ metrics.total_orders.value }}</div>
                        <div class="mt-1 text-sm">
                            <span :class="metrics.total_orders.growth > 0 ? 'text-green-600' : 'text-red-600'">
                                {{ metrics.total_orders.growth }}%
                            </span>
                            <span class="text-gray-500 ml-1">vs last month</span>
                        </div>
                    </div>

                    <!-- Revenue -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm font-medium uppercase">Total Revenue</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ metrics.total_revenue.formatted }}</div>
                        <div class="mt-1 text-sm">
                            <span :class="metrics.total_revenue.growth > 0 ? 'text-green-600' : 'text-red-600'">
                                {{ metrics.total_revenue.growth }}%
                            </span>
                            <span class="text-gray-500 ml-1">vs last month</span>
                        </div>
                    </div>

                    <!-- Active Stores -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm font-medium uppercase">Active Stores</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ metrics.active_stores.value }}</div>
                        <div class="mt-1 text-sm text-gray-500">
                            of {{ metrics.active_stores.total }} total stores
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Revenue Trend</h3>
                        <div class="h-64 flex items-end justify-between space-x-2">
                            <!-- Simple Bar Chart Visualization using CSS -->
                            <div v-for="(value, index) in revenueChart.values" :key="index" class="w-full flex flex-col items-center group relative">
                                <div class="w-full bg-indigo-500 hover:bg-indigo-600 transition-all rounded-t"
                                     :style="{ height: `${(value / Math.max(...revenueChart.values)) * 100}%` }">
                                     <div class="opacity-0 group-hover:opacity-100 absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2 pointer-events-none whitespace-nowrap z-10">
                                        {{ formatCurrency(value) }}
                                     </div>
                                </div>
                                <span class="text-xs text-gray-500 mt-2">{{ revenueChart.labels[index] }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <button class="p-4 border rounded-lg hover:bg-gray-50 text-left">
                                <span class="block font-medium text-gray-900">User Management</span>
                                <span class="text-sm text-gray-500">Add, edit, or remove users</span>
                            </button>
                            <button class="p-4 border rounded-lg hover:bg-gray-50 text-left">
                                <span class="block font-medium text-gray-900">System Logs</span>
                                <span class="text-sm text-gray-500">View system activity</span>
                            </button>
                            <button class="p-4 border rounded-lg hover:bg-gray-50 text-left">
                                <span class="block font-medium text-gray-900">Database Backup</span>
                                <span class="text-sm text-gray-500">Create or restore backups</span>
                            </button>
                            <button class="p-4 border rounded-lg hover:bg-gray-50 text-left">
                                <span class="block font-medium text-gray-900">Global Settings</span>
                                <span class="text-sm text-gray-500">Configure system parameters</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
