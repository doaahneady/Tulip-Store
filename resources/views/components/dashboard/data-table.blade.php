@props(['title', 'icon' => 'fas fa-table', 'viewAllLink' => null, 'headers' => [], 'emptyMessage' => 'لا توجد بيانات'])

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <!-- Header -->
    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
        <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
            <i class="{{ $icon }} text-primary-500"></i>
            {{ $title }}
        </h3>
        @if($viewAllLink)
            <a href="{{ $viewAllLink }}" class="text-primary-500 hover:text-primary-600 text-sm font-medium flex items-center gap-1">
                عرض الكل <i class="fas fa-arrow-left text-xs"></i>
            </a>
        @endif
    </div>
    
    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            @if(count($headers) > 0)
            <thead class="bg-gray-50 dark:bg-gray-700/50">
                <tr>
                    @foreach($headers as $header)
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            @endif
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                {{ $slot }}
            </tbody>
        </table>
    </div>
    
    @if(trim($slot) === '')
        <div class="p-8 text-center">
            <i class="fas fa-inbox text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
            <p class="text-gray-500 dark:text-gray-400">{{ $emptyMessage }}</p>
        </div>
    @endif
</div>
