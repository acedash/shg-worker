<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Ulb;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register', [
            'districts' => District::query()->with('ulbs')->orderBy('name')->get(),
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'district_id' => ['required', 'exists:districts,id'],
            'ulb_id' => ['required', 'exists:ulbs,id'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

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

        $user = User::create([
            ...$validated,
            'district_name' => $district->name,
            'ulb_name' => $ulb->name,
            'role' => 'worker',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('worker.dashboard')->with('status', 'Registration successful. You can now fill in your daily activity.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
