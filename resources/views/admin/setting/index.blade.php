@extends('admin.layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Header --}}
    <div>
        <nav class="mb-3 flex items-center gap-2 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 transition hover:text-[#B88A44]">Dashboard</a>
            <span class="text-gray-400">/</span>
            <span class="font-medium text-[#B88A44]">System Settings</span>
        </nav>
        <h1 class="text-3xl font-bold tracking-tight text-white">System Settings</h1>
        <p class="mt-2 text-sm text-slate-400">Configure global configurations, currency formats, location metadata and maintenance status.</p>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Store Metadata --}}
        <section class="rounded-3xl border border-slate-800 bg-[#111827] shadow-xl overflow-hidden">
            <div class="border-b border-slate-800 px-6 py-5 bg-slate-900/40">
                <h3 class="text-base font-semibold text-white">General Details</h3>
                <p class="text-xs text-slate-500 mt-1">Configure your storefront identifier and contact information.</p>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Store Name</label>
                    <input type="text" name="store_name" value="{{ old('store_name', $settings['store_name']) }}" required class="h-12 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                    @error('store_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-2">Contact Email</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}" required class="h-12 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                        @error('contact_email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-2">Contact Phone</label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone']) }}" class="h-12 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                        @error('contact_phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Contact Address</label>
                    <textarea name="contact_address" rows="3" class="w-full rounded-xl border border-slate-700 bg-slate-800 p-4 text-white focus:border-[#B88A44] focus:outline-none">{{ old('contact_address', $settings['contact_address']) }}</textarea>
                    @error('contact_address') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        {{-- Localization & Formats --}}
        <section class="rounded-3xl border border-slate-800 bg-[#111827] shadow-xl overflow-hidden">
            <div class="border-b border-slate-800 px-6 py-5 bg-slate-900/40">
                <h3 class="text-base font-semibold text-white">Localization & Formats</h3>
                <p class="text-xs text-slate-500 mt-1">Configure currencies, regional symbols and timezones.</p>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-2">Currency Symbol</label>
                        <input type="text" name="currency_symbol" value="{{ old('currency_symbol', $settings['currency_symbol']) }}" required class="h-12 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                        @error('currency_symbol') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-2">Currency Code</label>
                        <input type="text" name="currency_code" value="{{ old('currency_code', $settings['currency_code']) }}" required class="h-12 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                        @error('currency_code') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-2">Timezone</label>
                        <select name="timezone" class="h-12 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                            <option value="Asia/Kolkata" {{ old('timezone', $settings['timezone']) === 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata</option>
                            <option value="UTC" {{ old('timezone', $settings['timezone']) === 'UTC' ? 'selected' : '' }}>UTC</option>
                            <option value="America/New_York" {{ old('timezone', $settings['timezone']) === 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                            <option value="Europe/London" {{ old('timezone', $settings['timezone']) === 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                        </select>
                        @error('timezone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </section>

        {{-- Maintenance Settings --}}
        <section class="rounded-3xl border border-slate-800 bg-[#111827] shadow-xl overflow-hidden">
            <div class="border-b border-slate-800 px-6 py-5 bg-slate-900/40">
                <h3 class="text-base font-semibold text-white">System Status</h3>
                <p class="text-xs text-slate-500 mt-1">Control active accessibility to the online shopper storefront.</p>
            </div>
            <div class="p-6 flex items-center justify-between">
                <div>
                    <span class="block text-sm font-semibold text-white">Maintenance Mode</span>
                    <span class="text-xs text-slate-400">Lock shopper access to display a standard maintenance landing page.</span>
                </div>
                <select name="maintenance_mode" class="h-12 rounded-xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                    <option value="0" {{ old('maintenance_mode', $settings['maintenance_mode']) == 0 ? 'selected' : '' }}>Store Online</option>
                    <option value="1" {{ old('maintenance_mode', $settings['maintenance_mode']) == 1 ? 'selected' : '' }}>Maintenance Active</option>
                </select>
            </div>
        </section>

        {{-- Save Actions --}}
        <div class="flex items-center justify-end">
            <button type="submit" class="inline-flex h-12 items-center justify-center rounded-xl bg-[#B88A44] px-8 text-sm font-semibold text-white shadow-lg transition hover:bg-[#a67936]">
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection
