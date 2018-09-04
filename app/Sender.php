<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sender extends Model
{
	protected $table = 'emailspam.senders';
	protected $guarded = [''];
	// protected $fillable = ['from'];
	protected $primarykey = 'sender_id';
}
