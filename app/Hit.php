<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hit extends Model
{
    /**
     * Define fillable
     *
     * @var array
     */
    protected $fillable = [
        'day',
        'week',
        'month',
        'total',
        'owner_type',
        'owner_id'
    ];

    /**
     * Aa hit belong to one model
     *
     * @return mixed
     */
    public function getOwnerAtrribute() {
        switch ($this->owner_type) {
            case 'question':
                return Question::findOrFail($this->owner_id);
            case 'answer':
                return Answer::findOrFail($this->owner_id);
            case 'bookmark':
                return Bookmark::findOrFail($this->owner_id);
            case 'user':
                return User::findOrFail($this->owner_id);
            default:
                abort(500);
        }
    }
}
