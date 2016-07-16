<?php

namespace App\Http\Controllers;

use Auth;
use File;
use IImage;
use App\History;
use App\Image;
use App\Topic;
use App\Http\Requests;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    /**
     * Response ajax request to store topic img
     *
     * @param Request $request
     * @return array(json)
     */
    public function uploadTopicImg(Request $request) {
        $this->validate($request, [
            'croppedImage' => 'required|mimes:jpeg,bmp,png',
            'id' => 'required|integer'
        ]);

        $img = $request->file('croppedImage');
        $topic = Topic::findOrFail($request->get('id'));

        // generate file name
        $filename = 'topic-' . $topic->id . '.' . $img->extension();

        // check if topic folder exist
        if (!File::exists(base_path('images/topic'))) {
            File::makeDirectory(base_path('images/topic'), $mode = 0777, true, true);
        }

        // resize the image
        $img_resize = IImage::make($img->getRealPath());
        // resize the image to a width of 512 and constrain aspect ratio (auto height)
        $img_resize->resize(512, null, function ($constraint) {
            $constraint->aspectRatio();
        });


        // delete old file
        // exists than delete
        if (Image::where('id', '=', $topic->avatar_img_id)->exists()) {
            $old_img = Image::findOrFail($topic->avatar_img_id);
            $old_img->deleteAll();
        }

        // create new image instance
        $img_database = Image::create([
            'path' => 'images/topic/' . $filename,
            'width' => 512,
            'height' => $img_resize->height()
        ]);
        $img_database->save();

        // update reference id
        $img_database->reference_id = $img_database->id;
        $img_database->save();

        // update new user pic id
        $topic->avatar_img_id = $img_database->id;
        $topic->save();

        // save new image
        $img_resize->save(base_path('images/topic/' . $filename), 50); // medium quality

        // change by who?
        $user = Auth::user();

        // image change
        $history = History::create([
            'user_id' => $user->id,
            'type' => 7,
            'text' => 'N/A'
        ]);
        $topic->histories()->save($history);

        return [
            'status' => 'true'
        ];
    }


    /**
     * Answer ajax request to upload user profile pic
     *
     * @param Request $request
     * @return array
     */
    public function uploadProfileImg(Request $request) {
        $this->validate($request, [
            'croppedImage' => 'required|mimes:jpeg,bmp,png'
        ]);

        $img = $request->file('croppedImage');
        $user = Auth::user();

        // generate file name
        $filename = 'profile-' . $user->id . '.' . $img->extension();
        $relative_fullpath = 'images/user/' . $user->id . '/' . $filename;

        // check if user folder exist
        if (!File::exists(base_path('images/user/' . $user->id))) {
            File::makeDirectory(base_path('images/user/' . $user->id), $mode = 0777, true, true);
        }

        // resize the image
        $img_resize = IImage::make($img->getRealPath());
        // resize the image to a width of 1024 and constrain aspect ratio (auto height)
        $img_resize->resize(800, null, function ($constraint) {
            $constraint->aspectRatio();
        });


        // delete old file
        // exists than delete
        if (Image::where('id', '=', $user->settings->profile_pic_id)->exists()) {
            $old_img = Image::findOrFail($user->settings->profile_pic_id);
            $old_img->deleteAll();
        }


        // create new image instance
        $img_database = Image::create([
            'path' => $relative_fullpath,
            'width' => 800,
            'height' => $img_resize->height()
        ]);
        $img_database->save();

        // update reference id
        $img_database->reference_id = $img_database->id;
        $img_database->save();

        // update new user pic id
        $settings = $user->settings;
        $settings->profile_pic_id = $img_database->id;
        $settings->save();

        // save new image
        $img_resize->save(base_path($relative_fullpath), 50); // medium quality

        return [
            'status' => 'true'
        ];
    }


    public function uploadUserImage(Request $request) {
        $this->validate($request, [
            'croppedImage' => 'required|mimes:jpeg,bmp,png'
        ]);

        $img = $request->file('croppedImage');
        $user = Auth::user();

        // generate file name
        $filename = 'user-' . $user->id . '-' . time() . '.' . $img->extension();
        $relative_fullpath = 'images/user/' . $user->id . '/' . $filename;

        // check if user folder exist
        if (!File::exists(base_path('images/user/' . $user->id))) {
            File::makeDirectory(base_path('images/user/' . $user->id), $mode = 0777, true, true);
        }

        // resize the image
        $img_resize = IImage::make($img->getRealPath());
        // resize the image to a width of 1024 and constrain aspect ratio (auto height)
        // small than 1024, than cancel resize
        if ($img_resize->width() < 1024) {
            $img_resize->resize(1024, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }


        // create new image instance
        $img_database = Image::create([
            'path' => $relative_fullpath,
            'width' => $img_resize->width(),
            'height' => $img_resize->height()
        ]);
        $img_database->save();

        // update reference id
        $img_database->reference_id = $img_database->id;
        $img_database->save();

        // save new image
        $img_resize->save(base_path($relative_fullpath), 60); // medium quality

        // calculate ratio
        $width = ($img_resize->width()) < 600 ? $img_resize->width() : 600;
        $height = ceil($width * ($img_resize->height() / $img_resize->width()));

        return [
            'status' => 'true',
            'url' => DImage($img_database->id, $width, $height)
        ];
    }
}
