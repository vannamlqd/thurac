<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Origin extends Model
{
	protected $table = 'emailspam.origins';
	protected $guarded = [''];
	protected $primarykey = 'origin_id';
	// protected $fillable = ['from'];
}
