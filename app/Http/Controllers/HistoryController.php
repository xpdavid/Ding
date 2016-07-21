<?php

namespace App\Http\Controllers;

use App\History;
use App\Point;
use App\Question;
use App\Topic;
use App\User;
use App\Answer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;

use App\Http\Requests;

class HistoryController extends Controller
{
    protected $itemInPage = 6;

    /**
     * HistoryController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth_real');
    }

    /**
     * Show topic edit history
     *
     * @param $topic_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTopicLog($topic_id) {
        $topic = Topic::findOrFail($topic_id);
        $parent_topics = $topic->parent_topics;
        $subtopics = $topic->subtopics;
        return view('topic.topic_log', compact('topic', 'parent_topics', 'subtopics'));
    }


    /**
     * Post ajax to get topic editing log
     *
     * @param $topic_id
     * @param Request $request
     * @return array
     */
    public function postTopicLog($topic_id, Request $request) {
        $topic = Topic::findOrFail($topic_id);

        // get necessary param
        $page = $request->get('page');
        $itemInPage = $request->get('itemInPage') ? $request->get('itemInPage') : $this->itemInPage;

        $pages = ceil($topic->histories()->count() / $itemInPage);
        $histories = $topic->histories()
            ->orderBy('created_at', 'desc')->get()->forPage($page, $itemInPage);

        // for name
        $names = [];
        foreach ($histories->filter(function ($value, $key) {
            return $value->type == 1;
        }) as $history) {
            $user = User::findOrFail($history->user_id);
            $user_arr = [
                'id' => $user->id,
                'name' => $user->name,
                'url' => action('PeopleController@show', $user->url_name)
            ];
            array_push($names, [
                'id' => $history->id,
                'type' => $history->type,
                'user' => $user_arr,
                'text' => $history->text,
                'time' => Carbon::parse($history->created_at)->diffForHumans(),
                'timestamp' => Carbon::parse($history->created_at)->timestamp,
                'canRollback' => Auth::user()->operation(12),
                'canReport' => true,
            ]);
        }
        array_unshift($names, [
            'text' => $topic->name
        ]);

        // for description
        $descriptions = [];
        foreach ($histories->filter(function ($value, $key) {
            return $value->type == 2;
        }) as $history) {
            $user = User::findOrFail($history->user_id);
            $user_arr = [
                'id' => $user->id,
                'name' => $user->name,
                'url' => action('PeopleController@show', $user->url_name)
            ];
            array_push($descriptions, [
                'id' => $history->id,
                'type' => $history->type,
                'user' => $user_arr,
                'text' => $history->text,
                'time' => Carbon::parse($history->created_at)->diffForHumans(),
                'timestamp' => Carbon::parse($history->created_at)->timestamp,
                'canRollback' => Auth::user()->operation(12),
                'canReport' => true,
            ]);
        }
        array_unshift($descriptions, [
            'text' => $topic->description
        ]);

        // for topics
        $topics = [];
        foreach ($histories->filter(function ($value, $key) {
            return 3 <= $value->type && $value->type <= 6;
        }) as $history) {
            $user = User::findOrFail($history->user_id);
            $user_arr = [
                'id' => $user->id,
                'name' => $user->name,
                'url' => action('PeopleController@show', $user->url_name)
            ];
            $topic = Topic::findOrFail($history->text);
            array_push($topics, [
                'id' => $history->id,
                'type' => $history->type,
                'user' => $user_arr,
                'topic' => [
                    'name' => $topic->name,
                    'url' => '/topic/' . $topic->id
                ],
                'time' => Carbon::parse($history->created_at)->diffForHumans(),
                'timestamp' => Carbon::parse($history->created_at)->timestamp,
                'canRollback' => Auth::user()->operation(12),
                'canReport' => true,
            ]);
        }


        // fore image
        $images = [];
        foreach ($histories->filter(function ($value, $key) {
            return $value->type == 7;
        }) as $history) {
            $user = User::findOrFail($history->user_id);
            $user_arr = [
                'id' => $user->id,
                'name' => $user->name,
                'url' => action('PeopleController@show', $user->url_name)
            ];
            array_push($images, [
                'id' => $history->id,
                'type' => $history->type,
                'topic' => [
                    'name' => $topic->name,
                    'url' => '/topic/' . $topic->id
                ],
                'user' => $user_arr,
                'time' => Carbon::parse($history->created_at)->diffForHumans(),
                'timestamp' => Carbon::parse($history->created_at)->timestamp,
                'canRollback' => false,
                'canReport' => true,
            ]);
        }

        // for merges
        $merges = [];
        foreach ($histories->filter(function ($value, $key) {
            return $value->type == 8 ||  $value->type == 9;
        }) as $history) {
            $user = User::findOrFail($history->user_id);
            $user_arr = [
                'id' => $user->id,
                'name' => $user->name,
                'url' => action('PeopleController@show', $user->url_name)
            ];
            $topic = Topic::findOrFail($history->text);
            array_push($merges, [
                'id' => $history->id,
                'type' => $history->type,
                'topic' => [
                    'name' => $topic->name,
                    'url' => '/topic/' . $topic->id
                ],
                'user' => $user_arr,
                'time' => Carbon::parse($history->created_at)->diffForHumans(),
                'timestamp' => Carbon::parse($history->created_at)->timestamp,
                'canRollback' => false,
                'canReport' => true,
            ]);
        }

        // for operations
        $operations = [];
        foreach ($histories->filter(function ($value, $key) {
            return $value->type == 10 || $value->type == 11;
        }) as $history) {
            $user = User::findOrFail($history->user_id);
            $user_arr = [
                'id' => $user->id,
                'name' => $user->name,
                'url' => action('PeopleController@show', $user->url_name)
            ];
            array_push($operations, [
                'id' => $history->id,
                'type' => $history->type,
                'user' => $user_arr,
                'text' => $history->text,
                'time' => Carbon::parse($history->created_at)->diffForHumans(),
                'timestamp' => Carbon::parse($history->created_at)->timestamp,
                'canRollback' => Auth::user()->operation(9),
                'canReport' => true,
            ]);
        }



        return [
            'pages' => $pages,
            'data' => [
                'names' => $names,
                'descriptions' => $descriptions,
                'topics' => $topics,
                'images' => $images,
                'merges' => $merges,
                'operations' => $operations
            ]
        ];


    }

