<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Order
                </h2>
                <p class="text-sm text-gray-600 mt-1">{{ $order->order_number }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <span
                    class="px-3 py-1 rounded-full text-sm font-medium {{ $order->getStatusBadgeClass() }}">
                    {{ $order->status }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ tab: '{{ session('active_tab', 'info') }}' }">

            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-6 relative" role="alert">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-emerald-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
                        <button @click="show = false" class="absolute top-2 right-2 text-emerald-600 hover:text-emerald-900 rounded focus:outline-none" aria-label="Close">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Progress Bar Status Order -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Progress Status Order</h3>
                    @php
                        $stepNumber = \App\Models\Order::getProgressIndex($order->status) + 1;
                        $totalSteps = count(\App\Models\Order::PROGRESS_STATUSES);
                    @endphp
                    <span class="text-sm text-gray-500">Step {{ $stepNumber }} dari {{ $totalSteps }}</span>
                </div>
            
                <div class="relative px-6">
                    @php
                        $statuses = \App\Models\Order::PROGRESS_STATUSES;
                        $statusLabels = ['Draft', 'Menunggu', 'Produksi', 'Selesai', 'Dikirim', 'Closed'];
                        $currentIndex = \App\Models\Order::getProgressIndex($order->status);
                        $totalSteps = count($statuses);
                    @endphp
            
                    <!-- Steps Container using CSS Grid for perfect alignment -->
                    <div class="grid grid-cols-6 gap-2 relative">
                        <!-- Progress Line Background -->
                        <div class="absolute top-4 left-4 right-4 h-0.5 bg-gray-200 z-10"></div>
            
                        <!-- Active Progress Line -->
                        @php
                            $progressPercentage = $currentIndex > 0 ? (($currentIndex) / ($totalSteps - 1)) * 100 : 0;
                        @endphp
                        <div class="absolute top-4 left-4 h-0.5 bg-emerald-500 z-10 transition-all duration-500 ease-in-out"
                             style="width: {{ $progressPercentage }}%; max-width: calc(100% - 32px);"></div>
            
                        @foreach ($statuses as $index => $status)
                            @php
                                $circleClass = 'w-8 h-8 mx-auto rounded-full flex items-center justify-center text-xs font-semibold border-2 relative z-20';
                                if ($index <= $currentIndex) {
                                    if ($index == $currentIndex) {
                                        $circleClass .= ' bg-blue-600 text-white border-blue-600 ring-2 ring-blue-100';
                                    } else {
                                        $circleClass .= ' bg-emerald-500 text-white border-emerald-500';
                                    }
                                } else {
                                    $circleClass .= ' bg-white text-gray-400 border-gray-300';
                                }
                                $label = $statusLabels[$index] ?? $status;
                            @endphp
            
                            <div class="flex flex-col items-center relative">
                                <div class="{{ $circleClass }}">
                                    @if ($index < $currentIndex)
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </div>
            
                                <div class="text-center mt-1 w-full">
                                    <span class="text-[10px] text-gray-600 font-medium block truncate" title="{{ $status }}">{{ $label }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
            
                    <!-- Alternative: Full text with fixed height container -->
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Status saat ini:</span>
                            <span class="text-blue-600 font-semibold">{{ $order->status }}</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Progress Status Order</h3>
                        <p class="text-sm text-gray-500 mt-1">Track your order progress in real-time</p>
                    </div>
                    @php
                        $stepNumber = \App\Models\Order::getProgressIndex($order->status) + 1;
                        $totalSteps = count(\App\Models\Order::PROGRESS_STATUSES);
                    @endphp
                    <div class="text-right">
                        <span class="text-sm text-gray-500">Step {{ $stepNumber }} of {{ $totalSteps }}</span>
                        <div class="w-16 bg-gray-200 rounded-full h-1.5 mt-1">
                            <div class="bg-gradient-to-r from-emerald-500 to-blue-500 h-1.5 rounded-full transition-all duration-500" 
                                 style="width: {{ ($stepNumber / $totalSteps) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
            
                <div class="relative px-4 py-3">
                    @php
                        $statuses = \App\Models\Order::PROGRESS_STATUSES;
                        $statusLabels = [
                            'Draft' => 'Draft',
                            'Menunggu' => 'Waiting',
                            'Produksi' => 'Production',
                            'Selesai' => 'Completed',
                            'Dikirim' => 'Shipped',
                            'Closed' => 'Closed'
                        ];
                        $statusIcons = [
                            'Draft' => 'document-text',
                            'Menunggu' => 'clock',
                            'Produksi' => 'cog-6-tooth',
                            'Selesai' => 'check-circle',
                            'Dikirim' => 'truck',
                            'Closed' => 'check-circle'
                        ];
                        $statusColors = [
                            'Draft' => 'bg-gray-100 text-gray-600 border-gray-300',
                            'Menunggu' => 'bg-amber-100 text-amber-600 border-amber-300',
                            'Produksi' => 'bg-blue-100 text-blue-600 border-blue-300',
                            'Selesai' => 'bg-emerald-100 text-emerald-600 border-emerald-300',
                            'Dikirim' => 'bg-purple-100 text-purple-600 border-purple-300',
                            'Closed' => 'bg-green-100 text-green-600 border-green-300'
                        ];
                        $currentIndex = \App\Models\Order::getProgressIndex($order->status);
                        $totalSteps = count($statuses);
                    @endphp
            
                    <!-- Progress Line Background - tepat di tengah lingkaran -->
                    <div class="absolute top-5 left-5 right-5 h-0.5 bg-gray-200 z-10"></div>
            
                    <!-- Active Progress Line with Gradient -->
                    @php
                        // Hitung persentase progress yang tepat
                        if ($currentIndex <= 0) {
                            $progressPercentage = 0;
                        } else {
                            // Progress dari step pertama ke step saat ini
                            $progressPercentage = ($currentIndex / ($totalSteps - 1)) * 100;
                        }
                    @endphp
                    
                    @if($progressPercentage > 0)
                        <div class="absolute top-5 left-5 h-0.5 bg-gradient-to-r from-emerald-500 to-blue-500 z-10 transition-all duration-700 ease-out shadow-sm"
                             style="width: {{ $progressPercentage }}%;">
                            <!-- Animated Pulse Effect -->
                            @if($currentIndex > 0 && $currentIndex < $totalSteps - 1)
                                <div class="absolute right-0 top-0 w-2 h-0.5 bg-white opacity-75 animate-pulse"></div>
                            @endif
                        </div>
                    @endif
            
                    <!-- Steps Container -->
                    <div class="grid grid-cols-6 gap-2 relative z-20">
                        @foreach ($statuses as $index => $status)
                            @php
                                $isCompleted = $index < $currentIndex;
                                $isCurrent = $index == $currentIndex;
                                $isPending = $index > $currentIndex;
                                
                                if ($isCompleted) {
                                    $circleClass = 'w-10 h-10 mx-auto rounded-full flex items-center justify-center text-white bg-gradient-to-br from-emerald-500 to-emerald-600 border-2 border-emerald-300 shadow-md transform scale-100 transition-all duration-300';
                                    $iconClass = 'w-5 h-5 text-white';
                                } elseif ($isCurrent) {
                                    $circleClass = 'w-10 h-10 mx-auto rounded-full flex items-center justify-center text-white bg-gradient-to-br from-blue-500 to-blue-600 border-2 border-blue-300 shadow-md ring-3 ring-blue-100 transform scale-105 transition-all duration-300';
                                    $iconClass = 'w-5 h-5 text-white animate-pulse';
                                } else {
                                    $circleClass = 'w-10 h-10 mx-auto rounded-full flex items-center justify-center bg-gray-50 border-2 border-gray-200 text-gray-400 transition-all duration-300 hover:bg-gray-100';
                                    $iconClass = 'w-4 h-4 text-gray-400';
                                }
                                
                                $label = $statusLabels[$status] ?? $status;
                                $icon = $statusIcons[$status] ?? 'question-mark-circle';
                            @endphp
            
                            <div class="flex flex-col items-center group">
                                <!-- Status Circle with Icon -->
                                <div class="{{ $circleClass }}">
                                    @if ($isCompleted)
                                        <!-- Checkmark for completed steps -->
                                        <svg class="{{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @else
                                        <!-- Heroicon for current/pending steps -->
                                        @switch($icon)
                                            @case('document-text')
                                                <svg class="{{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                @break
                                            @case('clock')
                                                <svg class="{{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                @break
                                            @case('cog-6-tooth')
                                                <svg class="{{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                @break
                                            @case('check-circle')
                                                <svg class="{{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                @break
                                            @case('truck')
                                                <svg class="{{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM21 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M17 17a2 2 0 104 0"></path>
                                                </svg>
                                                @break
                                            @default
                                                <svg class="{{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                        @endswitch
                                    @endif
                                </div>
            
                                <!-- Status Label with Better Typography -->
                                <div class="text-center mt-2 w-full">
                                    <span class="text-xs font-medium block
                                        {{ $isCompleted ? 'text-emerald-600' : ($isCurrent ? 'text-blue-600' : 'text-gray-500') }}
                                        group-hover:text-gray-700 transition-colors duration-200" 
                                        title="{{ $status }}">
                                        {{ $label }}
                                    </span>
                                    
                                    @if ($isCurrent)
                                        <div class="flex items-center justify-center mt-1">
                                            <div class="w-1 h-1 bg-blue-500 rounded-full animate-ping"></div>
                                            <div class="w-1 h-1 bg-blue-500 rounded-full animate-ping mx-1" style="animation-delay: 0.2s"></div>
                                            <div class="w-1 h-1 bg-blue-500 rounded-full animate-ping" style="animation-delay: 0.4s"></div>
                                        </div>
                                    @elseif ($isCompleted)
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                ✓
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
            
                    <!-- Current Status Info with Enhanced Design -->
                    <div class="mt-6 p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                                <div>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">Status:</span>
                                        <span class="text-blue-600 font-semibold ml-1">{{ $order->status }}</span>
                                    </p>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        Updated: {{ now()->format('M d, h:i A') }}
                                    </p>
                                </div>
                            </div>
                            
                            @if ($currentIndex < $totalSteps - 1)
                                @php $nextStatus = $statuses[$currentIndex + 1] ?? null; @endphp
                                @if ($nextStatus)
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500">Next</p>
                                        <p class="text-sm font-medium text-gray-700">{{ $statusLabels[$nextStatus] ?? $nextStatus }}</p>
                                    </div>
                                @endif
                            @else
                                <div class="flex items-center space-x-1">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-green-600">Complete!</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Navigation Tabs -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6">
                <nav class="flex space-x-1 p-2" aria-label="Tabs">
                    <button @click="tab = 'info'"
                        :class="{ 'bg-blue-50 text-blue-700 border-blue-200': tab === 'info', 'text-gray-500 hover:text-gray-700 hover:bg-gray-50': tab !== 'info' }"
                        class="flex-1 px-4 py-3 text-sm font-medium rounded-xl border-2 transition-all duration-200">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Info Order</span>
                        </div>
                    </button>
                    <button @click="tab = 'pembelian'"
                        :class="{ 'bg-blue-50 text-blue-700 border-blue-200': tab === 'pembelian', 'text-gray-500 hover:text-gray-700 hover:bg-gray-50': tab !== 'pembelian' }"
                        class="flex-1 px-4 py-3 text-sm font-medium rounded-xl border-2 transition-all duration-200">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span>Pembelian</span>
                        </div>
                    </button>
                    <button @click="tab = 'biaya'"
                        :class="{ 'bg-blue-50 text-blue-700 border-blue-200': tab === 'biaya', 'text-gray-500 hover:text-gray-700 hover:bg-gray-50': tab !== 'biaya' }"
                        class="flex-1 px-4 py-3 text-sm font-medium rounded-xl border-2 transition-all duration-200">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                </path>
                            </svg>
                            <span>Biaya Produksi</span>
                        </div>
                    </button>
                    <button @click="tab = 'pemasukan'"
                        :class="{ 'bg-blue-50 text-blue-700 border-blue-200': tab === 'pemasukan', 'text-gray-500 hover:text-gray-700 hover:bg-gray-50': tab !== 'pemasukan' }"
                        class="flex-1 px-4 py-3 text-sm font-medium rounded-xl border-2 transition-all duration-200">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                </path>
                            </svg>
                            <span>Pemasukan</span>
                        </div>
                    </button>
                    <button @click="tab = 'invoice'"
                        :class="{ 'bg-blue-50 text-blue-700 border-blue-200': tab === 'invoice', 'text-gray-500 hover:text-gray-700 hover:bg-gray-50': tab !== 'invoice' }"
                        class="flex-1 px-4 py-3 text-sm font-medium rounded-xl border-2 transition-all duration-200">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <span>Invoice</span>
                        </div>
                    </button>
                    <button @click="tab = 'ringkasan'"
                        :class="{ 'bg-blue-50 text-blue-700 border-blue-200': tab === 'ringkasan', 'text-gray-500 hover:text-gray-700 hover:bg-gray-50': tab !== 'ringkasan' }"
                        class="flex-1 px-4 py-3 text-sm font-medium rounded-xl border-2 transition-all duration-200">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                            <span>Ringkasan</span>
                        </div>
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div x-show="tab === 'info'" class="space-y-6">
                <!-- Info Cards -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Customer & Product Info -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Informasi Customer & Produk</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-600">Customer</span>
                                <span
                                    class="text-sm text-gray-900 font-medium">{{ $order->customer->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-600">Produk</span>
                                <span class="text-sm text-gray-900 font-medium">{{ $order->product_name }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-600">Spesifikasi</span>
                                <span class="text-sm text-gray-900">{{ $order->product_specification ?: '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3">
                                <span class="text-sm font-medium text-gray-600">Jumlah</span>
                                <span class="text-sm text-gray-900 font-medium">{{ $order->quantity }} pcs</span>
                            </div>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Detail Order</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-600">Tanggal Order</span>
                                <span
                                    class="text-sm text-gray-900 font-medium">{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-600">Deadline</span>
                                <span class="text-sm text-gray-900 font-medium">
                                    {{ $order->deadline ? \Carbon\Carbon::parse($order->deadline)->format('d M Y') : '-' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-3">
                                <span class="text-sm font-medium text-gray-600">Status</span>
                                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $order->getStatusBadgeClass() }}">
                                    {{ $order->status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Cards -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Update Status -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Update Status Order</h3>
                        </div>

                        <form action="{{ route('orders.updateStatus', $order) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status Baru</label>
                                <select name="status"
                                    class="w-full border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    @foreach (\App\Models\Order::STATUSES as $status)
                                        <option value="{{ $status }}"
                                            {{ $order->status == $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit"
                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                Update Status
                            </button>
                        </form>
                    </div>

                    <!-- Update Price -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Update Harga Jual</h3>
                        </div>

                        <form action="{{ route('orders.updatePrice', $order) }}" method="POST" class="space-y-4" onsubmit="return validatePriceForm(this)">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="current_tab" value="info">
                            <input type="hidden" name="timestamp" value="{{ time() }}">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Harga per Unit</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                    <input type="text" name="total_price" id="total_price_input" value="{{ $order->total_price ? number_format($order->total_price, 0, ',', '.') : '' }}"
                                        class="w-full pl-12 pr-4 py-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="0" oninput="formatNumber(this)">
                                </div>
                            </div>
                            <button type="submit"
                                class="w-full bg-amber-600 text-white px-4 py-2 rounded-xl hover:bg-amber-700 focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-colors">
                                Update Harga
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div x-show="tab === 'pembelian'" class="space-y-6">
                <!-- Data Table Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Daftar Pembelian Material</h3>
                        </div>
                        @if ($order->purchases->count() > 0)
                            <div class="bg-blue-50 px-4 py-2 rounded-xl">
                                <p class="text-sm font-semibold text-blue-900">
                                    Total: Rp
                                    {{ number_format($order->purchases->sum(function ($purchase) {return $purchase->quantity * $purchase->price;}),0,',','.') }}
                                </p>
                            </div>
                        @endif
                    </div>

                    @if ($order->purchases->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th
                                            class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Material</th>
                                        <th
                                            class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Supplier</th>
                                        <th
                                            class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Jumlah</th>
                                        <th
                                            class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Harga</th>
                                        <th
                                            class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Total</th>
                                        <th
                                            class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($order->purchases as $purchase)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="py-4 px-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $purchase->material_name }}</div>
                                            </td>
                                            <td class="py-4 px-4">
                                                <div class="text-sm text-gray-600">{{ $purchase->supplier }}</div>
                                            </td>
                                            <td class="py-4 px-4">
                                                <div class="text-sm text-gray-900">{{ number_format($purchase->quantity, 0, ',', '.') }}</div>
                                            </td>
                                            <td class="py-4 px-4">
                                                <div class="text-sm text-gray-900">Rp
                                                    {{ number_format($purchase->price, 0, ',', '.') }}</div>
                                            </td>
                                            <td class="py-4 px-4">
                                                <div class="text-sm font-medium text-gray-900">Rp
                                                    {{ number_format($purchase->quantity * $purchase->price, 0, ',', '.') }}
                                                </div>
                                            </td>
                                            <td class="py-4 px-4">
                                                <form action="{{ route('purchases.destroy', $purchase) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus pembelian material ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="current_tab" value="pembelian">
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div
                                class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-lg font-medium">Belum ada data pembelian</p>
                            <p class="text-gray-400 text-sm mt-1">Tambahkan pembelian material pertama untuk order ini
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Add Purchase Form Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Tambah Pembelian Material</h3>
                    </div>

                    <form action="{{ route('purchases.store', $order) }}" method="POST"
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Material</label>
                            <input type="text" name="material_name"
                                class="w-full border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
                            <input type="text" name="supplier"
                                class="w-full border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                            <input type="text" name="quantity"
                                class="w-full border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="0" required oninput="formatNumber(this)" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Harga per Unit</label>
                            <div class="relative">
                                <span
                                    class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                <input type="text" name="price"
                                    class="w-full pl-12 pr-4 py-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="0" required oninput="formatNumber(this)" />
                            </div>
                        </div>
                        {{-- <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Foto Nota (Opsional)</label>
                            <input type="file" name="receipt_photo" accept="image/*"
                                class="w-full border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
                        </div> --}}
                        <div class="md:col-span-2 lg:col-span-4">
                            <button type="submit"
                                class="w-full bg-emerald-600 text-white px-6 py-3 rounded-xl hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-colors font-medium">
                                Tambah Pembelian
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div x-show="tab === 'biaya'" class="space-y-6">
                <!-- Data Table Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Daftar Biaya Produksi</h3>
                        </div>
                        @if ($order->productionCosts->count() > 0)
                            <div class="bg-purple-50 px-4 py-2 rounded-xl">
                                <p class="text-sm font-semibold text-purple-900">
                                    Total: Rp {{ number_format($order->productionCosts->sum('amount'), 0, ',', '.') }}
                                </p>
                            </div>
                        @endif
                    </div>

                    @if ($order->productionCosts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th
                                            class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Jenis Biaya</th>
                                        <th
                                            class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Deskripsi</th>
                                        <th
                                            class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Jumlah</th>
                                        <th
                                            class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($order->productionCosts as $cost)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="py-4 px-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $cost->type }}
                                                </div>
                                            </td>
                                            <td class="py-4 px-4">
                                                <div class="text-sm text-gray-600">{{ $cost->description ?: '-' }}
                                                </div>
                                            </td>
                                            <td class="py-4 px-4">
                                                <div class="text-sm font-medium text-gray-900">Rp
                                                    {{ number_format($cost->amount, 0, ',', '.') }}</div>
                                            </td>
                                            <td class="py-4 px-4">
                                                <form action="{{ route('costs.destroy', $cost) }}" method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus biaya produksi ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="current_tab" value="biaya">
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div
                                class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-lg font-medium">Belum ada data biaya produksi</p>
                            <p class="text-gray-400 text-sm mt-1">Tambahkan biaya produksi pertama untuk order ini</p>
                        </div>
                    @endif
                </div>

                <!-- Add Production Cost Form Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Tambah Biaya Produksi</h3>
                    </div>

                    <form action="{{ route('costs.store', $order) }}" method="POST"
                        class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Biaya</label>
                            <select name="type"
                                class="w-full border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                required>
                                <option value="">-- Pilih --</option>
                                <option value="Tenaga Kerja">Tenaga Kerja</option>
                                <option value="Overhead">Overhead</option>
                                <option value="Transportasi">Transportasi</option>
                                <option value="Biaya Pengiriman">Biaya Pengiriman</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <input type="text" name="description"
                                class="w-full border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Deskripsi biaya (opsional)" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                            <div class="relative">
                                <span
                                    class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                <input type="text" name="amount"
                                    class="w-full pl-12 pr-4 py-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="0" required oninput="formatNumber(this)" />
                            </div>
                        </div>
                        <div class="md:col-span-3">
                            <button type="submit"
                                class="w-full bg-purple-600 text-white px-6 py-3 rounded-xl hover:bg-purple-700 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors font-medium">
                                Tambah Biaya Produksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div x-show="tab === 'pemasukan'" class="space-y-6">
                <!-- Data Table Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Daftar Pemasukan</h3>
                        </div>
                        @if ($order->incomes->count() > 0)
                            <div class="bg-emerald-50 px-4 py-2 rounded-xl">
                                <p class="text-sm font-semibold text-emerald-900">
                                    Total: Rp {{ number_format($order->incomes->sum('amount'), 0, ',', '.') }}
                                </p>
                            </div>
                        @endif
                    </div>

                    @if ($order->incomes->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th
                                            class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Jenis Pemasukan</th>
                                        <th
                                            class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Tanggal</th>
                                        <th
                                            class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Jumlah</th>
                                        <th
                                            class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($order->incomes as $income)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="py-4 px-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $income->type }}
                                                </div>
                                            </td>
                                            <td class="py-4 px-4">
                                                <div class="text-sm text-gray-600">
                                                    {{ \Carbon\Carbon::parse($income->date)->format('d M Y') }}</div>
                                            </td>
                                            <td class="py-4 px-4">
                                                <div class="text-sm font-medium text-emerald-900">Rp
                                                    {{ number_format($income->amount, 0, ',', '.') }}</div>
                                            </td>
                                            <td class="py-4 px-4">
                                                <form action="{{ route('incomes.destroy', $income) }}" method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus pemasukan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div
                                class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-lg font-medium">Belum ada data pemasukan</p>
                            <p class="text-gray-400 text-sm mt-1">Tambahkan pemasukan pertama untuk order ini</p>
                        </div>
                    @endif
                </div>

                <!-- Add Income Form Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Tambah Pemasukan</h3>
                    </div>

                    <form action="{{ route('incomes.store', $order) }}" method="POST"
                        class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pemasukan</label>
                            <select name="type"
                                class="w-full border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                required>
                                <option value="">-- Pilih --</option>
                                <option value="DP">DP</option>
                                <option value="Cicilan">Cicilan</option>
                                <option value="Lunas">Lunas</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                            <input type="date" name="date"
                                class="w-full border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                            <div class="relative">
                                <span
                                    class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                <input type="text" name="amount"
                                    class="w-full pl-12 pr-4 py-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="0" required oninput="formatNumber(this)" />
                            </div>
                        </div>

                        <!-- Payment Summary -->
                        <div class="md:col-span-3">
                            <div class="bg-gray-50 rounded-xl p-4 mb-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Total Harga Jual:</span>
                                        <span class="font-medium text-gray-900">Rp
                                            {{ number_format(($order->total_price ?? 0) * ($order->quantity ?? 1), 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Total Pemasukan:</span>
                                        <span class="font-medium text-emerald-600">Rp
                                            {{ number_format($order->incomes->sum('amount'), 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Sisa Pembayaran:</span>
                                        @php
                                            $sisaBayarClass = 'font-medium';
                                            $sisaBayarAmount =
                                                ($order->total_price ?? 0) * ($order->quantity ?? 1) -
                                                $order->incomes->sum('amount');
                                            if ($sisaBayarAmount <= 0) {
                                                $sisaBayarClass .= ' text-emerald-600';
                                            } else {
                                                $sisaBayarClass .= ' text-red-600';
                                            }
                                        @endphp
                                        <span class="{{ $sisaBayarClass }}">
                                            Rp {{ number_format($sisaBayarAmount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-3">
                            <button type="submit"
                                class="w-full bg-emerald-600 text-white px-6 py-3 rounded-xl hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-colors font-medium">
                                Tambah Pemasukan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div x-show="tab === 'invoice'" class="space-y-6">
                <!-- Invoice List Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Daftar Invoice</h3>
                        </div>
                    </div>

                    @if ($order->invoices->count() > 0)
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            @foreach ($order->invoices as $invoice)
                                <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h4 class="font-semibold text-lg text-gray-900">
                                                {{ $invoice->invoice_number }}</h4>
                                            <div class="flex items-center space-x-4 text-sm text-gray-600 mt-1">
                                                <span>Tanggal:
                                                    {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</span>
                                                <span>Jatuh Tempo:
                                                    {{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</span>
                                            </div>
                                        </div>
                                        @php
                                            $invoiceStatusClass = 'px-3 py-1 rounded-full text-sm font-medium';
                                            if ($invoice->status === 'Paid') {
                                                $invoiceStatusClass .= ' bg-emerald-100 text-emerald-800';
                                            } elseif ($invoice->status === 'Overdue') {
                                                $invoiceStatusClass .= ' bg-red-100 text-red-800';
                                            } elseif ($invoice->status === 'Sent') {
                                                $invoiceStatusClass .= ' bg-blue-100 text-blue-800';
                                            } else {
                                                $invoiceStatusClass .= ' bg-gray-100 text-gray-800';
                                            }
                                        @endphp
                                        <span class="{{ $invoiceStatusClass }}">
                                            {{ $invoice->status }}
                                        </span>
                                    </div>

                                    <div class="mb-4">
                                        <p class="text-2xl font-bold text-emerald-600">
                                            Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <div class="flex space-x-2">
                                        <a href="{{ route('invoices.show', $invoice) }}"
                                            class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors text-sm font-medium text-center">
                                            Detail
                                        </a>
                                        @if ($invoice->status !== 'Paid')
                                            <a href="{{ route('invoices.download', $invoice) }}"
                                                class="flex-1 bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-colors text-sm font-medium text-center">
                                                Download
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div
                                class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-lg font-medium mb-4">Belum ada invoice untuk order ini</p>
                            @if ($order->isInvoiceAllowed())
                                <form action="{{ route('invoices.generate', $order) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium">
                                        Generate Invoice
                                    </button>
                                </form>
                            @else
                                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 max-w-md mx-auto">
                                    <p class="text-sm text-amber-800">
                                        Invoice dapat dibuat setelah order selesai dan harga jual ditentukan.
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Invoice Info Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Invoice</h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-blue-50 p-4 rounded-xl">
                            <h4 class="font-semibold text-blue-900 mb-3">Cara Kerja Invoice:</h4>
                            <ul class="text-sm text-blue-800 space-y-2">
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    Invoice dibuat otomatis setelah order selesai
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    Nomor invoice: INV-YYYYMMDD-XXXX
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    Jatuh tempo default: 30 hari
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    Status: Draft → Sent → Paid
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    Invoice dapat didownload sebagai PDF
                                </li>
                            </ul>
                        </div>

                        @if ($order->total_price)
                            <div class="bg-emerald-50 p-4 rounded-xl">
                                <h4 class="font-semibold text-emerald-900 mb-3">Harga Jual:</h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-emerald-700">Harga per Unit:</span>
                                        <span class="text-lg font-bold text-emerald-800">
                                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-emerald-700">Quantity:</span>
                                        <span class="font-medium text-emerald-800">{{ $order->quantity }} pcs</span>
                                    </div>
                                    <hr class="border-emerald-200">
                                    <div class="flex justify-between">
                                        <span class="text-sm font-semibold text-emerald-700">Total:</span>
                                        <span class="text-xl font-bold text-emerald-800">
                                            Rp {{ number_format($order->total_price * $order->quantity, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-gray-50 p-4 rounded-xl">
                                <h4 class="font-semibold text-gray-700 mb-2">Harga Jual Belum Ditentukan</h4>
                                <p class="text-sm text-gray-600">
                                    Silakan update harga jual di tab Info Order terlebih dahulu sebelum membuat invoice.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div x-show="tab === 'ringkasan'" class="space-y-6">
                <!-- Financial Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @php
                        $totalPembelian = $order->purchases->sum(function ($purchase) {
                            return $purchase->quantity * $purchase->price;
                        });
                        $totalBiayaProduksi = $order->productionCosts->sum('amount');
                        $totalHPP = $totalPembelian + $totalBiayaProduksi;
                        $totalHargaJual = ($order->total_price ?? 0) * ($order->quantity ?? 1);
                        $totalPemasukan = $order->incomes->sum('amount');
                        $totalMargin = $totalHargaJual - $totalHPP;
                        $sisaBayar = $totalHargaJual - $totalPemasukan;

                        // Detailed HPP breakdown
                        $purchaseBreakdown = [];
                        foreach ($order->purchases as $purchase) {
                            $label = 'Material: ' . $purchase->material_name;
                            $purchaseBreakdown[$label] = ($purchaseBreakdown[$label] ?? 0) + ($purchase->quantity * $purchase->price);
                        }
                        $costBreakdown = [];
                        foreach ($order->productionCosts as $cost) {
                            $detail = trim($cost->description ?? '') !== '' ? $cost->description : $cost->type;
                            $label = 'Biaya: ' . $detail;
                            $costBreakdown[$label] = ($costBreakdown[$label] ?? 0) + $cost->amount;
                        }
                        $hppBreakdownLabels = array_keys(array_merge($purchaseBreakdown, $costBreakdown));
                        $hppBreakdownValues = array_values(array_merge($purchaseBreakdown, $costBreakdown));
                    @endphp

                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <h4 class="text-sm font-semibold text-blue-800">Total Pembelian Material</h4>
                        </div>
                        <p class="text-2xl font-bold text-blue-900">Rp
                            {{ number_format($totalPembelian, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-2xl p-6 border border-red-200">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                            </div>
                            <h4 class="text-sm font-semibold text-red-800">Total Biaya Produksi</h4>
                        </div>
                        <p class="text-2xl font-bold text-red-900">Rp
                            {{ number_format($totalBiayaProduksi, 0, ',', '.') }}</p>
                    </div>

                    <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-2xl p-6 border border-amber-200">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            <h4 class="text-sm font-semibold text-amber-800">HPP (Total)</h4>
                        </div>
                        <p class="text-2xl font-bold text-amber-900">Rp {{ number_format($totalHPP, 0, ',', '.') }}
                        </p>
                    </div>

                    <div
                        class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-2xl p-6 border border-emerald-200">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                            </div>
                            <h4 class="text-sm font-semibold text-emerald-800">Total Harga Jual</h4>
                        </div>
                        <p class="text-2xl font-bold text-emerald-900">Rp
                            {{ number_format($totalHargaJual, 0, ',', '.') }}</p>
                    </div>

                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-gray-500 rounded-xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                            </div>
                            <h4 class="text-sm font-semibold text-gray-800">Total Pemasukan</h4>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">Rp
                            {{ number_format($totalPemasukan, 0, ',', '.') }}
                        </p>
                    </div>

                    <div
                        class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-2xl p-6 border border-indigo-200">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-indigo-500 rounded-xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                            </div>
                            <h4 class="text-sm font-semibold text-indigo-800">Sisa Pembayaran</h4>
                        </div>
                        @php
                            $sisaBayarClass = 'text-2xl font-bold';
                            if ($sisaBayar <= 0) {
                                $sisaBayarClass .= ' text-emerald-600';
                            } else {
                                $sisaBayarClass .= ' text-red-600';
                            }
                        @endphp
                        <p class="{{ $sisaBayarClass }}">Rp {{ number_format($sisaBayar, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Breakdown HPP</h3>
                        <canvas id="hppBreakdownChart" height="120"></canvas>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Pemasukan per Tanggal</h3>
                        <canvas id="incomeTrendChart" height="120"></canvas>
                    </div>
                </div>

                <!-- Analysis Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Analisis Margin, Laba/Rugi & Status</h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-gray-700 font-medium">Total Margin (Laba Kotor):</span>
                                <span class="text-lg font-semibold text-gray-900">Rp
                                    {{ number_format($totalMargin, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-gray-700 font-medium">Laba/Rugi (Profit/Loss):</span>
                                @php
                                    $profitLossClass = 'text-lg font-bold';
                                    if ($totalMargin > 0) {
                                        $profitLossClass .= ' text-emerald-600';
                                    } elseif ($totalMargin < 0) {
                                        $profitLossClass .= ' text-red-600';
                                    } else {
                                        $profitLossClass .= ' text-gray-600';
                                    }
                                @endphp
                                <span class="{{ $profitLossClass }}">
                                    Rp {{ number_format($totalMargin, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-3">
                                <span class="text-gray-700 font-medium">Status Order:</span>
                                @if ($totalMargin > 0)
                                    <span
                                        class="inline-flex items-center px-4 py-2 rounded-full bg-emerald-100 text-emerald-800 text-sm font-semibold">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        UNTUNG
                                    </span>
                                @elseif($totalMargin < 0)
                                    <span
                                        class="inline-flex items-center px-4 py-2 rounded-full bg-red-100 text-red-800 text-sm font-semibold">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        RUGI
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-4 py-2 rounded-full bg-gray-100 text-gray-800 text-sm font-semibold">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        IMPAS
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">Penjelasan Istilah:</h4>
                            <div class="space-y-2 text-sm text-gray-700">
                                <div class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    <span><b>HPP (Harga Pokok Produksi)</b> = Total Pembelian Material + Total Biaya
                                        Produksi Lain</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    <span><b>Total Margin</b> = Total Harga Jual - HPP (Total)</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    <span><b>Laba/Rugi</b> = Total Margin (positif = untung, negatif = rugi, nol =
                                        impas)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                // Format number input with commas
                function formatNumber(input) {
                    // Remove all non-digit characters
                    let value = input.value.replace(/[^\d]/g, '');
                    if (value) {
                        // Convert to number and format with commas
                        value = parseInt(value).toLocaleString('id-ID');
                    }
                    input.value = value;
                }

                // Validate price form before submission
                function validatePriceForm(form) {
                    console.log('Form validation called');
                    const priceInput = form.querySelector('input[name="total_price"]');
                    console.log('Price input value:', priceInput ? priceInput.value : 'No input found');
                    
                    if (priceInput && priceInput.value) {
                        // Clean the formatted value before submission
                        const cleanValue = priceInput.value.replace(/[^\d]/g, '');
                        console.log('Cleaned value:', cleanValue);
                        
                        if (cleanValue && parseInt(cleanValue) > 0) {
                            console.log('Validation passed, submitting form');
                            return true;
                        } else {
                            alert('Harga harus berupa angka yang valid dan lebih dari 0');
                            return false;
                        }
                    }
                    console.log('No price value, allowing submission');
                    return true; // Allow empty values
                }

                // Get current tab for redirect
                function getCurrentTab() {
                    return document.querySelector('[x-data]').__x.$data.tab;
                }

                // Add tab parameter to forms
                document.addEventListener('DOMContentLoaded', () => {
                    const forms = document.querySelectorAll('form');
                    forms.forEach(form => {
                        if (form.action.includes('/purchases') || form.action.includes('/costs') || form.action.includes('/incomes')) {
                            const tabInput = document.createElement('input');
                            tabInput.type = 'hidden';
                            tabInput.name = 'current_tab';
                            tabInput.value = getCurrentTab();
                            form.appendChild(tabInput);
                        }
                    });
                });

                document.addEventListener('DOMContentLoaded', () => {
                    // HPP breakdown doughnut (detailed)
                    const hppCtx = document.getElementById('hppBreakdownChart');
                    if (hppCtx && window.Chart) {
                        const labels = @json($hppBreakdownLabels ?? []);
                        const values = @json($hppBreakdownValues ?? []);

                        // Generate palette
                        const baseColors = ['#60a5fa','#93c5fd','#3b82f6','#2563eb','#1d4ed8','#fca5a5','#f87171','#ef4444','#dc2626','#fbbf24','#f59e0b','#10b981','#34d399','#059669','#6ee7b7','#a78bfa','#8b5cf6','#6366f1','#22d3ee','#06b6d4'];
                        const colors = values.map((_, i) => baseColors[i % baseColors.length]);

                        const data = {
                            labels,
                            datasets: [{
                                data: values,
                                backgroundColor: colors,
                                borderWidth: 0,
                            }]
                        };
                        new Chart(hppCtx, {
                            type: 'doughnut',
                            data,
                            options: {
                                plugins: {
                                    legend: { position: 'bottom' },
                                    tooltip: {
                                        callbacks: {
                                            label: (ctx) => {
                                                const label = ctx.label || '';
                                                const value = ctx.parsed || 0;
                                                const total = values.reduce((a,b)=>a+b,0) || 1;
                                                const pct = (value/total*100).toFixed(1);
                                                return `${label}: Rp ${value.toLocaleString('id-ID')} (${pct}%)`;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }

                    // Income trend line (per tanggal input)
                    const incomeCtx = document.getElementById('incomeTrendChart');
                    if (incomeCtx && window.Chart) {
                        const incomeMap = @json(
                            $order->incomes->sortBy('date')->groupBy(function ($i) {
                                    return \Carbon\Carbon::parse($i->date)->format('Y-m-d');
                                })->map->sum('amount'));
                        const labels = Object.keys(incomeMap);
                        const values = Object.values(incomeMap);
                        new Chart(incomeCtx, {
                            type: 'line',
                            data: {
                                labels,
                                datasets: [{
                                    label: 'Pemasukan',
                                    data: values,
                                    borderColor: '#10b981',
                                    backgroundColor: 'rgba(16,185,129,0.15)',
                                    borderWidth: 2,
                                    tension: 0.3,
                                    fill: true,
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                }
                            }
                        });
                    }
                });
            </script>
        </div>
    </div>
</x-app-layout>
