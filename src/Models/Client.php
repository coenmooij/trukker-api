<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    const ID = 'id';
    const NAME = 'name';

    protected $table = 'clients';

    protected $guarded = ['id'];
}
