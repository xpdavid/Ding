<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
    	'profile_pic_name',
    	'sex',
    	'facebook',
    	'email',
    	'bio',
    	'description',
    	'school',
    	'first_major',
    	'second_major',
    	'occupation'
    ];

    public function user()
    {
    	return $this->belongsTo('App\User')	;
    }
}
