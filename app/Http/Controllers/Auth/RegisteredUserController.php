<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\IntecSdrAdmin;
use App\Models\IntecSdrApplicant; // Import the applicant model
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,staff'],
        ]);

        $userId = null;

        if ($request->role === 'staff') {
            $applicant = IntecSdrApplicant::create([
                'applicant_name' => $request->name,
                'applicant_email' => $request->email,
            ]);

            $userId = $applicant->applicant_id;
        }
        else if ($request->role === 'admin') {
            $admin = IntecSdrAdmin::create([
                'admin_name' => $request->name,
                'admin_email' => $request->email,
            ]);
            $userId = $admin->admin_id;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'user_id' => $userId, 
        ]);

        event(new Registered($user));
        return redirect()->route('login')->with('success', 'Registration successful! You can now log in.');
        
    }
}
