<?php

namespace App\Http\Controllers;

use App\Image;
use App\Topic;
use IImage;
use Illuminate\Http\Request;

use App\Http\Requests;

class ImageController extends Controller
{

    /**
     * Query database to get image
     *
     * @param $reference_id
     * @param $width
     * @param $height
     * @return mixed
     */
    public function image($reference_id, $width, $height) {
        $img = Image::findSimilarOrCreate($reference_id, $width, $height);
        if ($img) {
            return $img->response();
        } else {
            abort(404);
        }
    }

    /**
     * Return relevant image according to the query
     *
     * @param $query
     */
    public function autoGetting($query, $width, $height) {
        $width = intval($width);
        $height = intval($height);
        $query = urldecode($query);

        if (Topic::similarMatch($query)->exists()) {
            $topic = Topic::similarMatch($query)->first();
            $img = Image::findSimilarOrCreate($topic->avatar_img_id, $width, $height);
        } else {
            $img = IImage::make(public_path('static/images/not_find.png'))->fit($width, $height);
        }
        if ($img == null) {
            $img = IImage::make(public_path('static/images/not_find.png'))->fit($width, $height);
        }

        return $img->response();
    }

    /**
     * Response request to get original image
     *
     * @param $reference_id
     * @return mixed
     */
    public function original_image($reference_id) {
        $img = Image::findOrFail($reference_id);
        return $img->toIImage()->response();
    }
}
