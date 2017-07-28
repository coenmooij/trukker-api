<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledEmail extends Model
{
    const ID = 'id';
    const TO_NAME = 'to_name';
    const TO_EMAIL = 'to_email';
    const SUBJECT = 'subject';
    const BODY = 'body';
    const DELIVER_AT = 'deliver_at';
    const STATUS = 'status';

    const STATUS_READY = 'ready';
    const STATUS_SENDING = 'sending';
    const STATUS_SENT = 'sent';

    protected $table = 'scheduled_emails';

    protected $guarded = [self::ID];
}
