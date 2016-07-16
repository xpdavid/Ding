<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthGroup extends Model
{
    protected $table = "authGroups";
    protected $fillable = [
        'name',
        'point'
    ];


    /**
     * An auth group may have different authorities
     */
    public function authorities() {
        return $this->belongsToMany('App\Authority',
            'authGroup_authority', 'authGroup_id', 'authority_id');
    }

    /**
     * Many user may in the authgroup
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users() {
        return $this->hasMany('App\User', 'authGroup_id');
    }


}