    /**
     * Show question edit history
     *
     * @param $question_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getQuestionLog($question_id) {
        $question = Question::findOrFail($question_id);
        return view('question.question_log', compact('question'));
    }

    /**
     * ajax post request to get all question log
     *
     * @param $question_id
     * @param Request $request
     * @return array
     */
    public function postQuestionLog($question_id, Request $request) {
        $question = Question::findOrFail($question_id);

        // get necessary param
        $page = $request->get('page');
        $itemInPage = $request->get('itemInPage') ? $request->get('itemInPage') : $this->itemInPage;

        $pages = ceil($question->histories()->count() / $itemInPage);
        $histories = $question->histories()
            ->orderBy('created_at', 'desc')->get()->forPage($page, $itemInPage);

        $topics = [];
        // for topics
        foreach ($histories->filter(function ($value, $key) {
            return $value->type == 3 || $value->type == 4;
        }) as $history) {
            $user = User::findOrFail($history->user_id);
            $user_arr = [
                'id' => $user->id,
                'name' => $user->name,
                'url' => action('PeopleController@show', $user->url_name)
            ];
            $topic = Topic::findOrFail($history->text);
            array_push($topics, [
                'id' => $history->id,
                'type' => $history->type,
                'user' => $user_arr,
                'topic' => [
                    'name' => $topic->name,
                    'url' => '/topic/' . $topic->id
                ],
                'time' => Carbon::parse($history->created_at)->diffForHumans(),
                'timestamp' => Carbon::parse($history->created_at)->timestamp,
                'canRollback' => Auth::user()->operation(9),
                'canReport' => true,
            ]);
        }

        // title
        $titles = [];
        foreach ($histories->filter(function ($value, $key) {
            return $value->type == 1;
        }) as $history) {
            $user = User::findOrFail($history->user_id);
            $user_arr = [
                'id' => $user->id,
                'name' => $user->name,
                'url' => action('PeopleController@show', $user->url_name)
            ];
            array_push($titles, [
                'id' => $history->id,
                'type' => $history->type,
                'user' => $user_arr,
                'text' => $history->text,
                'time' => Carbon::parse($history->created_at)->diffForHumans(),
                'timestamp' => Carbon::parse($history->created_at)->timestamp,
                'canRollback' => Auth::user()->operation(9),
                'canReport' => true,
            ]);
        }
        array_unshift($titles, [
            'text' => $question->title
        ]);

        // content
        $contents = [];
        foreach ($histories->filter(function ($value, $key) {
            return $value->type == 2;
        }) as $history) {
            $user = User::findOrFail($history->user_id);
            $user_arr = [
                'id' => $user->id,
                'name' => $user->name,
                'url' => action('PeopleController@show', $user->url_name)
            ];
            array_push($contents, [
                'id' => $history->id,
                'type' => $history->type,
                'user' => $user_arr,
                'text' => $history->text,
                'time' => Carbon::parse($history->created_at)->diffForHumans(),
                'timestamp' => Carbon::parse($history->created_at)->timestamp,
                'canRollback' => Auth::user()->operation(9),
                'canReport' => true,
            ]);
        }
        array_unshift($contents, [
            'text' => $question->content
        ]);

        // rewards
        $rewards = [];
        foreach ($histories->filter(function ($value, $key) {
            return $value->type == 7;
        }) as $history) {
            $user = User::findOrFail($history->user_id);
            $user_arr = [
                'id' => $user->id,
                'name' => $user->name,
                'url' => action('PeopleController@show', $user->url_name)
            ];
            array_push($rewards, [
                'id' => $history->id,
                'type' => $history->type,
                'user' => $user_arr,
                'text' => $history->text,
                'time' => Carbon::parse($history->created_at)->diffForHumans(),
                'timestamp' => Carbon::parse($history->created_at)->timestamp,
                'canRollback' => Auth::user()->operation(9),
                'canReport' => true,
            ]);
        }
        array_unshift($rewards, [
            'text' => (string)$question->reward
        ]);

        // operations
        $operations = [];
        foreach ($histories->filter(function ($value, $key) {
            return $value->type == 5 || $value->type == 6;
        }) as $history) {
            $user = User::findOrFail($history->user_id);
            $user_arr = [
                'id' => $user->id,
                'name' => $user->name,
                'url' => action('PeopleController@show', $user->url_name)
            ];
            array_push($operations, [
                'id' => $history->id,
                'type' => $history->type,
                'user' => $user_arr,
                'text' => $history->text,
                'time' => Carbon::parse($history->created_at)->diffForHumans(),
                'timestamp' => Carbon::parse($history->created_at)->timestamp,
                'canRollback' => Auth::user()->operation(9),
                'canReport' => true,
            ]);
        }

        return [
            'pages' => $pages,
            'data' => [
                'titles' => $titles,
                'contents' => $contents,
                'topics' => $topics,
                'rewards' => $rewards,
                'operations' => $operations
            ]
        ];

    }

