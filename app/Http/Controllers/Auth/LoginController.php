<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $login    = $request->input('login');
        $password = $request->input('password');

        // Try email, student_id, or employee_id
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 
            (is_numeric($login) ? 'student_id' : 'employee_id');

        if (!Auth::attempt([$fieldType => $login, 'password' => $password])) {
            return back()->withErrors([
                'login' => 'Invalid credentials. Please try again.',
            ])->withInput();
        }

        $user = Auth::user();

        // Status gate
        if (!$user->isActive()) {
            Auth::logout();

            $messages = [
                'pending'  => 'Your account is still pending approval.',
                'rejected' => 'Your account has been rejected. Contact the library.',
                'inactive' => 'Your account is inactive. Contact the library.',
            ];

            return back()->withErrors([
                'login' => $messages[$user->status] ?? 'Account access denied.',
            ])->withInput();
        }

        $request->session()->regenerate();

        // Redirect based on role
        return redirect()->intended($this->redirectByRole($user->role));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    private function redirectByRole(string $role): string
    {
        return match($role) {
            'admin'     => '/admin/dashboard',
            'librarian' => '/librarian/dashboard',
            'student'   => '/student/dashboard',
            default     => '/',
        };
    }
}