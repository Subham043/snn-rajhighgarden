<?php

namespace App\Http\Services;

use App\Http\Requests\ForgotPasswordPostRequest;
use App\Http\Requests\PasswordPostRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfilePostRequest;
use Carbon\Carbon;
use App\Http\Services\DecryptService;

class AuthService extends UserService
{
    public function __construct(User $userModel)
    {
       parent::__construct($userModel);
    }

    public function logout(): void
    {
        Auth::logout();
    }

    public function login(array $credentials): String
    {
        return Auth::attempt($credentials);
    }

    public function forgot_password(ForgotPasswordPostRequest $request): User
    {
        $user = $this->getByEmail($request->email);
        $this->hasAccess($user);
        $user->update([
            'otp' => $request->otp,
        ]);
        return $user;
    }


    public function auth_user_details(): User
    {
        return Auth::user();
    }

    public function auth_refresh(): String
    {
        return Auth::refresh();
    }

    public function send_otp(String $id): User
    {
        $decryptedId = (new DecryptService)->decryptId($id);
        $user = $this->getById($decryptedId);
        $user->update([
            'otp' => rand(1000,9999),
        ]);

        return $user;
    }

    public function verify_user(String $id): User
    {
        $decryptedId = (new DecryptService)->decryptId($id);
        $user = $this->getById($decryptedId);
        $user->update([
            'otp' => rand(1000,9999),
            'status' => 1,
            'email_verified_at' => Carbon::now()->toDateTimeString(),
        ]);
        return $user;
    }

    public function profile_update(ProfilePostRequest $request): User
    {
        $user = $this->getById($this->auth_user_details()->id);
        $user->update([
            ...$request->all()
        ]);
        return $user;
    }

    public function password_update(PasswordPostRequest $request): User
    {
        $user = $this->getById($this->auth_user_details()->id);
        $user->update([
            ...$request->all()
        ]);
        return $user;
    }
}
