<?php

namespace App\Providers\Auth;

use App\Helpers\UserCache;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Validation\ValidationException;

class LegacyUserProvider implements UserProvider
{
    /**
     * The hasher implementation.
     *
     * @var Hasher
     */
    protected Hasher $hasher;

    /**
     * Create a new database user provider.
     */
    public function __construct(Hasher $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * @inheritDoc
     */
    public function retrieveById($identifier): ?Authenticatable
    {
        return UserCache::user($identifier);
    }

    /**
     * @inheritDoc
     */
    public function retrieveByToken($identifier, $token)
    {
        $model = UserCache::user($identifier);

        if (!$model) {
            return null;
        }

        $rememberToken = $model->getRememberToken();

        return $rememberToken && hash_equals($rememberToken, $token) ? $model : null;
    }

    /**
     * @inheritDoc
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        $user->setRememberToken($token);

        $user->saveOrFail();
    }

    /**
     * @inheritDoc
     */
    public function retrieveByCredentials(array $credentials)
    {
        return User::query()->where('login', $credentials['login'])->first();
    }

    /**
     * @inheritDoc
     */
    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        if (!$user->isActive()) {
            throw ValidationException::withMessages([
                $user->login => 'Usuário inativo',
            ]);
        }

        $pass = $this->hasher->check(
            $credentials['senha'],
            $user->getAuthPassword()
        );

        if (empty($pass)) {
            return $this->validateLegacyCredentials($user, $credentials);
        }

        return true;
    }

    /**
     * Validate legacy credentials and rehash if correct.
     *
     * @param User $user
     * @param array $credentials
     * @return bool
     */
    public function validateLegacyCredentials(User $user, array $credentials): bool
    {
        $plain = $credentials['senha'];

        if (md5(sha1($plain)) !== $user->getAuthPassword()) {
            return false;
        }

        $this->rehashPassword($user, $plain);

        return true;
    }

    /**
     * Rehash a legacy password that uses MD5.
     *
     * @param User $user
     * @param string $password
     * @return void
     */
    public function rehashPassword(User $user, string $password)
    {
        $user->senha = $this->hasher->make($password);
        $user->save();
    }
}
