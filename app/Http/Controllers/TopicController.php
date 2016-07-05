<?php

namespace App\Http\Controllers;

use App\Visitor;
use Auth;
use Carbon\Carbon;
use IImage;
use File;
use App\Topic;
use App\Image;
use App\Http\Requests;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    protected $itemInPage = 12; // define how many item will be display in each page
    /**
     * TopicController constructor.
     *
     * define middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * show user custom topic page
     */
    public function index() {
        $user = Auth::user();

        // get subscribe_topics
        $subscribe_topics = $user->subscribe->topics;

        // generate people also like
        $other_topics = Topic::getHotTopics()->take(6);

        return view('topic.home', compact('subscribe_topics', 'other_topics'));
        
    }

    /**
     * show all topics
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function topics() {
        $top_parent_topics = Topic::topParentTopics()->get();

        $popular_topics = Topic::getHotTopics()->take(5);

        return view('topic.topics', compact('top_parent_topics', 'popular_topics'));
    }

    /**
     * Show specific topic
     *
     * @param $topic_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($topic_id, Request $request) {
        $topic = Topic::findOrFail($topic_id);

        // Visit Count
        Visitor::visit($topic);

        // determine sorted method
        $sorted = $request->get('sorted') ? $request->get('sorted') : '';

        // determine type
        $type = $request->get('type') ? $request->get('type') : 'highlight';

        // get parent topics
        $parent_topics = $topic->parent_topics;

        // get subtopics
        $subtopics = $topic->subtopics;

        return view('topic.show', compact('topic', 'sorted', 'parent_topics', 'subtopics', 'type'));
    }

    /**
     * Show overall organization of topics
     *
     * @param $topic_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function organization($topic_id) {
        $topic = Topic::findOrFail($topic_id);

        $parent_topics = $topic->parent_topics;

        $subtopics = $topic->subtopics;

        $parent_tree_array = [];
        if (!$this->generateParentTopicTree($topic, [], $parent_tree_array)) {
            // generate tree fail, abort
            abort(500);
        } else {
            $parent_tree = $this->renderParentTopicTree($parent_tree_array);
        }


        return view('topic.organization', compact('topic', 'parent_topics', 'subtopics', 'parent_tree'));
    }


    /**
     * show Edit topic page
     * need authentication check
     *
     * @param $topic_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($topic_id) {
        $topic = Topic::findOrFail($topic_id);

        $parent_topics = $topic->parent_topics;

        $subtopics = $topic->subtopics;

        return view('topic.edit', compact('topic', 'parent_topics', 'subtopics'));
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
            'topic_id' => 'required|integer',
            'page' => 'required|integer',
        ]);

        // get necessary param
        $topic_id = $request->get('topic_id');
        $page = ($request->get('page') < '0') ? 1 : $request->get('page'); // page must be positive numbers
        $itemInPage = $request->exists('itemInPage') ? $request->get('itemInPage') : $this->itemInPage;
        $sorted = $request->has('sorted') ? $request->get('sorted') : 'default';

        $topic = Topic::findOrFail($topic_id);


        $questions = null;
        // determine sorted type
        if ($sorted == 'created') {
            $questions = $topic->questions()->orderBy('created_at', 'desc')->get();
        } else {
            // determine request type
            switch ($request->get('type')) {
                case 'recommend' :
                    $questions = $topic->recommendQuestions;
                    break;
                case 'wait_for_answer' :
                    $questions = $topic->waitAnswerQuestions;
                    break;
                default:
                    $questions = $topic->highlightQuestions;
                    break;
            }
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

        $user = Auth::user();

        // format results
        $results = [];
        foreach ($topic->subtopics->forPage($page, $this->itemInPage) as $subtopic) {
            array_push($results, [
                'id' => $subtopic->id,
                'name' => $subtopic->name,
                'description' => $subtopic->description,
                'numSubtopic' => $subtopic->subtopics()->count(),
                'isSubscribed' => $user->subscribe->checkHasSubscribed($subtopic->id, 'topic'),
                'pic' => DImage($subtopic->avatar_img_id, 40, 40),
            ]);
        }

        // push the topics itself also
        array_unshift($results, [
            'id' => $topic->id,
            'name' => $topic->name,
            'description' => $topic->description,
            'numSubtopic' => $topic->subtopics()->count(),
            'isSubscribed' => $user->subscribe->checkHasSubscribed($topic->id, 'topic'),
            'pic' => DImage($topic->avatar_img_id, 40, 40),
        ]);

        return $results;
    }

    /**
     * update information of topics
     *
     * @param $topic_id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($topic_id, Request $request) {
        $topic = Topic::findOrFail($topic_id);
        if($request->exists('add_parent_topics')) {
            foreach ($request->get('add_parent_topics') as $key=>$parent_topic_id) {
                if ($parent_topic_id == $topic_id) {
                    // cannot be self assigned
                    continue;
                }
                // detach relationship first
                $topic->subtopics()->detach($parent_topic_id);
                $topic->parent_topics()->detach($parent_topic_id);

                $topic->parent_topics()->attach($parent_topic_id);

                // validation the assignment
                $results = [];
                if (!$this->generateParentTopicTree($topic, [], $results)) {
                    // generate tree fail, the current assignment is invalid
                    $topic->parent_topics()->detach($parent_topic_id);
                }
            }

        }

        if($request->exists('add_subtopics')) {
            foreach ($request->get('add_subtopics') as $key=>$subtopic_id) {
                if ($subtopic_id == $topic_id) {
                    // cannot be self assigned
                    continue;
                }
                // detach relationship first
                $topic->parent_topics()->detach($subtopic_id);
                $topic->subtopics()->detach($subtopic_id);

                $topic->subtopics()->attach($subtopic_id);

                // validation the assignment
                $results = [];
                if (!$this->generateParentTopicTree($topic, [], $results)) {
                    // generate tree fail, the current assignment is invalid
                    $topic->subtopics()->detach($subtopic_id);
                }
            }
        }

        if($request->exists('delete_parent_topics')) {
            foreach ($request->get('delete_parent_topics') as $key=>$delete_parent_topic_id) {
                $topic->parent_topics()->detach($delete_parent_topic_id);
            }
        }

        if($request->exists('delete_subtopics')) {
            foreach ($request->get('delete_subtopics') as $key=>$delete_subtopic_id) {
                $topic->subtopics()->detach($delete_subtopic_id);
            }
        }

        return redirect(action('TopicController@edit', $topic_id));
    }


    /**
     * Recursive process to generate parent topic tree
     * DFS searching technique
     *
     * @param Topic $topic
     * @param $current_result
     * @param &$results
     *
     * @return mixed
     */
    private function generateParentTopicTree(Topic $topic, $current_result, &$results) {
        if ($this->generateParentTopicTreeHelper($topic, $current_result)) {
            // we have already visit the node, impossible for DAG, thus break.
            return false;
        }
        array_push($current_result, [
            'id' => $topic->id,
            'name' => $topic->name,
        ]);
        if($topic->parent_topics()->count() == 0) {
            array_push($results, $current_result);
        } else {
            foreach ($topic->parent_topics as $parent_topic) {
                $flag = $this->generateParentTopicTree($parent_topic, $current_result, $results);
                if (!$flag) {
                    // the previous recursion fail, we also fail
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Helper function check if topic is in current_results
     *
     * @param Topic $topic
     * @param $current_result
     * @return bool
     */
    private function generateParentTopicTreeHelper(Topic $topic, $current_result) {
        foreach ($current_result as $item) {
            if( $topic->id == $item['id']) {
                return true;
            }
        }
        return false;
    }

    /**
     * Render the data from the above method
     *
     * @param $topics_path
     * @return string
     */
    private function renderParentTopicTree($topics_path) {
        $final_result = "";
        foreach ($topics_path as $topic_path) {
            $result = "";
            foreach ($topic_path as $topic) {
                $id = $topic['id'];
                $name = $topic['name'];
                $result = "<ul><li><a href='/topic/$id'>$name</a> $result</li></ul>";
            }
            $final_result = $final_result . $result;
        }
        return $final_result;
    }


}
