<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\EducationExp;
use App\Job;
use App\Topic;
use Illuminate\Http\Request;


class APIController extends Controller
{

    /**
     * Get all the institutions
     *
     * this is api function
     */
    public function getInstitutionList() {
        return EducationExp::getInstitutionList();
    }

    /**
     * Get all the majors
     *
     * this is api function
     */
    public function getMajorList() {
        return EducationExp::getMajorList();
    }

    /**
     * Get all the organizations
     *
     * this is api function
     */
    public function getOrganizationList() {
        return Job::getOrganizationList();
    }

    /**
     * Get all the majors
     *
     * this is api function
     */
    public function getDesignationList() {
        return Job::getDesignationList();
    }

    /**
     * Get all the majors
     *
     * this is api function
     */
    public function getSpecializationList() {
        return Topic::getTopicList();
    }
}
