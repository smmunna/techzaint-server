<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    //register a new user
    public function registerUser(Request $request)
    {
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;
        $avatar = $request->avatar;
        $existingUser = User::where('email', $email)->first();
        if ($existingUser) {
            return response()->json([
                'success' => false,
                'message' => 'User already exist'
            ]);
        } else {
            // Create a new user with hashed password
            $newUser = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password), // Hash the password
                'avatar' => $avatar,
                'remember_token' => rand(100000, 999999)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'user' => $newUser,
            ]);
        }
    }


    // Send verification code
    public function sendVerificationCode(Request $request)
    {
        $email = $request->query('email');
        $user = User::where('email', $email)->first();

        // Generate a random verification code
        $verificationCode = Str::random(6);

        // Update the user's verification code in the database
        $user->update(['remember_token' => $verificationCode]);

        // For sending the mail
        $emailContent = "
<html>
    <head>
        <style>
            body {
                font-family: 'Arial', sans-serif;
                background-color: #f4f4f4;
                color: #333;
            }
            h3 {
                color: #007bff;
            }
            hr {
                border: 1px solid #ccc;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                background-color: #fff;
                border: 1px solid #ddd;
                border-radius: 5px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }
            .message {
                margin-bottom: 20px;
            }
            .code {
                font-size: 32px;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <h3>Hello Dear,</h3>
            <p class='message'>We are providing you with this verification code to ensure the security of your email. Please use the code below to verify your email address so that we can easily stay in touch with you.</p>
            <hr>
            <p><strong>Verification Code:</strong> <span class='code'>$verificationCode</span></p>
        </div>
    </body>
</html>";


        Mail::raw($emailContent, function ($message) use ($email, $emailContent) {
            $message->to($email)
                ->subject('TechZaint Verification Code')
                ->html($emailContent);
        });

        return response()->json([
            'success' => true,
            'message' => 'Verification hasbeen sent, Check Inbox'
        ]);
    }

    // Verify Email address;
    public function verifyEmail(Request $request)
    {
        $email = $request->query('email');
        $remember_token = $request->query('remember_token');
        $user = User::where('email', $email)->first();

        if ($email == $user->email && $remember_token == $user->remember_token) {
            $user->email_verification = 'true';
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'Email hasbeen Verified'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid information'
            ]);
        }
    }

    // user list
    public function userList(Request $request)
    {
        $limit = $request->query('limit', 5);
        $offset = $request->query('offset', 0);

        $users = User::offset($offset)->limit($limit)->orderBy('created_at', 'desc')->get();
        return response()->json([
            'limit' => $limit,
            'offset' => $offset,
            'users' => $users,
            'total' => User::count(),
        ]);
    }

    // SingleUserInfo
    public function signleUserInfo(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        return $user;
    }
}
