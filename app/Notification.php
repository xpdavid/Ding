<?php

namespace App;

use Carbon\Carbon;
use View;
use Auth;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'type',
        'subject_id',
        'object_id',
        'has_read'
    ];

    /**
     * A notification belongs to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner() {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Query to get all unread notification
     *
     * @param $query
     * @return mixed
     */
    public function scopeUnread($query) {
        return $query->where('has_read', false);
    }

    /**
     * send notification to user
     *
     * @param User $user
     * @param $type
     * @param $subject_id
     * @param $object_id
     */
    public static function notification(User $user, $type, $subject_id, $object_id) {
        // check blocking
        $current_user = Auth::user();
        if ($user->blockings()->count() > 0) {
            if (in_array($current_user->id, $user->blockings->lists('id')->all())) {
                return ;
            }
        }

        if (static::hasNotification($user->notifications(), $type, $subject_id, $object_id)) {
            // already has the same notification
            $notification = static::getNotification($user->notifications(), $type, $subject_id, $object_id);
            // update the created at attribute
            $notification->updated_at = Carbon::now();
            $notification->save();
        } else {
            // create new notification
            $notification = static::createFromType($type, $subject_id, $object_id);
            $user->notifications()->save($notification);
            // mail to user
            MailRobot::notification($user, $notification);
        }
    }

    /**
     * Override create method to set has_read as false
     *
     * @param array $attributes
     * @return static
     */
    public static function createFromType($type, $subject_id, $object_id)
    {
        // a notification is unread at the first
        return parent::create([
            'type' => $type,
            'subject_id' => $subject_id,
            'object_id' => $object_id,
        ]); // TODO: Change the autogenerated stub
    }

    /**
     * Check is has the same notification
     *
     * @param $query
     * @param $type
     * @param $subject_id
     * @param $object_id
     * @return bool
     */
    public static function hasNotification($query, $type, $subject_id, $object_id) {
        return $query->whereType($type)
            ->whereSubjectId($subject_id)
            ->whereObjectId($object_id)
            ->count() > 0;
    }

    /**
     * Get the notification with three parameters and query
     *
     * @query $query
     * @param $type
     * @param $subject_id
     * @param $object_id
     * @return Notification
     */
    public static function getNotification($query, $type, $subject_id, $object_id) {
        return $query->whereType($type)
            ->whereSubjectId($subject_id)
            ->whereObjectId($object_id)
            ->first();
    }

    /**
     * Get notification summary
     */
    public function getTitleAttribute() {
        switch ($this->type) {
            case 1:
                return 'You got new invitation';
            case 2:
                return 'Question get answered';
            case 3:
            case 4:
            case 5:
                return 'someone @ you';
            case 6:
                return 'You got new reply';
            case 7:
            case 8:
            case 9:
                return 'You got vote';
            case 10:
                return 'You got follower';
            case 11:
                return 'You got new message';
            case 12:
            case 13:
            case 14:
                return 'updates for subscribers';
        }
    }

    /**
     * Render the notification according to the template
     * 1.	{{User}} invite you to answer question {{Question}}
     * 2.	{{User}} answer the question {{Question}}
     * 3.	{{User}} @ you in his/her {{answer}}
     * 4.	{{User}} @ you in his/her {{question}}
     * 5.	{{User}} @ you in his/her {{reply}}
     * 6.	{{User}} reply your {{Reply}}
     * 7.	{{User}} vote up (your) answers {{answer}}
     * 8.	Someone vote down (your) answers {{answer}}
     * 9.	{{User}} vote up your reply {{Reply}}
     * 10.	{{User}} subscribe you.
     * 11.	{{User}} send message to you
     * 12.  {{User}} post a question : {{Question}}
     * 13.  {{User}} subscribe to a question : {{Question}}
     * 14.  {{User}} subscribe to a topic : {{Topic}}
     * @return string
     */
    public function getRenderedContentAttribute() {
        $subject = null;
        $object = null;
        switch ($this->type) {
            case 13:
            case 12:
            case 4:
            case 1:
                $subject = User::findOrFail($this->subject_id);
                $object = Question::findOrFail($this->object_id);
                break;
            case 2:
            case 7:
            case 3:
                $subject = User::findOrFail($this->subject_id);
                $object = Answer::findOrFail($this->object_id);
                break;
            case 9:
            case 6:
            case 5:
                $subject = User::findOrFail($this->subject_id);
                $object = Reply::findOrFail($this->object_id);
                break;
            case 8:
                $subject = Answer::findOrFail($this->object_id);
                break;
            case 10:
                $subject = User::findOrFail($this->subject_id);
                break;
            case 11:
                $subject = User::findOrFail($this->subject_id);
                $object = Message::findOrFail($this->object_id);
                break;
            case 14:
                $subject = User::findOrFail($this->subject_id);
                $object = Topic::findOrFail($this->object_id);
                break;
        }

        $view = View::make('notification.type_' . $this->type,
            [
                'subject' => $subject,
                'object' => $object
            ]);
        $content = $view->render();

        return $content;
    }

    /**
     * Mark notification as read.
     *
     */
    public function read() {
        $this->has_read = true;
        $this->save();
    }
}
