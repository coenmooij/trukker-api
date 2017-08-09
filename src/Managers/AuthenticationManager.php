<?php

namespace App\Managers;

use App\Exceptions\LoginFailedException;
use App\Models\PasswordReset;
use App\Models\ScheduledEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthenticationManager extends AbstractManager
{
    const PASSWORD_RESET_SUBJECT = 'Trukker Password Reset';

    public function login(array $request): string
    {
        try {
            $user = $this->getUser(User::EMAIL, $request[User::EMAIL]);
        } catch (ModelNotFoundException $exception) {
            throw new LoginFailedException('User not found');
        }

        if (!$this->passwordIsValid($request[User::PASSWORD], $user->{User::SALT}, $user->{User::PASSWORD})) {
            throw new LoginFailedException('Password mismatch');
        }

        return $this->createToken($user);
    }

    public function logout(string $token): void
    {
        $user = $this->getUser(User::TOKEN, $token);
        $user->{User::TOKEN} = null;
        $user->{User::TOKEN_EXPIRES} = null;
    }

    public function register(array $request): int
    {
        $user = new User();
        $user->{User::EMAIL} = $request[User::EMAIL];
        $user->{User::SALT} = $this->createSalt($user->{User::EMAIL});
        $user->{User::PASSWORD} = $this->hashPassword($request[User::PASSWORD], $user->{User::SALT});
        $user->{User::FIRST_NAME} = $request[User::FIRST_NAME];
        $user->{User::LAST_NAME} = $request[User::LAST_NAME];
        $user->{User::TOKEN_EXPIRES} = Carbon::now()->addHour(1);
        $user->saveOrFail();

        return $user->{User::ID};
    }

    public function resetPassword(array $request): void
    {
        $user = $this->getUser(User::EMAIL, $request[User::EMAIL]);
        $passwordReset = new PasswordReset();
        $passwordReset->{PasswordReset::EMAIL} = $user->{User::EMAIL};
        $passwordReset->{PasswordReset::TOKEN} = $this->createToken($user);
        $passwordReset->{PasswordReset::EMAIL} = Carbon::now()->addHour(1);
        $passwordReset->saveOrFail();

        $subject = self::PASSWORD_RESET_SUBJECT;
        $body = 'Use the token to reset your password: ' . $passwordReset->{PasswordReset::TOKEN}; // TODO refactor
        $scheduledEmail = new ScheduledEmail();
        $scheduledEmail->{ScheduledEmail::TO_NAME} = $user->getFullName();
        $scheduledEmail->{ScheduledEmail::TO_EMAIL} = $user->{User::EMAIL};
        $scheduledEmail->{ScheduledEmail::SUBJECT} = $subject;
        $scheduledEmail->{ScheduledEmail::BODY} = $body;
        $scheduledEmail->{ScheduledEmail::STATUS} = ScheduledEmail::STATUS_READY;
        $scheduledEmail->saveOrFail();
    }

    public function authenticate($token): User
    {
        return $this->getUser(User::TOKEN, $token);
    }

    private function hashPassword(string $password, string $salt): string
    {
        $hash = password_hash($password . $salt . $this->getPepper(), PASSWORD_BCRYPT);

        return $hash;
    }

    private function passwordIsValid($password, $salt, $hash): bool
    {
        return password_verify($password . $salt . $this->getPepper(), $hash);
    }

    private function createSalt(string $prefix): string
    {
        return uniqid($prefix, true);
    }

    private function createToken(User $user)
    {
        $token = $user->{User::ID} . bin2hex(random_bytes(64));
        $user->token = $token;
        $user->save();

        return $token;
    }

    private function getUser($column, $value): User
    {
        return User::where($column, $value)->firstOrFail();
    }

    private function getPepper(): string
    {
        return env('PEPPER', '');
    }
}
