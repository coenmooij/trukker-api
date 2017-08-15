<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    const ID = 'id';
    const JOB_PROFILE_ID = 'job_profile_id';
    const TITLE = 'title';
    const DESCRIPTION = 'description';
    const COMPENSATION = 'compensation';
    const START_DATE = 'start_date';
    const END_DATE = 'end_date';
    const START_LOCATION = 'start_location';
    const END_LOCATION = 'end_location';
    const IS_RETOUR = 'is_retour';
    const OUTBOUND_CARGO = 'outbound_cargo';
    const INBOUND_CARGO = 'inbound_cargo';

    protected $table = 'shifts';

    protected $guarded = ['id', 'job_profile_id'];
}
