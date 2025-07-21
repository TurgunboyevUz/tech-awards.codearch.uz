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
use Illuminate\Support\Facades\Hash;
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
            // Foydalanuvchi ismi
            'name'     => 'required',

            // Foydalanuvchi emaili
            'email'    => 'required|email|unique:users',

            // Foydalanuvchi paroli
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return $this->success([], 'User registered successfully', Response::HTTP_OK);
    }

    /**
     * Kirish
     *
     * @unauthenticated
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            // Foydalanuvchi emaili
            'email'    => 'required|email',

            // Foydalanuvchi paroli
            'password' => 'required',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user) {
            return $this->error('User not found', Response::HTTP_NOT_FOUND);
        }

        if (! Auth::attempt($data)) {
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
            // Foydalanuvchi ismi
            'name'     => 'nullable|string',

            // Foydalanuvchi emaili
            'email'    => 'nullable|email',

            // Foydalanuvchi paroli
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user = $request->user();

        $user->name     = $request->name ?? $user->name;
        $user->email    = $request->email ?? $user->email;
        $user->password = $request->password ? Hash::make($request->password) : $user->password;
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
}
