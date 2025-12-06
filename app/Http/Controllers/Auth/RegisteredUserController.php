<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

use App\Models\Cart;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $name = $request->name;
        $nameParts = explode(' ', $name, 2);

        $user = User::create([
            'name' => $request->name,
            'first_name' => $nameParts[0],
            'last_name' => $nameParts[1] ?? '',
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        $user->user_id = generateUniqueId('user');

        $user->syncRoles('User');
        $user->update();

        event(new Registered($user));

        Auth::login($user);

        $guestUserId = Cookie::get('guest_user_id');

        if ($guestUserId) {
            $guestCartItems = Cart::where('user_id', $user->id)->get();

            foreach ($guestCartItems as $cartItem) {
                $cartItem->update(['user_id' => $user->id]);
            }

            Cookie::queue(Cookie::forget('guest_user_id'));
        }

        // return redirect(route('dashboard', absolute: false));
        return redirect(session('url.intended', route('user-dashboard.profile')));
    }
}
