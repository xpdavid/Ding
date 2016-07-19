<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginMethod extends Model
{
    protected $table = 'login_methods';
    protected $fillable = [
        'type',
        'token',
        'option1',
        'option2',
        'option3',
        'expired_at'
    ];


    /**
     * A login method belongs to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner() {
        return $this->belongsTo('App\User', 'user_id');
    }


    /**
     * Find user according to the type & token
     *
     * @param $type
     * @param $token
     * @return User / bool
     */
    public static function findUser($type, $id) {
        if (LoginMethod::whereType($type)->whereOption1($id)->exists()) {
            return LoginMethod::whereType($type)->whereOption1($id)->first()->owner;
        }

        return false;
    }
}
