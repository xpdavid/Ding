<?php

namespace App\Http\Controllers;

use App\User;
use App\Topic;
use App\Question;
use App\EducationExp;
use App\Http\Requests;
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
     * Response ajax request for query relevant terms.
     *
     * @param Request $request
     * @return array
     */
    public function postAutocomplete(Request $request) {
        $this->validate($request, [
           'queries' => 'array'
        ]);

        $results = [];

        foreach ($request->get('queries') as $query) {
            switch ($query['type']) {
                case 'topic' :
                    $term = $query['term']; // the keyword
                    $max_matches = $query['max_match']; // the maximum match
                    $use_similar = $query['use_similar']; // whether use similar

                    // leave the max_matches as numbers only
                    $max_matches = ($max_matches != "0") ? $max_matches : '0';

                    // leave the user_similar as true or false only
                    $use_similar = ($use_similar == "1") ? true : false;

                    $topics = $use_similar ? Topic::similarMatch($term) : Topic::noneSimilarMatch($term);
                    $topics = $topics->take($max_matches)->get();

                    foreach ($topics as $topic) {
                        array_push($results, [
                            'id' => $topic->id,
                            'name' => $topic->name,
                            'category' => 'Topic'
                        ]);
                    }

                    break;
                case 'question' :

                    $term = $query['term']; // the keyword
                    $max_matches = $query['max_match']; // the maximum match
                    $use_similar = $query['use_similar']; // whether use similar

                    // leave the max_matches as numbers only
                    $max_matches = ($max_matches != "0") ? $max_matches : '0';

                    // leave the user_similar as true or false only
                    $use_similar = ($use_similar == "1") ? true : false;

                    $questions = $use_similar ? Question::similarMatch($term) : Question::noneSimilarMatch($term);
                    $questions = $questions->take($max_matches)->get();

                    foreach ($questions as $question) {
                        array_push($results, [
                            'url' => action('QuestionController@show', $question->id),
                            'title' => $question->title,
                            'numAnswers' => $question->answers()->count(),
                            'category' => 'Question'
                        ]);
                    }

                    break;
                case 'people':

                    $term = $query['term']; // the keyword
                    $max_matches = $query['max_match']; // the maximum match
                    $use_similar = $query['use_similar']; // whether use similar

                    // leave the max_matches as numbers only
                    $max_matches = ($max_matches != "0") ? $max_matches : '0';

                    // leave the user_similar as true or false only
                    $use_similar = ($use_similar == "1") ? true : false;

                    $users = $use_similar ? User::similarMatch($term) : User::noneSimilarMatch($term);
                    $users = $users->take($max_matches)->get();

                    foreach ($users as $user) {
                        array_push($results, [
                            'id' => $user->id,
                            'name' => $user->name,
                            'category' => 'People'
                        ]);
                    }

                    break;
            }
        }

        return $results;

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
