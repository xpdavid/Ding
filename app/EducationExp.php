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
     * Get all the distinct institution in the database
     *
     * @return array
     */
    public static function getInstitutionList() {
        $institution_list = EducationExp::select('institution')
            ->distinct()
            ->get()
            ->lists('institution')
            ->all();

        return $institution_list;
    }

    /**
     * Get all the distinct major in the database
     *
     * @return array
     */
    public static function getMajorList() {
        $major_list = EducationExp::select('major')
            ->distinct()
            ->get()
            ->lists('major')
            ->all();

        return $major_list;
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

    public function getFullNameAttribute() {
        $fullname = $this->institution;
        if ($this->major) {
            $fullname = $fullname . ' ⋅ ' . $this->major;
        }
        return $fullname;
    }
}
