@extends('dashboards.layouts.app')

@section('title', 'Admin Downloads & Exports')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Downloads & Exports</h1>
        <p class="text-gray-600">Download system data in CSV or PDF format</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Users Export -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Users Export</h3>
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <p class="text-sm text-gray-600 mb-4">Export all users with their roles and status</p>
            <div class="flex gap-2">
                <a href="{{ route('dashboard.admin.export.users', ['format' => 'csv']) }}" 
                   class="flex-1 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-center text-sm">
                    CSV
                </a>
                <a href="{{ route('dashboard.admin.export.users', ['format' => 'pdf']) }}" 
                   class="flex-1 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-center text-sm">
                    PDF
                </a>
            </div>
        </div>

        <!-- Orders Export -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Orders Export</h3>
                <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <p class="text-sm text-gray-600 mb-4">Export all orders with customer and payment details</p>
            <div class="flex gap-2">
                <a href="{{ route('dashboard.admin.export.orders', ['format' => 'csv']) }}" 
                   class="flex-1 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-center text-sm">
                    CSV
                </a>
                <a href="{{ route('dashboard.admin.export.orders', ['format' => 'pdf']) }}" 
                   class="flex-1 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-center text-sm">
                    PDF
                </a>
            </div>
        </div>

        <!-- Financial Transactions Export -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Financial Transactions</h3>
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-sm text-gray-600 mb-4">Export all financial transactions and payments</p>
            <div class="flex gap-2">
                <a href="{{ route('dashboard.admin.export.financial-transactions', ['format' => 'csv']) }}" 
                   class="flex-1 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-center text-sm">
                    CSV
                </a>
                <a href="{{ route('dashboard.admin.export.financial-transactions', ['format' => 'pdf']) }}" 
                   class="flex-1 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-center text-sm">
                    PDF
                </a>
            </div>
        </div>

        <!-- Products Export -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Products Export</h3>
                <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <p class="text-sm text-gray-600 mb-4">Export all products with inventory and pricing</p>
            <div class="flex gap-2">
                <a href="{{ route('dashboard.admin.export.products', ['format' => 'csv']) }}" 
                   class="flex-1 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-center text-sm">
                    CSV
                </a>
                <a href="{{ route('dashboard.admin.export.products', ['format' => 'pdf']) }}" 
                   class="flex-1 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-center text-sm">
                    PDF
                </a>
            </div>
        </div>

        <!-- Employees Export -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Employees Export</h3>
                <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <p class="text-sm text-gray-600 mb-4">Export all employees with department and role info</p>
            <div class="flex gap-2">
                <a href="{{ route('dashboard.admin.export.employees', ['format' => 'csv']) }}" 
                   class="flex-1 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-center text-sm">
                    CSV
                </a>
                <a href="{{ route('dashboard.admin.export.employees', ['format' => 'pdf']) }}" 
                   class="flex-1 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-center text-sm">
                    PDF
                </a>
            </div>
        </div>

        <!-- Stores Export -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Stores Export</h3>
                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <p class="text-sm text-gray-600 mb-4">Export all stores with owner and commission info</p>
            <div class="flex gap-2">
                <a href="{{ route('dashboard.admin.export.stores', ['format' => 'csv']) }}" 
                   class="flex-1 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-center text-sm">
                    CSV
                </a>
                <a href="{{ route('dashboard.admin.export.stores', ['format' => 'pdf']) }}" 
                   class="flex-1 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-center text-sm">
                    PDF
                </a>
            </div>
        </div>

        <!-- Audit Logs Export -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Audit Logs Export</h3>
                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <p class="text-sm text-gray-600 mb-4">Export complete audit trail of all system actions</p>
            <div class="flex gap-2">
                <a href="{{ route('dashboard.admin.audit-logs.export', ['format' => 'csv']) }}" 
                   class="flex-1 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-center text-sm">
                    CSV
                </a>
                <a href="{{ route('dashboard.admin.audit-logs.export', ['format' => 'pdf']) }}" 
                   class="flex-1 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-center text-sm">
                    PDF
                </a>
            </div>
        </div>

        <!-- System Report -->
        <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Complete System Report</h3>
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <p class="text-sm text-blue-100 mb-4">Download comprehensive system report with all metrics</p>
            <div class="flex gap-2">
                <a href="{{ route('dashboard.admin.export.system-report', ['format' => 'csv']) }}" 
                   class="flex-1 bg-white text-blue-600 hover:bg-blue-50 px-4 py-2 rounded text-center text-sm font-semibold">
                    CSV
                </a>
                <a href="{{ route('dashboard.admin.export.system-report', ['format' => 'pdf']) }}" 
                   class="flex-1 bg-white text-purple-600 hover:bg-purple-50 px-4 py-2 rounded text-center text-sm font-semibold">
                    PDF
                </a>
            </div>
        </div>
    </div>

    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h4 class="font-semibold text-blue-800 mb-2">📥 Download Tips</h4>
        <ul class="text-sm text-blue-700 space-y-1">
            <li>• CSV files are best for data analysis in Excel or Google Sheets</li>
            <li>• PDF files are best for printing and sharing reports</li>
            <li>• Large exports may take a few moments to generate</li>
            <li>• All exports include real-time data from the production database</li>
        </ul>
    </div>
</div>
@endsection

