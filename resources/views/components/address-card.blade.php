@props([
    'address',
    'index'
])

<div class="bg-white rounded-[20px] border border-gray-150 p-6 shadow-sm hover:shadow-md transition-all duration-300 relative flex flex-col justify-between h-full">
    <div>
        <div class="flex items-center justify-between mb-4">
            <span class="inline-flex items-center gap-1 text-xs font-bold text-gray-900 uppercase tracking-widest">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>{{ $address['label'] ?? 'Home' }}</span>
            </span>

            @if(!empty($address['is_default']))
                <span class="inline-flex items-center rounded-full bg-brand-50 border border-brand-100 px-2 py-0.5 text-[9px] font-bold text-brand-700 tracking-wider">
                    DEFAULT
                </span>
            @endif
        </div>

        <div class="space-y-1.5 text-sm text-gray-600 font-medium">
            <p class="font-bold text-gray-900">{{ $address['name'] ?? '' }}</p>
            <p class="text-xs text-gray-450 leading-relaxed">{{ $address['street'] ?? '' }}</p>
            <p class="text-xs text-gray-450 leading-none">{{ $address['city'] ?? '' }}, {{ $address['state'] ?? '' }} - {{ $address['zip'] ?? '' }}</p>
            <p class="text-xs text-gray-450 pt-2 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                <span>{{ $address['phone'] ?? '' }}</span>
            </p>
        </div>
    </div>

    <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
        <button @click="editAddress({{ $index }})" class="text-xs font-semibold text-gray-500 hover:text-brand-500 transition">
            Edit
        </button>
        <div class="w-px h-3 bg-gray-200"></div>
        <button @click="deleteAddress({{ $index }})" class="text-xs font-semibold text-red-500 hover:text-red-650 transition">
            Delete
        </button>
    </div>
</div>
