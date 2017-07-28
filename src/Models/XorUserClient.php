<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XorUserClient extends Model
{
    const ID = 'id';
    const USER_ID = 'user_id';
    const CLIENT_ID = 'client_id';

    protected $table = 'xor_users_clients';

    protected $guarded = ['id'];
}
