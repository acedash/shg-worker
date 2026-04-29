<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Ulb;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'districts' => District::query()->with('ulbs')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'district_id' => ['nullable', 'exists:districts,id'],
            'ulb_id' => ['nullable', 'exists:ulbs,id'],
            'assigned_ward' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        if (! empty($validated['district_id']) && ! empty($validated['ulb_id'])) {
            $district = District::findOrFail($validated['district_id']);
            $ulb = Ulb::query()
                ->whereKey($validated['ulb_id'])
                ->where('district_id', $district->id)
                ->first();

            if (! $ulb) {
                return back()
                    ->withErrors(['ulb_id' => 'Please select a valid ULB for the chosen district.'])
                    ->withInput();
            }

            $validated['district_name'] = $district->name;
            $validated['ulb_name'] = $ulb->name;
        }

        if (blank($validated['password'] ?? null)) {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('profile.edit')
            ->with('status', 'Profile updated successfully.');
    }
}

