<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

use App\Models\Cart;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        if ($user->status !== 1) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->back()->withErrors(['email' => 'Your account is inactive. Please contact support.']);
        }

        $request->session()->regenerate();

        // return redirect()->intended(route('dashboard', absolute: false));

        $guestUserId = Cookie::get('guest_user_id');

        if ($guestUserId) {
            $guestCartItems = Cart::where('user_id', $guestUserId)->get();

            foreach ($guestCartItems as $cartItem) {
                $cartItem->update(['user_id' => $user->id]);
            }

            Cookie::queue(Cookie::forget('guest_user_id'));
        }
        
        // Redirect based on role
        if ($user->hasRole('User')) {
            return redirect(session('url.intended', route('user-dashboard.profile')))->with('success','Login Successfully');
            // return redirect()->route('user-profile'); // Redirect to user profile
        } else {
            return redirect()->intended(route('dashboard')); // Redirect to dashboard
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/')->with('success','Logout Successfully');;
    }
}
