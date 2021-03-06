<?php

namespace App\Http\Controllers;

use App\User;
use App\Topic;
use App\Question;
use App\EducationExp;
use App\Http\Requests;
use App\Job;
use Illuminate\Http\Request;


class APIController extends Controller
{

    /**
     * Response ajax request for query relevant terms.
     *
     * @param Request $request
     * @return array
     */
    public function postAutocomplete(Request $request)
    {
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
                            'category' => 'Topic',
                            'url' => action('TopicController@show', $topic->id)
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
                            'category' => 'Question',
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
                            'category' => 'People',
                            'url' => action('PeopleController@show', $user->url_name)
                        ]);
                    }

                    break;

                case 'institution':
                    $term = $query['term']; // the keyword
                    $max_matches = $query['max_match']; // the maximum match

                    // leave the max_matches as numbers only
                    $max_matches = ($max_matches != "0") ? $max_matches : '0';

                    // search by institution name
                    $educationExps = EducationExp::institutionSearch($term)->take($max_matches)->get();

                    foreach ($educationExps as $educationExp) {
                        array_push($results, $educationExp->institution);
                    }

                    break;
                case 'major':
                    $term = $query['term']; // the keyword
                    $max_matches = $query['max_match']; // the maximum match

                    // leave the max_matches as numbers only
                    $max_matches = ($max_matches != "0") ? $max_matches : '0';

                    // search by institution name
                    $educationExps = EducationExp::majorSearch($term)->take($max_matches)->get();

                    foreach ($educationExps as $educationExp) {
                        array_push($results, $educationExp->major);
                    }

                    break;
                case 'organization' :
                    $term = $query['term']; // the keyword
                    $max_matches = $query['max_match']; // the maximum match

                    // leave the max_matches as numbers only
                    $max_matches = ($max_matches != "0") ? $max_matches : '0';

                    // search by institution name
                    $jobs = Job::organizationSearch($term)->take($max_matches)->get();

                    foreach ($jobs as $job) {
                        array_push($results, $job->organization);
                    }
                    break;
                case 'designation' :
                    $term = $query['term']; // the keyword
                    $max_matches = $query['max_match']; // the maximum match

                    // leave the max_matches as numbers only
                    $max_matches = ($max_matches != "0") ? $max_matches : '0';

                    // search by institution name
                    $jobs = Job::designationSearch($term)->take($max_matches)->get();

                    foreach ($jobs as $job) {
                        array_push($results, $job->designation);
                    }
                    break;
            }
        }

        return $results;
    }
}
