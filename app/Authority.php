<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Authority extends Model
{
    protected $table = "authorities";
    protected $fillable = [
        'type',
        'description'
    ];

    /**
     * An authority may shared by many auth groups
     */
    public function authGroups() {
        return $this->belongsToMany('App\AuthGroup',
            'authGroup_authority', 'authority_id', 'authGroup_id');
    }
}
