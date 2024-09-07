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
            if ($user->email_verified_at == null) {
                Mail::to($user->email)->send(new SendVerificationCode($user->verification_code));
                return response()->json([
                    'status'  => 200,
                    'message' => 'Account already exists but is not verified',
                    'verification_code'     => $user->verification_code,
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
        $user = $this->model->where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'data' => null,
                'status' => 401,
                'message' => 'Invalid email or password. Please check your credentials and try again.',
            ], 401);
        }
        if (is_null($user->email_verified_at)) {
            return response()->json([
                'status'                => 403,
                'message'               => 'Your account is not verified. Please verify your email.',
                'verification_code'     => $user->verification_code,
            ], 403);
        }
        $token = $user->createToken('user Token')->plainTextToken;
        $user->token = $token;
        return response()->json([
            'data' => new UserResource($user),
            'status' => 200,
            'message' => 'Login successful. Welcome back!',
        ],200);
    }
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = User::where('email', $request->email)->first();
        if($user) {
            $user->update(['verification_code' => $this->generateVerificationCode()]);
            Mail::to($user->email)->send(new SendVerificationCode($user->verification_code));
            return response()->json([
                'message' => 'verification code sent succesfuly',
                'status' => 200,
                'data' => NULL
            ]);
        }    
    }
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = User::where('verification_code', $data['verification_code'])->first();
        if ($user) {
            $user->update(['password' => Hash::make($data['password'])]);
            Auth::login($user);
            if (Arr::exists($data, 'device_token')) {
                $user->update(['device_token' => $data['device_token']]);
            }
            $user['token'] = $user->createToken('user Token')->plainTextToken;
            return response()->json([
                'status'  => 200,
                'message' => 'Password changed successfully',
                'data'    => new UserResource($user),
            ], 200);
        } else {
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
                $code = random_int(1111, 9999);
                $exists = User::where('verification_code', $code)->exists();
            } while ($exists);
            return $code;
        } catch (Exception $e) {
            Log::error('Error while creating verification code for new user', ['error' => $e->getMessage(), 'trace' => $e->__toString()]);
            return false;
        }
    }
    public function deleteaccount($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return $this->success((new UserResource($user)));
    }
}
