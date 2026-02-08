<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    systemMetrics: Object,
    kpiMetrics: Object,
});

const getStatusColor = (status) => {
    switch (status) {
        case 'normal': return 'bg-green-500';
        case 'warning': return 'bg-yellow-500';
        case 'critical': return 'bg-red-500';
        default: return 'bg-gray-500';
    }
};
</script>

<template>
    <Head title="IT Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">IT Infrastructure Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <!-- System Resources -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div v-for="(metric, key) in systemMetrics" :key="key" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div class="text-gray-500 text-sm font-medium uppercase">{{ key.replace('_', ' ') }}</div>
                            <div :class="`h-3 w-3 rounded-full ${getStatusColor(metric.status)}`"></div>
                        </div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">
                            {{ key === 'uptime' ? metric.formatted : metric.value + '%' }}
                        </div>
                        <div v-if="key !== 'uptime'" class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
                            <div class="bg-indigo-600 h-2.5 rounded-full" :style="{ width: metric.value + '%' }"></div>
                        </div>
                    </div>
                </div>

                <!-- KPIs -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm font-medium uppercase">Active Services</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ kpiMetrics.active_services.value }}</div>
                        <div class="mt-1 text-sm text-gray-500">
                            of {{ kpiMetrics.active_services.total }} services running
                        </div>
                    </div>
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm font-medium uppercase">Unresolved Alerts</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ kpiMetrics.unresolved_alerts.value }}</div>
                        <div class="mt-1 text-sm text-red-600 font-bold">
                            {{ kpiMetrics.unresolved_alerts.critical }} Critical
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm font-medium uppercase">Error Rate (24h)</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ kpiMetrics.error_rate.value }}</div>
                        <div class="mt-1 text-sm">
                            <span :class="kpiMetrics.error_rate.growth > 0 ? 'text-red-600' : 'text-green-600'">
                                {{ kpiMetrics.error_rate.growth }}%
                            </span>
                            <span class="text-gray-500 ml-1">vs yesterday</span>
                        </div>
                    </div>
                </div>

                <!-- Tools Grid -->
                 <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Infrastructure Tools</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <button class="flex flex-col items-center justify-center p-6 border rounded-lg hover:bg-gray-50 transition">
                            <span class="text-2xl mb-2">🔒</span>
                            <span class="font-medium text-gray-900">Security Audit</span>
                        </button>
                        <button class="flex flex-col items-center justify-center p-6 border rounded-lg hover:bg-gray-50 transition">
                            <span class="text-2xl mb-2">💾</span>
                            <span class="font-medium text-gray-900">Backups</span>
                        </button>
                        <button class="flex flex-col items-center justify-center p-6 border rounded-lg hover:bg-gray-50 transition">
                            <span class="text-2xl mb-2">📜</span>
                            <span class="font-medium text-gray-900">View Logs</span>
                        </button>
                        <button class="flex flex-col items-center justify-center p-6 border rounded-lg hover:bg-gray-50 transition">
                            <span class="text-2xl mb-2">⚙️</span>
                            <span class="font-medium text-gray-900">Services</span>
                        </button>
                    </div>
                 </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