    /**
     * Show all answer edit history
     */
    public function getAnswerLog($answer_id) {
        $answer = Answer::findOrFail($answer_id);
        $question = $answer->question;
        return view('question.answer_log', compact('answer', 'question'));
    }

    /**
     * ajax post request to get all answer log
     *
     * @param $answer_id
     * @return array
     */
    public function postAnswerLog($answer_id, Request $request) {
        $answer = Answer::findOrFail($answer_id);
        $user = Auth::user();

        // get necessary param
        $page = $request->get('page');
        $itemInPage = $request->get('itemInPage') ? $request->get('itemInPage') : $this->itemInPage;

        $pages = ceil($answer->histories()->count() / $itemInPage);
        $histories = $answer->histories()->orderBy('created_at', 'desc')->get();

        $answers = [];
        foreach ($histories->filter(function ($value, $key) {
            return $value->type == 1;
        }) as $history) {
            $user = User::findOrFail($history->user_id);
            $user_arr = [
                'id' => $user->id,
                'name' => $user->name,
                'url' => action('PeopleController@show', $user->url_name)
            ];
            $history_arr = [
                'id' => $history->id,
                'type' => $history->type,
                'text' => $history->text,
                'time' => Carbon::parse($history->created_at)->diffForHumans(),
                'canRollback' => $user->operation(15),
                'canReport' => $answer->owner->id != $user->id,
                'timestamp' => Carbon::parse($history->created_at)->timestamp,
                'user' => $user_arr,
            ];

            array_push($answers, $history_arr);
        }

        // push current answer
        array_unshift($answers, [
            'text' => $answer->answer,
        ]);

        // operations
        $operations = [];
        foreach ($histories->filter(function ($value, $key) {
            return $value->type == 2 || $value->type == 3;
        }) as $history) {
            $user = User::findOrFail($history->user_id);
            $user_arr = [
                'id' => $user->id,
                'name' => $user->name,
                'url' => action('PeopleController@show', $user->url_name)
            ];
            array_push($operations, [
                'id' => $history->id,
                'type' => $history->type,
                'text' => $history->text,
                'time' => Carbon::parse($history->created_at)->diffForHumans(),
                'canRollback' => $user->operation(15),
                'canReport' => $answer->owner->id != $user->id,
                'timestamp' => Carbon::parse($history->created_at)->timestamp,
                'user' => $user_arr,
            ]);
        }


        return [
            'data' => [
                'answers' => $answers,
                'operations' => $operations
            ],
            'pages' => $pages
        ];
    }

