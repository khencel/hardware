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
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
    
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
    
            return redirect()
                ->route('dashboard.index')
                ->with('success', 'You have successfully logged in!');
        }
    
        return back()->withErrors([
            'username' => 'These credentials do not match our records.',
        ])->onlyInput('username');
    }
}
