<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EMail extends Model
{
    protected $table = 'emailspam.emails';
    protected $guarded = [''];
}