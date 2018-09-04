<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $table = 'domains';
    protected $guarded = [''];	
    // protected $fillable = ['domain'];
    protected $primarykey = 'domain_id';

}
