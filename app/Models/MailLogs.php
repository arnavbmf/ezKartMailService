<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailLogs extends Model
{
    use HasFactory;
    protected $table = "maillogs";
    protected $fillable = [
        'user_id',
        'to_emailId',
        'from_emailId',
        'subject',
        'mail_body',
        'created_at',
        'updated_at'
    ];
}
