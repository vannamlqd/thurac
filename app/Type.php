<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
	protected $table = 'emailspam.types';
	protected $guarded = [''];
	// protected $fillable = ['from'];
	protected $primarykey = 'type_id';
}