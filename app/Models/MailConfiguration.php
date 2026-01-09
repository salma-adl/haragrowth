<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class MailConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption', 
        'mail_from_address', 'mail_from_name', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function setMailPasswordAttribute($value)
    {
        $this->attributes['mail_password'] = Crypt::encryptString($value); 
    }


    public function getMailPasswordAttribute($value)
    {
        return Crypt::decryptString($value);
    }


}
