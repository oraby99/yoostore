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

class AuthController extends Controller
{
    private AuthService $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
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
    
    public function login(LoginRequest $request): JsonResponse
    {
        
        if($user = $this->authService->login($request->validated())) {
            if($user == 'not verified')
                return $this->notVerified();

            return $this->success((new UserResource($user)));
        }

        return $this->failed();
    }
    public function deleteaccount($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 2]);
        return $this->success((new UserResource($user)));
    }
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        if($this->authService->forgotPassword($request->validated()))
            return $this->success();

        return $this->failed();
    }
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        if($user = $this->authService->changePassword($request->validated()))
            return $this->success((new UserResource($user)));

        return $this->failed();
    }
    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        if($user = $this->authService->verifyOtp($request->validated()))
            return $this->success((new UserResource($user)));

        return $this->failed();
    }
    public function resendOtp(ResendOtpRequest $request): JsonResponse
    {
        if($this->authService->resendOtp($request->validated()))
            return $this->success();

        return $this->failed();
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
