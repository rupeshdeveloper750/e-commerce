<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SettingController extends Controller
{
    public function index()
    {
        Gate::authorize('manage-settings');

        // Fetch settings or supply defaults
        $settings = [
            'store_name' => Setting::getValue('store_name', 'ShopMe'),
            'contact_email' => Setting::getValue('contact_email', 'support@shopme.com'),
            'contact_phone' => Setting::getValue('contact_phone', '+91 99999 99999'),
            'contact_address' => Setting::getValue('contact_address', '123 E-Commerce Way, Silicon Valley'),
            'currency_symbol' => Setting::getValue('currency_symbol', '₹'),
            'currency_code' => Setting::getValue('currency_code', 'INR'),
            'timezone' => Setting::getValue('timezone', 'Asia/Kolkata'),
            'maintenance_mode' => Setting::getValue('maintenance_mode', '0'),
        ];

        return view('admin.setting.index', compact('settings'));
    }

    public function update(Request $request)
    {
        Gate::authorize('manage-settings');

        $rules = [
            'store_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'contact_address' => 'nullable|string|max:1000',
            'currency_symbol' => 'required|string|max:10',
            'currency_code' => 'required|string|max:10',
            'timezone' => 'required|string|max:100',
            'maintenance_mode' => 'required|boolean',
        ];

        $validated = $request->validate($rules);

        foreach ($validated as $key => $value) {
            Setting::setValue($key, $value);
        }

        ActivityLog::log('updated', Setting::class, null, [
            'keys_updated' => array_keys($validated),
        ]);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'System settings updated successfully.');
    }
}
