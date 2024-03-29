<?php
 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
 
class LoginController extends Controller
{
    /**
     * Show the login form
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function view()
    {
        return view('pages.auth.login');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);
 
        if (Auth::attempt($credentials, $request->remember_me)) {
            $request->session()->regenerate();
 
            return redirect()->intended('dashboard');
        }
 
        return back()->withErrors([
            'username' => 'Identitas tersebut tidak cocok dengan data kami.',
        ]);
    }
}