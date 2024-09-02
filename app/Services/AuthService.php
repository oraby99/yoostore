<?php

namespace App\Services;

use App\Jobs\SendingRegistrationMailJob;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Mail\SendVerificationCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use App\Models\User;
use Exception;

class AuthService
{
    public function register($data): bool
    {
        DB::beginTransaction();
        try {
            Arr::set($data, 'verification_code', $this->generateVerificationCode());
            $user = User::create($data);
            Mail::to($user->email)->send(new SendVerificationCode($user->verification_code));
            DB::commit();
            Log::info('New user registered', ['user' => $user]);
            return true;
        } catch (Exception $e) {
            Log::error('Error while creating a new user', ['error' => $e->getMessage(), 'trace' => $e->__toString()]);
            DB::rollBack();
            return false;
        }
    }
    public function login($data)
    {
        try {
            // Attempt to authenticate the user with the provided credentials
            if (Auth::attempt(['email' => Arr::get($data, 'email'), 'password' => Arr::get($data, 'password')])) {
                $user = User::where('email', Arr::get($data, 'email'))->with('subscription')->first();
    
                // Update the device token if provided
                if (Arr::exists($data, 'device_token'))
                    $user->update(['device_token' => Arr::get($data, 'device_token')]);
    
                // Check if the email is verified
                if ($user->email_verified_at == null)
                    return 'not verified';
    
                // Delete any existing tokens for this user
                $user->tokens()->delete();
    
                // Generate a new token for this session
                $token = $user->createToken('fly-easy')->plainTextToken;
                $user['token'] = $token;
    
                return $user;
            }
    
            return false;
        } catch (Exception $e) {
            Log::error('Error while logging in a user', ['error' => $e->getMessage(), 'trace' => $e->__toString()]);
            return false;
        }
    }
    public function forgotPassword(array $data): bool
    {
        DB::beginTransaction();
        try {
            $user = User::where('email', Arr::get($data, 'email'))->first();
            if($user) {
                $user->update(['verification_code' => $this->generateVerificationCode()]);
                Mail::to($user->email)->send(new SendVerificationCode($user->verification_code));
              //  SendingRegistrationMailJob::dispatch($user);
                DB::commit();
                return true;
            }

            DB::rollBack();
            return false;
        } catch (Exception $e) {
            Log::error('Error while forgot password for a user', ['error' => $e->getMessage(), 'trace' => $e->__toString()]);
            DB::rollBack();
            return false;
        }
    }
    public function changePassword($data)
    {
        DB::beginTransaction();
        try {
            $user = User::where('verification_code', Arr::get($data, 'code'))->first();
            if($user) {
                $user->update(['password' => Arr::get($data, 'password')]);
                DB::commit();
                Auth::login($user);

                if(Arr::exists($data, 'device_token'))
                    $user->update(['device_token' => Arr::get($data, 'device_token')]);

                $user['token'] = $user->createToken('fly-easy')->plainTextToken;
                return $user;
            }

            DB::rollBack();
            return false;
        } catch (Exception $e) {
            Log::error('Error while change password for a user', ['error' => $e->getMessage(), 'trace' => $e->__toString()]);
            DB::rollBack();
            return false;
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
    public function verifyOtp($data)
    {
        try {
            $user = User::where('verification_code', Arr::get($data, 'code'))->first();
            if($user) {
                DB::beginTransaction();
                $user->update(['email_verified_at' => now()]);
                DB::commit();
                Auth::login($user);

                if(Arr::exists($data, 'device_token'))
                    $user->update(['device_token' => Arr::get($data, 'device_token')]);

                $user['token'] = $user->createToken('fly-easy')->plainTextToken;
                return $user;
            }

            return false;
        } catch (Exception $e) {
            Log::error('Error while verifying otp', ['error' => $e->getMessage(), 'trace' => $e->__toString()]);
            DB::rollBack();
            return false;
        }
    }
    public function resendOtp($data): bool
    {
        try {
            $user = User::where('email', Arr::get($data, 'email'))->first();
            if($user) {
                DB::beginTransaction();
                $newOtp = $this->generateVerificationCode();
                $user->update(['verification_code' => $newOtp]);
                $user->refresh();

                if($user->wasChanged()) {
                    DB::commit();
                    Mail::to($user->email)->send(new SendVerificationCode($user->verification_code));
                   // SendingRegistrationMailJob::dispatch($user);
                    return true;
                }
            }

            DB::rollBack();
            return false;
        } catch (Exception $e) {
            Log::error('Error while resending otp to an email', ['error' => $e->getMessage(), 'trace' => $e->__toString()]);
            DB::rollBack();
            return false;
        }
    }
}
