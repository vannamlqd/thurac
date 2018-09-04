<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ip extends Model
{
    protected $table = 'emailspam.ips';
    protected $guarded = [''];
    // protected $fillable = ['ip'];
    protected $primarykey = 'ip_id';

}
