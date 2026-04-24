<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $colleges = College::all();
        return view('auth.register', compact('colleges'));
    }

    public function register(Request $request)
    {
        $role = $request->input('role', 'student');

        // Validation rules based on role
        $rules = [
            'role'          => 'required|in:student,librarian',
            'email'         => 'required|email|unique:users,email',
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'date_of_birth' => 'required|date',
            'gender'        => 'required|in:male,female,other',
            'contact_number'=> 'required|string|max:20',
            'password'      => 'required|string|min:8|confirmed',
        ];

        if ($role === 'student') {
            $rules['student_id'] = 'required|string|unique:users,student_id';
            $rules['college_id'] = 'required|exists:colleges,id';
            $rules['program']    = 'required|string|max:100';
            $rules['year_level'] = 'required|string';
            $rules['section']    = 'required|string|max:10';
        } else {
            $rules['employee_id'] = 'required|string|unique:users,employee_id';
            $rules['position']    = 'required|string|max:100';
        }

        $request->validate($rules);

        // Build user data
        $userData = [
            'role'           => $role,
            'email'          => $request->email,
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'date_of_birth'  => $request->date_of_birth,
            'gender'         => $request->gender,
            'contact_number' => $request->contact_number,
            'password'       => Hash::make($request->password),
            'status'         => 'pending',
        ];

        if ($role === 'student') {
            $userData['student_id'] = $request->student_id;
            $userData['college_id'] = $request->college_id;
            $userData['program']    = $request->program;
            $userData['year_level'] = $request->year_level;
            $userData['section']    = $request->section;
        } else {
            $userData['employee_id'] = $request->employee_id;
            $userData['program']     = $request->position;
        }

        User::create($userData);

        // Flash success and redirect back to register page to show success screen
        return redirect()->route('register')->with('registered', true);
    }
}