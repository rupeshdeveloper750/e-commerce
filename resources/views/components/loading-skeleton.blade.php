@props([
    'type' => 'cards'
])

@if($type === 'cards')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 animate-pulse">
        @for($i = 0; $i < 4; $i++)
            <div class="bg-gray-100 rounded-[20px] h-32 p-6 flex flex-col justify-between">
                <div class="h-4 bg-gray-200 rounded w-1/3"></div>
                <div class="h-8 bg-gray-200 rounded w-1/2"></div>
                <div class="h-3 bg-gray-200 rounded w-3/4"></div>
            </div>
        @endfor
    </div>
@elseif($type === 'products')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 animate-pulse">
        @for($i = 0; $i < 4; $i++)
            <div class="bg-white border border-gray-150 rounded-[20px] overflow-hidden space-y-4">
                <div class="aspect-square bg-gray-100 w-full"></div>
                <div class="p-5 space-y-3">
                    <div class="h-3 bg-gray-200 rounded w-1/4"></div>
                    <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                    <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                </div>
            </div>
        @endfor
    </div>
@elseif($type === 'orders')
    <div class="space-y-6 animate-pulse">
        @for($i = 0; $i < 3; $i++)
            <div class="bg-white border border-gray-150 rounded-[20px] p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                    <div class="space-y-2">
                        <div class="h-4 bg-gray-200 rounded w-32"></div>
                        <div class="h-3 bg-gray-200 rounded w-24"></div>
                    </div>
                    <div class="h-6 bg-gray-200 rounded w-20"></div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-xl bg-gray-100 shrink-0"></div>
                    <div class="flex-grow space-y-2">
                        <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                        <div class="h-3 bg-gray-200 rounded w-1/4"></div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
@else
    <div class="space-y-6 animate-pulse">
        <div class="bg-gray-100 rounded-[20px] h-48 w-full"></div>
    </div>
@endif
