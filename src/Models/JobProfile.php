<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobProfile extends Model
{
    const ID = 'id';
    const CLIENT_ID = 'client_id';
    const TITLE = 'title';
    const DESCRIPTION = 'description';
    const LICENSE = 'license';
    const CODE_95 = 'code_95';
    const VEHICLE_TYPE = 'vehicle_type';

    protected $table = 'job_profiles';

    protected $guarded = ['id'];
}
