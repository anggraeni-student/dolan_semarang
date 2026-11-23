<?php

namespace App\Http\Controllers;

use App\Models\Interest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    public function show(): View
    {
        return view('onboarding', [
            'interests' => Interest::all(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'interests' => 'required|array|min:1|max:6',
            'interests.*' => 'exists:interests,id',
        ], [
            'interests.required' => 'Pilih minimal satu minat dulu ya.',
        ]);

        $request->user()->interests()->sync($validated['interests']);

        return redirect()->route('home')->with('success', 'Homepage-mu sudah disesuaikan dengan minatmu!');
    }
}
