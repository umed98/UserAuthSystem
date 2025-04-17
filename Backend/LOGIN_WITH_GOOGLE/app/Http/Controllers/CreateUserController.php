<?php

namespace App\Http\Controllers;

use App\Mail\SendOtp;
use App\Models\usersignup;
use DB;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Log;
use Mail;

class CreateUserController extends Controller
{
    public function register(Request $request)
{
    try {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:usersignups',
            'password' => 'required|string|min:6',
        ]);

        $otp = rand(100000, 999999);

        $user = usersignup::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'otp' => $otp,
        ]);

        // Send OTP email
        Mail::to($user->email)->send(new SendOtp($otp, $user->username));

        return response()->json([
            'message' => 'Registration successful. OTP sent to email.',
            'user' => $user,
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Something went wrong.',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function verifyOtp(Request $request)
{
    $request->validate([
      
        'otp' => 'required|digits:6',
    ]);

    $user = usersignup::where('otp', $request->otp)
                      ->first();

    if (!$user) {
        return response()->json([
            'message' => 'Invalid OTP.',
        ], 401);
    }

    // Mark as verified
    $user->is_verified = true;
    $user->otp = null; // Optional: clear OTP after successful verification
    $user->save();

    return response()->json([
        'message' => 'Email verification successful.',
        'user' => $user,
    ]);
}



// Login Function 
    public function loginuser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = DB::table('usersignups')->where('email', $request->email)->first();

        if ($user && $request->password === $user->password) {
            return response()->json([
                'status' => true,
                'message' => 'Login successful!',
                'user' => $user
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid email or password.'
            ], 401);
        }
    }



    // login with github
    public function redirectToGithub()
    {
        return response()->json([
            'url' => Socialite::driver('github')->stateless()->redirect()->getTargetUrl()
        ]);
    }

    public function handleGithubCallback()
    {
        try {
            $githubUser = Socialite::driver('github')->stateless()->user(); 
    
            $user = usersignup::where('email', $githubUser->email)->first();
    
            if (!$user) {
                $user = usersignup::create([
                    'username' => $githubUser->nickname ?? $githubUser->name,
                    'email' => $githubUser->email,
                    'password' => '', 
                ]);
            }
            $token = base64_encode($githubUser->email . '|' . now());
    
            return redirect()->away("http://localhost:5173/");

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'GitHub login failed!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    


// login with google
    public function redirectToGoogle()
    {
        return response()->json([
            'url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl()
        ]);
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user(); 
    
            $user = usersignup::where('email', $googleUser->email)->first();
    
            if (!$user) {
                $user = usersignup::create([
                    'username' => $googleUser->nickname ?? $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => '',
                ]);
            }
            $token = base64_encode($googleUser->email . '|' . now());
    
            return redirect()->away("http://localhost:5173/");

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Google login failed!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    
}
