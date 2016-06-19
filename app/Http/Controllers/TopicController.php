<?php

namespace App\Http\Controllers;

use App\Topic;
use App\Http\Requests;
use Illuminate\Http\Request;

class TopicController extends Controller
{

    /**
     * This is AJAX post request sever side autocomplete for topics
     *
     * @param Request $request
     * @return mixed
     */
    public function postAutocomplete(Request $request) {
        $this->validate($request, [
            'query' => 'required',
            'max_match' => 'required|integer',
            'use_similar' => 'required|boolean'
        ]);

        $query = $request->get('query'); // the keyword
        $max_matches = $request->get('max_match'); // the maximum match
        $use_similar = $request->get('use_similar'); // whether use similar

        // leave the max_matches as numbers only
        $max_matches = ($max_matches != "0") ? $max_matches : '0';

        // leave the user_similar as true or false only
        $use_similar = ($use_similar == "1") ? true : false;

        $topics = $use_similar ? Topic::similarMatch($query) : Topic::noneSimilarMatch($query);
        $topics = $topics->take($max_matches)->get();

        return $topics->lists('name', 'id')->all();
    }
}
