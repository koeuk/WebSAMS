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
                'university_name'        => $this->readTranslatable('university_name'),
                'university_address'     => $this->readTranslatable('university_address'),
                'university_phone'       => Setting::get('university_phone', ''),
                'university_email'       => Setting::get('university_email', ''),
                'university_website'     => Setting::get('university_website', ''),
                'university_logo'        => Setting::get('university_logo', ''),
                'late_threshold_minutes' => (int) Setting::get('late_threshold_minutes', 10),
                'token_expiry_minutes'   => (int) Setting::get('token_expiry_minutes', 30),
                'default_language'           => Setting::get('default_language', 'en'),
                'enabled_languages'          => $this->readEnabledLanguages(),
                'teacher_default_language'   => Setting::get('teacher_default_language', Setting::get('default_language', 'en')),
                'teacher_enabled_languages'  => self::teacherEnabledLanguages(),
                'student_default_language'   => Setting::get('student_default_language', Setting::get('default_language', 'en')),
                'student_enabled_languages'  => self::studentEnabledLanguages(),
                'timezone'               => Setting::get('timezone', config('app.timezone', 'UTC')),
                'date_format'            => Setting::get('date_format', 'Y-m-d'),
                'theme_primary_color'    => Setting::get('theme_primary_color', '#D4A017'),
                'dark_mode_default'      => Setting::get('dark_mode_default', '0') === '1',
                'favicon'                => Setting::get('favicon', ''),
            ],
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'university_name'        => 'nullable|array',
            'university_name.en'     => 'nullable|string|max:255',
            'university_name.km'     => 'nullable|string|max:255',
            'university_name.zh'     => 'nullable|string|max:255',
            'university_address'     => 'nullable|array',
            'university_address.en'  => 'nullable|string',
            'university_address.km'  => 'nullable|string',
            'university_address.zh'  => 'nullable|string',
            'university_phone'       => 'nullable|string|max:20',
            'university_email'       => 'nullable|email',
            'university_website'     => 'nullable|string|max:255',
            'university_logo'        => 'nullable|image|max:2048',
            'favicon'                => 'nullable|image|max:1024',
            'late_threshold_minutes' => 'nullable|integer|min:0|max:120',
            'token_expiry_minutes'   => 'nullable|integer|min:1|max:1440',
            'default_language'             => 'nullable|in:en,km,zh',
            'enabled_languages'            => 'nullable|array',
            'enabled_languages.*'          => 'in:en,km,zh',
            'teacher_default_language'     => 'nullable|in:en,km,zh',
            'teacher_enabled_languages'    => 'nullable|array',
            'teacher_enabled_languages.*'  => 'in:en,km,zh',
            'student_default_language'     => 'nullable|in:en,km,zh',
            'student_enabled_languages'    => 'nullable|array',
            'student_enabled_languages.*'  => 'in:en,km,zh',
            'timezone'               => 'nullable|string|max:64',
            'date_format'            => 'nullable|string|max:32',
            'theme_primary_color'    => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'dark_mode_default'      => 'nullable|boolean',
        ]);

        foreach (['university_name', 'university_address'] as $key) {
            if ($request->has($key)) {
                Setting::set($key, json_encode($request->input($key) ?: []));
            }
        }

        foreach ([
            'university_phone', 'university_email', 'university_website',
            'late_threshold_minutes', 'token_expiry_minutes',
            'default_language', 'teacher_default_language', 'student_default_language',
            'timezone', 'date_format', 'theme_primary_color',
        ] as $key) {
            if ($request->has($key)) {
                Setting::set($key, (string) $request->input($key));
            }
        }

        if ($request->has('dark_mode_default')) {
            Setting::set('dark_mode_default', $request->boolean('dark_mode_default') ? '1' : '0');
        }

        foreach (['enabled_languages', 'teacher_enabled_languages', 'student_enabled_languages'] as $key) {
            if ($request->has($key)) {
                $codes = array_values(array_unique(array_intersect(
                    ['en', 'km', 'zh'],
                    (array) $request->input($key, []),
                )));
                if (!in_array('en', $codes, true)) $codes[] = 'en';
                Setting::set($key, json_encode(array_values($codes)));
            }
        }

        $this->handleUpload($request, 'university_logo', 'settings');
        $this->handleUpload($request, 'favicon', 'settings');

        return back()->with('success', 'Settings updated successfully.');
    }

    public static function enabledLanguages(): array
    {
        return self::readLanguageList('enabled_languages');
    }

    public static function teacherEnabledLanguages(): array
    {
        return self::readLanguageList('teacher_enabled_languages');
    }

    public static function studentEnabledLanguages(): array
    {
        return self::readLanguageList('student_enabled_languages');
    }

    public static function teacherDefaultLanguage(): string
    {
        return self::pickDefaultLanguage('teacher_default_language', self::teacherEnabledLanguages());
    }

    public static function studentDefaultLanguage(): string
    {
        return self::pickDefaultLanguage('student_default_language', self::studentEnabledLanguages());
    }

    private static function readLanguageList(string $key): array
    {
        $raw = Setting::get($key);
        $decoded = $raw ? json_decode($raw, true) : null;
        $list = is_array($decoded) ? array_values(array_intersect(['en', 'km', 'zh'], $decoded)) : ['en', 'km', 'zh'];
        if (!in_array('en', $list, true)) $list[] = 'en';
        return array_values($list);
    }

    private static function pickDefaultLanguage(string $settingKey, array $enabled): string
    {
        $value = Setting::get($settingKey) ?: Setting::get('default_language', 'en');
        return in_array($value, $enabled, true) ? $value : ($enabled[0] ?? 'en');
    }

    private function readEnabledLanguages(): array
    {
        return self::enabledLanguages();
    }

    private function readTranslatable(string $key): array
    {
        $raw = Setting::get($key, '');
        if (!$raw) return ['en' => '', 'km' => '', 'zh' => ''];
        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) return ['en' => (string) $raw, 'km' => '', 'zh' => ''];
        return array_merge(['en' => '', 'km' => '', 'zh' => ''], $decoded);
    }

    private function handleUpload(Request $request, string $key, string $dir): void
    {
        if (!$request->hasFile($key)) return;
        $old = Setting::get($key);
        if ($old) Storage::disk('public')->delete($old);
        $path = $request->file($key)->store($dir, 'public');
        Setting::set($key, $path);
    }
}
