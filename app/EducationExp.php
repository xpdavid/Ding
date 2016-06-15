<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EducationExp extends Model
{
    /**
     * Override table name
     *
     * @var string
     */
    protected $table = "educationExps";

    /**
     * Set fillable area for the model
     *
     * @var array
     */
    protected $fillable = [
        'institution',
        'major',
    ];


    /**
     * Defined eloquent relationship : A student could has many education experience
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students() {
        return $this->belongsToMany('App\User', 'user_educationExp', 'user_id', 'educationExp_id');
    }


    public static function findOrCreate($institution, $major) {
        if(EducationExp::where('institution', $institution)->where('major', $major)->count() > 0) {

        }
    }
}
