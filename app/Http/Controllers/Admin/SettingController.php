<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display settings page with tabs.
     */
    public function index()
    {
        $settings = Setting::getAllAsArray();
        
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'operating_hours' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'instagram' => 'nullable|string|max:100',
            'tiktok' => 'nullable|string|max:255',
            'google_maps_url' => 'nullable|url|max:500',
            'notification_interval' => 'nullable|integer|min:10|max:120',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'notification_audio' => 'nullable|file|mimes:mp3,wav|max:1024',
        ]);

        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            // Delete old logo
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
            $logoPath = $request->file('site_logo')->store('settings', 'public');
            Setting::set('site_logo', $logoPath, 'image');
        }

        // Handle hero image upload
        if ($request->hasFile('hero_image')) {
            // Delete old hero image
            $oldHero = Setting::get('hero_image');
            if ($oldHero) {
                Storage::disk('public')->delete($oldHero);
            }
            $heroPath = $request->file('hero_image')->store('settings', 'public');
            Setting::set('hero_image', $heroPath, 'image');
        }

        // Handle notification audio upload
        if ($request->hasFile('notification_audio')) {
            // Delete old audio
            $oldAudio = Setting::get('notification_audio');
            if ($oldAudio) {
                Storage::disk('public')->delete($oldAudio);
            }
            $audioPath = $request->file('notification_audio')->store('settings', 'public');
            Setting::set('notification_audio', $audioPath, 'audio');
        }

        // Handle notification enabled checkbox
        Setting::set('notification_enabled', $request->has('notification_enabled') ? '1' : '0', 'boolean');

        // Save text settings
        $textSettings = [
            'site_name', 'site_description', 'hero_title', 'hero_subtitle',
            'phone', 'email', 'address', 'operating_hours',
            'whatsapp', 'instagram', 'tiktok', 'google_maps_url',
            'notification_interval'
        ];

        foreach ($textSettings as $key) {
            if (isset($validated[$key])) {
                $type = in_array($key, ['site_description', 'hero_subtitle', 'address']) ? 'textarea' : 'text';
                Setting::set($key, $validated[$key], $type);
            }
        }

        Setting::clearCache();

        return back()->with('success', 'Pengaturan berhasil disimpan!');
    }
}

