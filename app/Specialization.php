<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    protected $table = 'good_at';

    protected $fillable = ['full_name'];

    /**
     * Defined eloquent relationship : A student could have many specializations
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students() {
        return $this->belongsToMany('App\User', 'user_specialization', 'user_id', 'specialization_id');
    }

    /**
     * find the specialization in database 
     * if it does not exsit, then create it and return
     *
     * @param $full_name
     * @return Specialization
     */
    public static function findOrCreate($specializationName) {
        $candidates = Specialization::where('full_name', $specializationName);
        if($candidates->count() > 0) {
            return $candidates->first();
        } else {
            $specialization = Specialization::create([
                'full_name' => $specializationName
            ]);
            return $specialization;
        }
    }


    /**
     * Get all the distinct specializations in the database
     *
     * @return array
     */
    public static function getSpecializationList() {
        $specialization_list = Specialization::select('full_name')
            ->distinct()
            ->get()
            ->lists('full_name')
            ->all();

        return $specialization_list;
    }
}
