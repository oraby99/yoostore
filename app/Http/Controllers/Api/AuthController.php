<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use App\Mail\SendVerificationCode;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResendOtpRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private AuthService $authService;
    protected $model;
    public function __construct(AuthService $authService, User $model)
    {
        $this->authService = $authService;
        $this->model = $model;
    }
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Proceed with user creation
            $data['verification_code'] = $this->generateVerificationCode();
            try {
                $user = User::create($data);
                Mail::to($user->email)->send(new SendVerificationCode($user->verification_code));
                return response()->json([
                    'status'  => 200,
                    'message' => 'User Created Successfully',
                    'data'    => new UserResource($user)
                ], 200);
            } catch (Exception $e) {
                Log::error('Error while creating a user', ['error' => $e->getMessage(), 'trace' => $e->__toString()]);
                return response()->json([
                    'status'  => 500,
                    'message' => 'User creation failed',
                    'error'   => $e->getMessage()
                ], 500);
            }
        } else {
            // Handle case where user already exists
            if ($user->email_verified_at == null) {
                Mail::to($user->email)->send(new SendVerificationCode($user->verification_code));
                return response()->json([
                    'status'  => 200,
                    'message' => 'Account already exists but is not verified',
                    'OTP'     => $user->verification_code,
                ], 200);
            } else {
                return response()->json([
                    'status'  => 400,
                    'message' => 'Account already exists',
                    'data'    => null,
                ], 400);
            }
        }
    }
    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        // Attempt to find the user by email
        $user = $this->model->where('email', $data['email'])->first();

        // Check if user exists and if the password is correct
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'data' => null,
                'status' => 401,
                'message' => 'Invalid email or password. Please check your credentials and try again.',
            ], 401);
        }
        // Check if the user is verified
        if (is_null($user->email_verified_at)) {
            return response()->json([
                'data'                  => null,
                'status'                => 403, // 403 Forbidden indicates that the user cannot proceed
                'message'               => 'Your account is not verified. Please verify your email.',
                'verification_code'     => $user->verification_code,
            ], 403);
        }
        // If verified, proceed with generating the token
        $token = $user->createToken('user Token')->plainTextToken;
        // Add the token to the user data
        $user->token = $token;
        // Return the successful response
        return response()->json([
            'data' => new UserResource($user),
            'status' => 200,
            'message' => 'Login successful. Welcome back!',
        ]);
    }
    public function deleteaccount($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 2]);
        return $this->success((new UserResource($user)));
    }
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = User::where('email', $request->email)->first();
        Mail::to($user->email)->send(new SendVerificationCode($user->verification_code));
        $user->update([
            'password' => Hash::make($data['password']),
        ]);
        return response()->json([
            'message' => 'Password Changed',
            'status' => 200,
            'data' => NULL
        ]);
    }
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::where('verification_code', $data['verification_code'])->first();

        if ($user) {
            // Update the user's password
            $user->update(['password' => Hash::make($data['password'])]);

            // Log the user in
            Auth::login($user);

            // Update the device token if provided
            if (Arr::exists($data, 'device_token')) {
                $user->update(['device_token' => $data['device_token']]);
            }

            // Generate a new token for the user
            $user['token'] = $user->createToken('fly-easy')->plainTextToken;

            return response()->json([
                'status'  => 200,
                'message' => 'Password changed successfully',
                'data'    => new UserResource($user),
            ], 200);
        } else {
            // Return an error response if the verification code is invalid
            return response()->json([
                'status'  => 400,
                'message' => 'Invalid verification code',
                'data'    => null,
            ], 400);
        }
    }
    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        $user = $this->model->where('verification_code', $request->verification_code)->first();
        if ($user) {
            $user->update(['email_verified_at' => now()]);
            $user['token'] = $user->createToken('user Token')->plainTextToken;
            return response()->json([
                'status'  => 200,
                'message' => 'Account Verified',
                'data'   => new UserResource($user)
            ], 200);
        } else {
            return response()->json([
                'status'  => 400,
                'message' => 'Invaild verification_code',
                'data'   => NULL
            ], 400);
        }
    }
    public function resendOtp(ResendOtpRequest $request): JsonResponse
    {
        $verification_code  = $this->generateVerificationCode();
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->update([
                'verification_code' => $verification_code,
                'email_verified_at' => null,
            ]);
            try {
                Mail::to($user->email)->send(new SendVerificationCode($user->verification_code));
                return response()->json([
                    'status'  => 200,
                    'message' => 'verification_code Sent',
                    'data'   => NULL,
                    'verification_code'  => $user->verification_code
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    'status'      => 500,
                    'message'     => $e->getMessage(),
                    'data' => null
                ], 500);
            }
        } else {
            return response()->json([
                'status'  => 404,
                'message' => 'User Not Found',
                'data'   => NULL
            ], 404);
        }
    }
    public function generateVerificationCode(): bool|int
    {
        try {
            do {
                $code = random_int(111111, 999999);
                $exists = User::where('verification_code', $code)->exists();
            } while ($exists);
            return $code;
        } catch (Exception $e) {
            Log::error('Error while creating verification code for new user', ['error' => $e->getMessage(), 'trace' => $e->__toString()]);
            return false;
        }
    }
}
