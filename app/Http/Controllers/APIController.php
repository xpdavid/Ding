<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\EducationExp;
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
}
