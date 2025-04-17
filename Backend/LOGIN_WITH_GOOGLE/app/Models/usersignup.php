<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class usersignup extends Model
{
    protected $fillable = ['username', 'email', 'password','otp'];

}
