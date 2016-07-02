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


    /**
     * Search the distinct institution name in the database
     *
     * @param $query
     * @param $term
     * @return mixed
     */
    public static function scopeInstitutionSearch($query, $term) {
        return EducationExp::select('institution')
            ->distinct()->where('institution', 'LIKE', '%' . $term . '%');
    }

    /**
     * Search the distinct major name in the database
     *
     * @param $query
     * @param $term
     * @return mixed
     */
    public static function scopeMajorSearch($query, $term) {
        return EducationExp::select('major')
            ->distinct()->where('major', 'LIKE', '%' . $term . '%');
    }


    /**
     * find the educationexp in database base on the institution and major,
     * if it does not exsit, then create it and return
     *
     * @param $institution
     * @param $major
     * @return EducationExp
     */
    public static function findOrCreate($institution, $major) {
        $candidates = EducationExp::where('institution', $institution)->where('major', $major);
        if($candidates->count() > 0) {
            return $candidates->first();
        } else {
            $educationExp = EducationExp::create([
                'institution' => $institution,
                'major' => $major
            ]);
            return $educationExp;
        }
    }

    /**
     * return the full name of the education experience
     *
     * @return mixed|string
     */
    public function getFullNameAttribute() {
        $fullname = $this->institution;
        if ($this->major) {
            $fullname = $fullname . ' ⋅ ' . $this->major;
        }
        return $fullname;
    }
}
