@props([
    'notification',
    'index'
])

<div class="p-5 flex items-start gap-4 hover:bg-gray-50/60 transition-all duration-200 border-b border-gray-100 last:border-0 group">
    {{-- Category indicator icon --}}
    <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center shrink-0 border border-gray-100 group-hover:bg-brand-50 group-hover:text-brand-500 transition-colors">
        @if(($notification['category'] ?? '') === 'order')
            <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
        @elseif(($notification['category'] ?? '') === 'promo')
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7.463 8.2l.007-.003.011-.005m0 0L7 15.2M3 15.2h4.5m10 0l-3.8-3.8m0 0L17 11.4M17 11.4H12.5"/></svg>
        @else
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        @endif
    </div>

    {{-- Content --}}
    <div class="flex-grow min-w-0 space-y-1">
        <div class="flex items-start justify-between gap-4">
            <h4 class="text-sm font-semibold text-gray-900 leading-normal {{ empty($notification['is_read']) ? 'font-bold' : '' }}">
                {{ $notification['title'] ?? 'Notification' }}
            </h4>
            <span class="text-[10px] text-gray-400 font-bold whitespace-nowrap pt-0.5">{{ $notification['time'] ?? 'Just now' }}</span>
        </div>
        <p class="text-xs text-gray-450 leading-relaxed font-medium">{{ $notification['message'] ?? '' }}</p>
        
        <div class="pt-2 flex items-center gap-3">
            @if(empty($notification['is_read']))
                <button @click="markAsRead({{ $index }})" class="text-[10px] font-bold text-brand-500 hover:text-brand-600 transition tracking-wider uppercase">
                    Mark as Read
                </button>
                <div class="w-1 h-1 rounded-full bg-gray-200"></div>
            @endif
            <button @click="deleteNotification({{ $index }})" class="text-[10px] font-bold text-red-500 hover:text-red-650 transition tracking-wider uppercase">
                Delete
            </button>
        </div>
    </div>

    {{-- Unread Dot --}}
    @if(empty($notification['is_read']))
        <span class="w-2.5 h-2.5 rounded-full bg-brand-500 mt-1 shrink-0 shadow-md shadow-brand-500/20" title="Unread"></span>
    @endif
</div>
