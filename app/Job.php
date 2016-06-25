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
	 * @param $institution
	 * @param $major
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
	 * Get all the distinct organizations in the database
	 *
	 * @return array
	 */
	public static function getOrganizationList() {
	    $orgnization_list = Job::select('organization')
	        ->distinct()
	        ->get()
	        ->lists('organization')
	        ->all();

	    return $orgnization_list;
	}

	/**
	 * Get all the distinct designations in the database
	 *
	 * @return array
	 */
	public static function getDesignationList() {
	    $designation_list = Job::select('designation')
	        ->distinct()
	        ->get()
	        ->lists('designation')
	        ->all();

	    return $designation_list;
	}

	public function getFullNameAttribute() {
	    return $this->organization . ' ⋅ ' . $this->designation;
	}
}
