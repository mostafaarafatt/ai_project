<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'age' => 'required|integer|min:8',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $this->accountService->register($request->all());

        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }

        return response()->json([
            'message' => 'User registered successfully. Verification code sent to your email.',
            'user' => new UserResource($user),
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!$this->accountService->checkCredentials($request->only(['email', 'password']))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = User::where('email', $request->input('email'))->first();

        if (!$user->is_verified) {
            return response()->json(['message' => 'Please verify your email before logging in'], 403);
        }

        $token = $this->accountService->createToken($request->email);

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => new UserResource($user),
        ]);
    }

    public function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $email = $request->input('email');
        $code = $request->input('code');

        $verificationCode = $this->accountService->validateVerificationCode($email, $code);

        if (!$verificationCode) {
            return response()->json(['message' => 'Invalid or expired verification code'], 400);
        }

        $user = User::where('email', $email)->first();
        $this->accountService->verifyEmail($user);

        $this->accountService->markVerificationCodeAsUsed($verificationCode);

        return response()->json(['message' => 'Email verified successfully', 'user' => new UserResource($user)]);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $email = $request->input('email');

        if (!User::where('email', $email)->exists()) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $code = $this->accountService->generateVerificationCode($email);

        return response()->json(['message' => 'Password reset code sent to your email', 'code' => $code]);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'code' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $email = $request->input('email');
        $code = $request->input('code');

        $resetCode = $this->accountService->validateVerificationCode($email, $code);

        if (!$resetCode) {
            return response()->json(['message' => 'Invalid or expired reset code'], 400);
        }

        $user = User::where('email', $email)->first();
        $this->accountService->resetPassword($user, $request->input('password'));

        $this->accountService->markVerificationCodeAsUsed($resetCode);

        return response()->json(['message' => 'Password reset successful']);
    }

    public function logout()
    {
        $user = auth()->user();
        $this->accountService->logout($user);

        return response()->json(['message' => 'Logout successful']);
    }

    public function getUser()
    {
        if (!auth()->user()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return new UserResource(auth()->user());
    }
}
