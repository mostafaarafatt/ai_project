<?php

namespace App\Services;

use App\Models\EmailVerificationCode;
use App\Models\PasswordResetCode;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountService
{
    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        $this->generateVerificationCode($user->email);

        return $user;
    }

    public function generateVerificationCode(string $email)
    {
        // Generate a 6-digit random code
        $code = Str::random(6);

        // Save the code in the email_verification_codes table
        EmailVerificationCode::create([
            'email' => $email,
            'code' => $code,
            'is_used' => false,
            'created_at' => now(),
        ]);

        // Send the code via email
        $user = User::where('email', $email)->first();
        $user->sendVerificationCodeNotification($code);

        return $code;
    }

    public function checkCredentials(array $credentials)
    {
        return auth()->attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ]);
    }

    public function createToken(string $email)
    {
        $user = User::where('email', $email)->first();
        return $user->createToken('API Token')->plainTextToken;
    }

    public function logout(User $user)
    {
        $user->tokens()->delete();
    }


    public function validateVerificationCode(string $email, string $code)
    {
        return EmailVerificationCode::where('email', $email)
            ->where('code', $code)
            ->where('is_used', false)
            ->where('created_at', '>', now()->subMinutes(60))
            ->first();
    }

    public function markVerificationCodeAsUsed(EmailVerificationCode $verificationCode)
    {
        $verificationCode->is_used = true;
        $verificationCode->save();
    }

    public function verifyEmail(User $user)
    {
        $user->is_verified = true;
        $user->save();
    }

    public function resetPassword(User $user, string $password)
    {
        $user->password = Hash::make($password);
        $user->save();
    }

//    public function generateResetCode(string $email)
//    {
//        // Generate a 6-digit random code
//        $code = Str::random(6);
//
//        // Save the code in the password_reset_codes table
//        PasswordResetCode::create([
//            'email' => $email,
//            'code' => $code,
//            'is_used' => false,
//            'created_at' => now(),
//        ]);
//
//        // Send the code via email
//        $user = User::where('email', $email)->first();
//        $user->sendResetPasswordCodeNotification($code);
//
//        return $code;
//    }
//
//    public function validateResetCode(string $email, string $code)
//    {
//        return PasswordResetCode::where('email', $email)
//            ->where('code', $code)
//            ->where('is_used', false)
//            ->where('created_at', '>', now()->subMinutes(60))
//            ->first();
//    }
//
//    public function markResetCodeAsUsed(PasswordResetCode $resetCode)
//    {
//        $resetCode->is_used = true;
//        $resetCode->save();
//    }


}
