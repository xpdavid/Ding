<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{

    /**
     * A topic will have many question
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function questions() {
        return $this->belongsToMany('App\Question');
    }
}
