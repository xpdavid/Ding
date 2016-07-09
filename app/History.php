<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'text',
    ];

    /**
     * A history belong to an item
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function forItem() {
        return $this->morphTo();
    }
}