    /**
     * Rollback history
     *
     * @param $history_id
     * @return array|void
     */
    public function postRollback($history_id) {
        $history = History::findOrFail($history_id);
        $forItem = $history->forItem;
        $user = Auth::user();

        switch (get_class($forItem)) {
            case 'App\Answer' :
                $answer = $forItem;
                if ($answer->owner->id != $user->id) return;

                $answer->update([
                    'answer' => $history->text
                ], ['history' => true]);

                break;
            case 'App\Question' :
                $question = $forItem;
                $old_topics_list = $question->topics()->lists('topic_id')->all();
                if ($history->type == 1) {
                    $question->update([
                        'title' => $history->text
                    ], ['history' => true]);
                } else if ($history->type == 2) {
                    $question->update([
                        'content' => $history->text
                    ], ['history' => true]);
                } else if ($history->type == 3) {
                    $question->topics()->detach($history->text);
                } else if ($history->type == 4) {
                    $question->topics()->attach($history->text);
                }

                // deduct user mark
                // edit question reject
                Point::add_point(User::findOrFail($history->user_id), 10, [$history->id, Auth::user()->id]);

                // record it in history
                $question->recordTopicsHistory($question->topics()->lists('topic_id')->all()
                    , $old_topics_list);
                break;
            case 'App\Topic' :
                $topic = $forItem;
                $old_subtopics_list = $topic->subtopics()->lists('subtopic_id')->all();
                $old_parent_topics_list = $topic->parent_topics()->lists('parent_topic_id')->all();
                if ($history->type == 1) {
                    $topic->update([
                        'name' => $history->text
                    ]);
                } else if ($history->type == 2) {
                    $topic->update([
                        'description' => $history->text
                    ]);
                } else if ($history->type == 3) {
                    $topic->parent_topics()->detach($history->text);
                } else if ($history->type == 4) {
                    $topic->parent_topics()->attach($history->text);
                } else if ($history->type == 5) {
                    $topic->subtopics()->detach($history->text);
                } else if ($history->type == 6) {
                    $topic->subtopics()->attach($history->text);
                }

                // record it in history
                $topic->recordSubTopicsHistory($topic->subtopics()->lists('subtopic_id')->all()
                    , $old_subtopics_list);
                $topic->recordParentTopicsHistory($topic->parent_topics()->lists('parent_topic_id')->all()
                    , $old_parent_topics_list);


                // deduct user mark
                // edit topic reject
                Point::add_point(User::findOrFail($history->user_id), 12, [$history->id, Auth::user()->id]);

                break;
        }

        return [
            'status' => true
        ];
    }
}
