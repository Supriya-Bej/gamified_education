<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomepageContentController extends Controller
{
    public function edit(): View
    {
        $sections = config('homepage.sections');
        $defaults = [];

        foreach ($sections as $fields) {
            foreach ($fields as $key => $field) {
                $defaults[$key] = $field['default'];
            }
        }

        $savedContent = SiteSetting::query()
            ->where('key', 'homepage')
            ->first()
            ?->value ?? [];

        return view('admin.homepage.edit', [
            'sections' => $sections,
            'content' => array_replace($defaults, $savedContent),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $rules = [];

        foreach (config('homepage.sections') as $fields) {
            foreach (array_keys($fields) as $key) {
                $rules[$key] = ['required', 'string', 'max:1000'];
            }
        }

        $validated = $request->validate($rules);

        SiteSetting::query()->updateOrCreate(
            ['key' => 'homepage'],
            ['value' => $validated],
        );

        return redirect()
            ->route('admin.homepage.edit')
            ->with('success', 'Homepage content updated successfully.');
    }
}
