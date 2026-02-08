<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    metrics: Object,
    revenueData: Object,
    recentTransactions: Array,
});

const formatCurrency = (amount, currency = 'SAR') => {
    return new Intl.NumberFormat('en-SA', { style: 'currency', currency: currency }).format(amount);
};
</script>

<template>
    <Head title="Finance Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Finance Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <!-- Metrics -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm font-medium uppercase">Total Revenue (Month)</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ metrics.revenue.formatted }}</div>
                        <div class="mt-1 text-sm">
                            <span :class="metrics.revenue.growth > 0 ? 'text-green-600' : 'text-red-600'">
                                {{ metrics.revenue.growth }}%
                            </span>
                            <span class="text-gray-500 ml-1">vs last month</span>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm font-medium uppercase">Expenses</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ metrics.expenses.formatted }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm font-medium uppercase">Net Profit</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ metrics.profit.formatted }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Revenue Chart Placeholder -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Revenue Overview</h3>
                        <div class="h-64 flex items-end justify-between space-x-2 border-b border-l border-gray-200 p-2">
                             <div v-for="(value, index) in revenueData.values" :key="index" class="w-full flex flex-col items-center group relative">
                                <div class="w-full bg-green-500 hover:bg-green-600 transition-all rounded-t"
                                     :style="{ height: `${(value / (Math.max(...revenueData.values) || 1)) * 100}%` }">
                                </div>
                                <span class="text-xs text-gray-500 mt-2">{{ revenueData.labels[index] }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Transactions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Transactions</h3>
                        <div class="overflow-y-auto max-h-64">
                            <ul class="divide-y divide-gray-100">
                                <li v-for="tx in recentTransactions" :key="tx.id" class="py-3 flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ tx.description || 'Transaction #' + tx.id }}</p>
                                        <p class="text-xs text-gray-500">{{ new Date(tx.created_at).toLocaleDateString() }} • {{ tx.type }}</p>
                                    </div>
                                    <div class="font-bold text-sm" :class="tx.type === 'payment' ? 'text-green-600' : 'text-red-600'">
                                        {{ tx.type === 'payment' ? '+' : '-' }} {{ formatCurrency(tx.amount, tx.currency) }}
                                    </div>
                                </li>
                                <li v-if="recentTransactions.length === 0" class="py-4 text-center text-gray-500 text-sm">
                                    No recent transactions.
                                </li>
                            </ul>
                        </div>
                        <div class="mt-4">
                            <button class="w-full text-center text-sm text-indigo-600 hover:text-indigo-900">View All Transactions</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
