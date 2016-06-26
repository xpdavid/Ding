<?php

namespace App\Http\Controllers;

use App\Image;
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
}
