<?php

namespace App\Http\Controllers;

use App\Image;
use Illuminate\Http\Request;

use App\Http\Requests;

class ImageController extends Controller
{
    /**
     * ImageController constructor.
     *
     * define middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

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
