<?php

namespace App\Http\Controllers;

use App\Topic;
use App\Http\Requests;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    protected $itemInPage = 12; // define how many item will be display in each page

    /**
     * show user custom topic page
     */
    public function index() {

    }

    /**
     * show all topic
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function topics() {
        $top_parent_topics = Topic::topParentTopics()->get();

        return view('topic.topics', compact('top_parent_topics'));
    }

    /**
     * Show specific topic
     *
     * @param $topic_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($topic_id, Request $request) {
        $topic = Topic::findOrFail($topic_id);

        $sorted = $request->get('sorted') ? $request->get('sorted') : '';

        $parent_topics = $topic->parent_topics;

        $subtopics = $topic->subtopics;

        return view('topic.show', compact('topic', 'sorted', 'parent_topics', 'subtopics'));
    }

    /**
     * Response ajax request to get highlight/recommend/wait_for_answer question
     *
     * @param Request $request
     * @return array
     */
    public function getQuestions(Request $request) {
        // validate the incoming request
        $this->validate($request, [
            'type' => 'required',
            'topic_id' => 'required|integer',
            'page' => 'required|integer',
        ]);

        // get necessary param
        $topic_id = $request->get('topic_id');
        $page = ($request->get('page') < '0') ? 1 : $request->get('page'); // page must be positive numbers
        $itemInPage = $request->exists('itemInPage') ? $request->get('itemInPage') : $this->itemInPage;

        $topic = Topic::findOrFail($topic_id);

        // determine request type
        $questions = null;
        switch ($request->get('type')) {
            case 'recommend' :
                $questions = $topic->questions;
                break;
            case 'wait_for_answer' :
                $questions = $topic->questions->filter(function($question) {
                    return $question->answers()->count() == 0;
                });
                break;
            default:
                $questions = $topic->questions;
                break;
        }

        $current_questions = $questions->forPage($page, $itemInPage);

        // format results
        $results = [];
        foreach ($current_questions as $question) {
            array_push($results, [
                'id' => $question->id,
                'title' => $question->title,
            ]);
        }

        return $results;
    }


    /**
     * Response ajax request to show child topics
     *
     * @param $parent_id
     * @return mixed
     */
    public function subTopics($parent_id, Request $request) {
        $page = $request->exists('page') ? $request->get('page') : 1;

        $topic = Topic::findOrFail($parent_id);

        // format results
        $results = [];
        foreach ($topic->subtopics->forPage($page, $this->itemInPage) as $subtopic) {
            array_push($results, [
                'id' => $subtopic->id,
                'name' => $subtopic->name,
                'description' => $subtopic->description,
                'numSubtopic' => $subtopic->subtopics()->count()
            ]);
        }

        // push the topics itself also
        array_unshift($results, [
            'id' => $topic->id,
            'name' => $topic->name,
            'description' => $topic->description,
            'numSubtopic' => $topic->subtopics()->count()
        ]);

        return $results;
    }
}
