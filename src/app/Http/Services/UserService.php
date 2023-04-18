<?php

namespace App\Http\Services;

use App\Enums\UserStatusEnum;
use App\Models\User;
use App\Http\Resources\UserCollection;
use App\Exceptions\UserAccessException;
use App\Http\Requests\RegisterPostRequest;
use App\Http\Requests\ResetPasswordPostRequest;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    private $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function all(): Collection
    {
        return $this->userModel->all();
    }

    public function getById(Int $id): User
    {
        return $this->userModel->findOrFail($id);
    }

    public function getByEmail(String $email): User
    {
        return $this->userModel->where('email', $email)->firstOrFail();
    }

    public function geUserResource(User $user): UserCollection
    {
        return UserCollection::make($user);
    }

    public function create(RegisterPostRequest $user) : User
    {
        return $this->userModel->create($user->all());
    }

    public function hasAccess(User $user): void
    {
        if($user->status==2 || $user->status==0){
            throw new UserAccessException($user);
        }
    }

    public function reset_password(ResetPasswordPostRequest $request, String $user_id): void
    {
        # code...
        $user = $this->getById(
            (new DecryptService)->decryptId($user_id)
        );

        $this->hasAccess($user);

        $user->update([
            ...$request->all()
        ]);
    }

}
