<?php
namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Mail\EmailOtpMail;
use App\Models\Currency;
use App\Models\User;
use App\Traits\HttpResponse;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

#[Group('Foydalanuvchilar', weight: 0)]
class UserController extends Controller
{
    use HttpResponse;

    /**
     * Ro'yxatdan o'tish
     * 
     * @unauthenticated
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        $otp  = config('app.env') == 'local' ? 123456 : mt_rand(100000, 999999);
        $user = User::create([
            'name'           => $data['name'],
            'email'          => $data['email'],
            'password'       => bcrypt($data['password']),

            'otp_code'       => $otp,
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($user->email)->send(new EmailOtpMail($otp));

        return $this->success([
            'expires_at' => $user->otp_expires_at,
        ], 'User created successfully, please verify your email', Response::HTTP_CREATED);
    }

    /**
     * Kirish
     * 
     * @unauthenticated
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user) {
            return $this->error('User not found', Response::HTTP_NOT_FOUND);
        }

        if (! $user->hasVerifiedEmail()) {
            return $this->error('Email not verified', Response::HTTP_CONFLICT);
        }

        if (! auth()->attempt($data)) {
            return $this->error('Invalid credentials', Response::HTTP_UNAUTHORIZED);
        }

        Auth::login($user);

        return $this->success([
            'token' => $user->createToken('API Token')->plainTextToken,
        ], 'User logged in successfully', Response::HTTP_OK);
    }

    /**
     * Chiqish
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success([], 'User logged out successfully', Response::HTTP_OK);
    }

    /**
     * Profilni o'zgartirish
     */
    public function update(Request $request)
    {
        $request->validate([
            'name'     => 'nullable|string',
            'email'    => 'nullable|email',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user = $request->user();

        $user->name     = $request->name ?? $user->name;
        $user->email    = $request->email ?? $user->email;
        $user->password = $request->password ? bcrypt($request->password) : $user->password;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
            $user->otp_code          = config('app.env') == 'local' ? 123456 : mt_rand(100000, 999999);
            $user->otp_expires_at    = now()->addMinutes(5);

            Mail::to($user->email)->send(new EmailOtpMail($user->otp_code));

            return $this->success([
                'expires_at' => $user->otp_expires_at,
            ], 'User updated successfully, please verify your email', Response::HTTP_OK);
        }

        $user->save();

        return $this->success(new UserResource($user), 'User updated successfully', Response::HTTP_OK);
    }

    /**
     * Profil ma'lumotlari
     */
    public function user(Request $request)
    {
        return $this->success(new UserResource($request->user()), '', Response::HTTP_OK);
    }

    /**
     * Emailni tasdiqlash
     */
    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return $this->error('User not found', Response::HTTP_NOT_FOUND);
        }

        if ($user->hasVerifiedEmail()) {
            return $this->error('Email already verified', Response::HTTP_CONFLICT);
        }

        if ($user->otp_expires_at < now()) {
            return $this->error('OTP expired', Response::HTTP_CONFLICT);
        }

        if ($user->otp_code != $request->otp) {
            return $this->error('Invalid OTP', Response::HTTP_CONFLICT);
        }

        $user->markEmailAsVerified();

        return $this->success([], 'Email verified successfully', Response::HTTP_OK);
    }

    /**
     * OTPni qayta yuborish
     */
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return $this->error('User not found', Response::HTTP_NOT_FOUND);
        }

        if ($user->hasVerifiedEmail()) {
            return $this->error('Email already verified', Response::HTTP_CONFLICT);
        }

        $otp = config('app.env') == 'local' ? 123456 : mt_rand(100000, 999999);
        $user->update([
            'otp_code'       => $otp,
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($user->email)->send(new EmailOtpMail($otp));

        return $this->success([
            'expires_at' => $user->otp_expires_at,
        ], 'OTP resent successfully', Response::HTTP_OK);
    }

    /**
     * Hisobni to'ldirish
     */
    public function topup(Request $request)
    {
        $data = $request->validate([
            'amount'      => 'required|numeric',
            'currency_id' => 'required|exists:currencies,id',
        ]);

        $currency = Currency::find($data['currency_id']);

        $user        = $request->user();
        $transaction = $user->createMirpayTransaction($data['amount'], $currency . ' sotib olish uchun to\'lov');

        return $this->success([
            'amount'   => $data['amount'],
            'currency' => $currency,
            'currency_amount' => $data['amount'] / $currency->exchange_rate,
            'url'      => $transaction->redirect_url,
        ]);
    }
}
