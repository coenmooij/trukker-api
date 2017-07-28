<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    const ID = 'id';
    const EMAIL = 'email';
    const PASSWORD = 'password';
    const SALT = 'salt';
    const FIRST_NAME = 'first_name';
    const LAST_NAME = 'last_name';
    const TOKEN = 'token';
    const TOKEN_EXPIRES = 'token_expires';

    protected $table = 'users';

    protected $guarded = ['id'];

    public function getFullName()
    {
        return $this->{self::FIRST_NAME} . ' ' . $this->{self::LAST_NAME};
    }
}
