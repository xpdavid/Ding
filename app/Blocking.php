<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blocking extends Model
{	
	protected $fillable = ['user_id', 'blocked_id'];

	/**
	 * Define eloquent relationship to user.
	 * Each blocking instruction belongs to a particular user
	 */
    public function user() {
        return $this->belongsTo('App\User');
    }
}
