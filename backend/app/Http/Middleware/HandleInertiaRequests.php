<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Dashboard\SettingController;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'locale' => fn () => app()->getLocale(),
            'university_logo' => fn () => ($logo = Setting::get('university_logo')) ? Storage::disk('public')->url($logo) : null,
            'enabled_languages' => fn () => SettingController::enabledLanguages(),
            'locales' => fn () => collect([
                ['code' => 'en', 'name' => 'English'],
                ['code' => 'km', 'name' => 'ខ្មែរ'],
                ['code' => 'zh', 'name' => '中文'],
            ])->whereIn('code', SettingController::enabledLanguages())->values()->all(),
        ];
    }
}
