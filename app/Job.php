<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
	protected $table = 'Jobs';

	protected $fillable = ['organization', 'designation'];

	/**
	 * Defined eloquent relationship : A student could has many jobs
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function students() {
	    return $this->belongsToMany('App\User', 'user_job', 'user_id', 'job_id');
	}

	/**
	 * find the job in database 
	 * if it does not exsit, then create it and return
	 *
	 * @param $organization
	 * @param $designation
	 * @return EducationExp
	 */
	public static function findOrCreate($organization, $designation) {
	    $candidates = Job::where('organization', $organization)->where('designation', $designation);
	    if($candidates->count() > 0) {
	        return $candidates->first();
	    } else {
	        $job = Job::create([
	            'organization' => $organization,
	            'designation' => $designation
	        ]);
	        return $job;
	    }
	}


    /**
     * Search the distinct organization name in the database
     *
     * @param $query
     * @param $term
     * @return mixed
     */
    public static function scopeOrganizationSearch($query, $term) {
        return Job::select('organization')
            ->distinct()->where('organization', 'LIKE', '%' . $term . '%');
    }

    /**
     * Search the distinct designation name in the database
     *
     * @param $query
     * @param $term
     * @return mixed
     */
    public static function scopeDesignationSearch($query, $term) {
        return Job::select('designation')
            ->distinct()->where('designation', 'LIKE', '%' . $term . '%');
    }

    /**
     * return the full name of the job
     *
     * @return string
     */
	public function getFullNameAttribute() {
	    return $this->organization . ' ⋅ ' . $this->designation;
	}
}
