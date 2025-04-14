<?php
namespace App\Http\Controllers\Authentication;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class Login extends Controller
{
    public function __invoke(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required', // Only accept username
            'password' => 'required', // Password is still required
        ]);

        // Attempt to authenticate using the username and password
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->route('food-categories.index')
                ->withSuccess('You have successfully logged in!');
        }

        return back()->withErrors([
            'username' => 'Your provided credentials do not match our records.',
        ])->onlyInput('username');
    }
}
