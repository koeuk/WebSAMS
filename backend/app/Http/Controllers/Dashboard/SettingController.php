<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function edit()
    {
        return Inertia::render('Settings/Edit', [
            'settings' => [
                'university_name' => Setting::get('university_name', ''),
                'university_address' => Setting::get('university_address', ''),
                'university_phone' => Setting::get('university_phone', ''),
                'university_email' => Setting::get('university_email', ''),
                'university_website' => Setting::get('university_website', ''),
                'university_logo' => Setting::get('university_logo', ''),
            ],
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'university_name' => 'nullable|string|max:255',
            'university_address' => 'nullable|string',
            'university_phone' => 'nullable|string|max:20',
            'university_email' => 'nullable|email',
            'university_website' => 'nullable|string|max:255',
            'university_logo' => 'nullable|image|max:2048',
        ]);

        Setting::set('university_name', $request->university_name);
        Setting::set('university_address', $request->university_address);
        Setting::set('university_phone', $request->university_phone);
        Setting::set('university_email', $request->university_email);
        Setting::set('university_website', $request->university_website);

        if ($request->hasFile('university_logo')) {
            $oldLogo = Setting::get('university_logo');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
            $path = $request->file('university_logo')->store('settings', 'public');
            Setting::set('university_logo', $path);
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
